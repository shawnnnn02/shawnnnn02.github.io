<?php
// include database connection
include 'config/database.php';

try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $productID = isset($_GET['productID']) ? $_GET['productID'] :  die('ERROR: Record ID not found.');

    $query = "SELECT pimage FROM products WHERE productID = ? ";
    $stmt = $con->prepare($query);
    // this is the first question mark
    $stmt->bindParam(1, $productID);
    // execute our query
    $stmt->execute();
    $num = $stmt->rowCount();

    // store retrieved row to a variable
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $pimage = $row['pimage'];
   
    if ($num > 0) {
        header('Location: product_read.php?action=cantdelete');
        //die('Unable to delete record.');
    } else {

    // delete query
    $query = "DELETE FROM products WHERE productID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $productID);
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: product_read.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
}
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>