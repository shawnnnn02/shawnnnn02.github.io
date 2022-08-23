<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customer’s DETAILS</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $customerID = isset($_GET['customerID']) ? $_GET['customerID'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';
        include 'function/function.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT customerID, firstname, lastname, email, passd, birth_date, gender, status, user_image FROM customer WHERE customerID = ? ";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $customerID);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form  //extract($row);
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $email = $row['email'];
            $passd = $row['passd'];
            $birth_date = $row['birth_date'];
            $gender = $row['gender'];
            $status = $row['status'];
            $user_image = $row['user_image'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>First Name</td>
                <td><?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><?php echo htmlspecialchars($passd, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?php echo htmlspecialchars($birth_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td><?php echo htmlspecialchars($status, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Photo</td>
                <td><?php echo " " .user_img($user_image) . " " ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='customer_read.php' class='btn btn-danger'>Back to customer list</a>
                </td>
            </tr>
        </table>

    </div> <!-- end .container -->

</body>

</html>