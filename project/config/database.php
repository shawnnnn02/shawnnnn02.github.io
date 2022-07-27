<!DOCTYPE html>
<html>

<body>

    <?php
    // used to connect to the database
    $host = "localhost";
    $db_name = "shawn02";
    $username = "shawn02";
    $password = "xinyi0509";

    try {
        $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully <br/>";
    }

    // show error
    catch (PDOException $exception) {
        echo "Connection error: " . $exception->getMessage();
    }
    ?>


</body>

</html>