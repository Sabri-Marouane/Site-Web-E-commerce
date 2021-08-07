<?php

function getTitle(){
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    }
    else{
        echo 'default';
    }
}

function check($select, $from, $value){
    global $cnx;
    $statement = $cnx->prepare("SELECT $select FROM $from WHERE $select=?");
    $statement -> execute(array($value));
    $count = $statement -> rowCount();
    return $count;
}

function getAllFrom($field, $table, $where=NULL, $and=NULL, $orderfield, $ordering='DESC'){
    global $cnx;
    $getall=$cnx->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getall -> execute();
    $alls=$getall-> fetchAll();
    return $alls;
}

// function Redirect($url, $seconds=1){
//     header("refresh:$seconds;url=$url");
//     exit();
// }
?>