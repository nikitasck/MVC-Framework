<?php 

namespace app\Core;

/*

@var $rows:              Count of rows in database. Getting value from constructor.
@var $rowsPerPage:       Count of rows per page. Getting value from constructor.
@var $pages:             Count of pages in paginator after manupulations.
@var $currentPage:       Page where user are.
@var $rowsAtCurrentPage: Numbers of rows choosen page.
@var $pagesArray:        Pages with items on this page: [page1=>[item1, item2, item3..n], ... n]
@var $paginator:         Result array: [[curentPage => $currentPage], [rowsAtCurrentPage => $rowsAtCurrentPage], [pages => $pages]].

*/

class Paginator
{
    protected $rows;
    protected $rowsPerPage;
    protected $pages;
    protected $currentPage; //if empty($current) $current = 1 or start.
    protected $rowsAtCurrentPage;
    protected $pagesArray = [];
    protected array $paginator = [];

    public function __construct($rows, $rowsPerPage)
    {
        $this->rows = $rows;
        $this->rowsPerPage = $rowsPerPage;
        $this->pages = intval(ceil($rows / $rowsPerPage));
        //$this->setPages($rows, $rowsPerPage);
        $this->setPagesArray();
    }

    //Returning amount of pages with round fractions up.
    public function getPages()
    {
        return $this->pages;
    }

    //If count of pages less then rows, and it is intager value set it as amount of paginator pages. 
    //If rows bigger then rows per page, divide and round up.
    public function setPages($rows, $rowsPerPage)
    {
        if($rows < $rowsPerPage && is_int(intval($rowsPerPage))){
            $this->pages = $rows;
        } else {
            $this->pages = ceil($rows / $rowsPerPage);
        }
    }

    //Set 1 if currentPage empty
    public function checkCurrentPageValue()
    {
        if(empty($currentPage)) {
            $this->currentPage = 1;
        }
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    //Creating array with number of page that contains numbers of rows: [1=>[1,2,3 ... 10], 2=>[11,12,13 ... 20] ... N].
    public function setPagesArray()
    {
        $firstItem = 0;

        //Getting count of page. And rounded it to the upper value.
        $pages = $this->getPages();

        for($i = 1; $i <= $pages; $i++){

            //contain slice of items per page.
            $bufferArr = [];

            //save data from first page
            $itemPerPageLoop = $this->rowsPerPage;

            //where start loop
            $itemPerPageLoop = $itemPerPageLoop * $i;

            //If paginator will have one page and rows are less then elements on it, save first and last elements of rows.
            //If pages are more then one do save first and last elements of rows for every page.
            if($this->getPages() <= 1){
                $min = 0;
                $max = 0;
                for($j = $firstItem;$j <= $this->rows; $j++){
                    
                    //push first and last element in array
                    if($j < $min){
                        $min = $j;
                    }
                    if($j > $max){
                        $max = $j;
                    }
                }
                array_push($bufferArr, $min, $max);
                $this->pagesArray[$i] = $bufferArr;
            } else {
                $buffer = 0;
                for($j = $firstItem;$j <= $this->rows; $j++){
                    
                    //push first and last element in array
                    if($j == $firstItem){
                        array_push($bufferArr, $j, $this->rowsPerPage);
                        $this->pagesArray[$i] = $bufferArr;
                        //setting next starting data for 
                        //$firstItem = $itemPerPageLoop;
                    }
                }
    
                //Starting loop with next slice - sorry
                $firstItem = $itemPerPageLoop;    
            }
        }

        //check for pagers, that contain one row
    }

    public function getPagesArray()
    {
        return $this->pagesArray;
    }

    //If user firstly open page with pagination, retun first page(pagesArray[1]). 
    //if user choosen page, found this page in arrays of page from paginator and return it. 
    //If page not found in paginator array return false.
    public function getPage()
    {
        if(!empty($_GET["page"])){
            if(array_key_exists($_GET["page"], $this->pagesArray)){
                return $this->pagesArray[$_GET["page"]];
            } else {
                return false;
            }
        } else {
            return $this->pagesArray[1];
        }
    }

}

?>