<?php

require_once "add_purchases.php";
require_once "../connect/db_connect.php";


  
    
    function select_stock($product_id,$quantity){
        global $dbh;
   
        $stock = $dbh->exec('SELECT stock from stocks where id = "'.$product_id.'"');
        
        $stock += $quantity;
        $stmt->execute();
        return $stock;

    }

function update_stocks($product_id,$stock){
    global $dbh;
   
    $update = $dbh->exec('UPDATE stocks SET stock = "'.$stock.'" WHERE id = "'.$product_id.'"');
    $stmt = $dbh->prepare($update);
 
    $stmt->bindParam(':stock', $stock);
 
    $stock = $in["stock"];


    $stmt->execute();


}

function update_stock($product_id,$quantity){
    $stock = select_stock($product_id,$quantity);
    update_stocks($product_id,$stock);

    
}
    
        
