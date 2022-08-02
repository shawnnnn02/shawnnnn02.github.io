<!DOCTYPE HTML>
<html>

<head>
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        $save = true;
        if (!empty($_POST)) {
            // posted values
            
            $msg = "";

            //email//
            $email = htmlspecialchars(strip_tags($_POST['email']));
            if (empty($email)) {
                $msg = $msg . "Please do not leave email empty<br>";
                $save = false;
            } else if (!preg_match("/@/", $email)) {
                $msg = "Invalid email format!<br>";
                $save = false;
            }
            include 'config/database.php';
            $query = "SELECT email FROM customer WHERE email=:email";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0){
                $msg = "Invalid Email Format<br>";
                $save = false;
            }
            

            $passd = htmlspecialchars(strip_tags($_POST['passd']));
            if (empty($passd)) {
                $msg = $msg . "Please do not leave password empty<br>";
                $save = false;
            } elseif (strlen($passd) <= 5||!preg_match("/[a-z]/", $passd) || !preg_match("/[A-Z]/", $passd) || !preg_match("/[1-9]/", $passd)) {
                $msg = $msg . "Incorrect Password (Password format should be more than 6 character, at least 1 uppercase, 1 lowercase & 1 number)<br>";
                $save = false;
            }

            //status check//
            
            if (isset($_POST['status'])) {
                $status = htmlspecialchars(strip_tags($_POST['status']));  
            }else{
                $msg = $msg . "Please do not leave status empty<br>";
                $save = false;
            }


            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customer SET email=:email, passd=:passd, status=:status, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':passd', $passd);
                $stmt->bindParam(':status', $status);

                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                if ($save != false) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                    $stmt->execute();
                } else {
                    echo "<div class='alert alert-danger'><b>Unable to save record:</b><br>$msg</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form name="customer" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()" method="post" required>
            <table class='table table-hover table-responsive table-bordered'>
                
                
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' value= "<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='passd' class='form-control' value= "<?php if (isset($_POST['passd'])) echo $_POST['passd']; ?>" /></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name="status" value="active" <?php if (isset($_POST["status"]) && ($status == "active")) echo 'checked'; ?>><label>Active</label>&nbsp;
                        <input type="radio" name="status" value="deactive" <?php if (isset($_POST["status"]) && ($status == "deactive")) echo 'checked'; ?>><label>Deactive</label>
                    </td>
                </tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Login' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div>