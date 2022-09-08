<?php
// include database connection
include 'config/database.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $customerID = isset($_GET['customerID']) ? $_GET['customerID'] :  die('ERROR: Record ID not found.');

    $query = "SELECT user_image FROM customer WHERE customerID = ? ";
    $stmt = $con->prepare($query);
    // this is the first question mark
    $stmt->bindParam(1, $customerID);
    // execute our query
    $stmt->execute();
    $num = $stmt->rowCount();

    // store retrieved row to a variable
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_image = $row['user_image'];
   
    if ($num > 0) {
        header('Location: customer_read.php?action=cantdelete');
        //die('Unable to delete record.');
    } else {

    // delete query
    $query = "DELETE FROM customer WHERE customerID = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $customerID);
    unlink("uploads/".$user_image); 
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: customer_read.php?action=deleted');
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
