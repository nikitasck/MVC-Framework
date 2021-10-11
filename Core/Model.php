<?php

namespace app\Core;

abstract class Model
{
    //Required rules for validation
    public const RULE_REQUIRED = 'requires';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_EMAIL = 'email';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_IMAGE_FILE = 'image_file';
    public const RULE_FILE_SIZE = 'image_size';
    public const RULE_FILE_TYPE = 'image_file_type';

    //Array to storage erros from validation
    public array $errors = [];

    protected array $ruleMessages  = [
        self::RULE_REQUIRED => 'This field is required',
        self::RULE_UNIQUE => 'This field must be unique',
        self::RULE_EMAIL => 'This field must be email',
        self::RULE_MIN => 'Length of this field must be lower that {min}',
        self::RULE_MAX =>'Length of this field must be greater that {max}',
        self::RULE_MATCH => 'This field must be the same as the {match}',
        self::RULE_IMAGE_FILE => 'Please, uploade image file', 
        self::RULE_FILE_SIZE => 'Sorry, your file is too large. Max size 7mb.',
        self::RULE_FILE_TYPE => 'Please, use correct image format: jpg,jpeg,png.'  
    ];

    //Appending new values to the existing ruleMassage array. Use in child class constructor.
    public function appendRuleMessages(array $rules)
    {
        $this->ruleMessages = array_merge($this->ruleMessages, $rules);
    }

    public function getRuleMessages(): array
    {
        return $this->ruleMessages;
    }

    //Inserting new data in matching model attributes.
    public function loadData($data)
    {
        foreach($data as $key => $value){
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    //Array of labels
    public function labels():array
    {
        return [];
    }

    //Defines rules for every attributes. Result will look like arr['attributeName',['Rule-1', 'Rule-2,'...'Rule-n']]
    abstract public function rules(): array;

    //Checking getting rules form fules() and applyin int to every attribute
    public function validation()
    {
        $arrFromRules = $this->rules();
        foreach($arrFromRules as $attribute => $rules) {
            $attr = $this->{$attribute}; //Model attribute
            foreach($rules as $rule) {
                $ruleName = $rule; //Going deep to the array of Rules
                if(!is_string($ruleName)) {
                    $ruleName = $rule[0]; //This means, that rule may contain arguments like: ['RULE_MAX', 255]. But, when you have just rule, you use it?
                }
            
                //Cheking for rules matching
                if($ruleName === self::RULE_REQUIRED && !$attr) {//If attrubute will empty, or rule name mathched with rules -> will push error 
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($attr, FILTER_VALIDATE_EMAIL)) {//If email attribute doesnt be email, or rule name mathched with rules -> will push error 
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($attr) < $rule['min']) {//If attribute size will be smaller than the rule defined min value, or rule name mathched with rules -> will push error 
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if($ruleName === self::RULE_MAX && strlen($attr) > $rule['max']) {//If attribute size will be bigger than the rule defined min value, or rule name mathched with rules -> will push error 
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if($ruleName === self::RULE_MATCH && $attr != $this->{$rule['match']}) {//If attrubute doesnt exist in model, or rule name mathched with rules -> will push error 
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                } 
                if($ruleName === self::RULE_UNIQUE) {//If attrubute doesnt matched with setted match value, or rule name mathched with rules -> will push error | retrive rule looks sent [self::RULE_UNIQUE, 'class' = 'password']
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttribute = :attr");
                    $statement->bindValue(":attr", $attr);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record) {
                        $this->addErrorForRule($attribute, SELF::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
                if($ruleName === self::RULE_IMAGE_FILE){
                    if(!is_file($_FILES[$attribute]["tmp_name"])){
                        $this->addError($attribute, 'Please, uploade file.');
                    }
                }
                if($ruleName === self::RULE_FILE_SIZE && ($_FILES[$attribute]["size"] > $rule['size'])){
                        $this->addError($attribute, 'Sorry, your file is too large. Max size 7mb.');
                    }
                if($ruleName === self::RULE_FILE_TYPE){
                    //Get file extension;
                    $file = $_FILES[$attribute]['name'];
                    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    if(!in_array($fileExtension, $rule['type'])){
                        $this->addError($attribute, 'Please, use correct image format: jpg,jpeg,png.');
                    }
                }
            }
        }

        //Return true if arrays with errors will be empty
        
        return empty($this->errors);
    }

    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    //Replaced {{error}} are from views
    public function addErrorForRule($attribute, $rule, $params = [])
    {
        $message = $this->getRuleMessages()[$rule] ?? 'Message for this rule not created';

        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }

       $this->addError($attribute, $message);
    }

    //Checking for errors in the attribute
    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    //Getting first error in the attribute
    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    //Recieving label for attribute
    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? '';
    }


}

?>