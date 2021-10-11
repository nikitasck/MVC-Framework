<?php 

$rows = 10000;
$itemPerPage = 10;
$pagesArray = [];
$startPage = 1;
$pages = ceil($rows / $itemPerPage);

for($i = 1; $i <= $pages; $i++){

    //contain slice of items per page.
    $bufferArr = [];

    //save data from first page
    $itemPerPageLoop = $itemPerPage;

    //where start loop
    $itemPerPageLoop = $itemPerPageLoop * $i;

    for($j = $startPage;$j <= $rows; $j++){
        
        //when 1-12 is working
        if($j <= $itemPerPageLoop){
            array_push($bufferArr, $j);
            $pagesArray[$i] = $bufferArr;
            //setting next starting data for 
            $startPage = $j;
        }
    }
}



echo var_dump($pagesArray);

?>