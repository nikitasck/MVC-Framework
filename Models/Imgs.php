<?php 

namespace app\Models;

use app\Core\Application;
use app\Core\DbModel;

class Imgs extends DbModel
{
    public $src = "";
    protected $defaultUserImg = 'user.png';

    public function tableName(): string
    {
        return 'imgs';
    }

    public function primaryKey(): string
    {
        return 'id';
    }
    
    public function attributes(): array
    {
        return ['src'];
    }

    public function rules(): array
    {
        return [
            'src' => [
                self::RULE_IMAGE_FILE, 
                [self::RULE_FILE_SIZE, 'size' => '7340000' ], 
                [self::RULE_FILE_TYPE, 'type' => ['png','jpg', 'jpeg']]
            ]
        ];
    }

    public function labels(): array
    {
        return [
            'src' => 'Article image'
        ];
    }
    
    
    //Storage given file and save path in $src property
    public function uploadImg()
    {
        //Storage directory
        $storageDir = $this->storageDefine();

        //Getting file info.
        $uploadFile = $storageDir .'/'. basename($_FILES["src"]["name"]);
        $fileName = strtolower(pathinfo($uploadFile, PATHINFO_FILENAME));
        $fileType = $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));


        //Check file for unique name or add random numbers at the end.
        $uploadFile = $this->fileCheck($storageDir, $uploadFile, $fileName, $fileType);

        
        //SaveFiles
        if(move_uploaded_file($_FILES["src"]["tmp_name"], $uploadFile)){
            //$this->src = $uploadFile;
            $this->src = $this->saveSrc($uploadFile);
            return true;
        }
    }

    //Get src that good for insrting.
    public function saveSrc($uploadFile)
    {
        $folder = '/storage/user-'.$this->getUserDirectory();
        $fileName = strtolower(pathinfo($uploadFile, PATHINFO_FILENAME));
        $fileType = $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        return $folder . '/' . $fileName . '.' . $fileType;
    }

    //Every user has own dir specified on their id-s in '/storage' directory.
    //If directory doesn't exists we are create a new one.
    public function storageDefine()
    {
        $storageDir = Application::$rootDir.'/public/storage/user-'.$this->getUserDirectory();

        if(!file_exists($storageDir)){
            mkdir($storageDir);
        }

        return $storageDir;
    }

    //If file with the same name exists, generate new file name with php tempnam() function and return new upload file(path/newFileName.type).
    public function fileCheck($storageDir, $uploadFile, $fileName, $fileType)
    {
        if(file_exists($uploadFile)) {
            $fileName = tempnam($storageDir, $fileName);
            $uploadFile = $fileName .'.'. $fileType;
            return $uploadFile;
        }

        return $uploadFile;
    }

    public function getUserDirectory()
    {
        if(Application::$app->session->get('user')){
            return Application::$app->session->get('user');
        } else {
            return 'empty';
        }
    }

    //save image file name for default picture
    public function setDefaultUserImageName(string $name)
    {
        $this->defaultUserImg = $name;
    }

    //get path + name of default user image
    public function getPathDefaultUserImage()
    {
        return '/storage/default/'.$this->defaultUserImg;
    }

    //save image file name for default picture
    public function setDefaultUserImage()
    {
        if($this->src = $this->getPathDefaultUserImage()){
            return true;
        }

    }
}

?>