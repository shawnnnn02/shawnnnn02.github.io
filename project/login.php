<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Login - PHP CRUD Tutorial</title>
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

        $msg = "";

        if (!empty($_POST)) {
            // posted values

            //email//
            $email = htmlspecialchars(strip_tags($_POST['email']));
            if (empty($email)) {
                $msg = $msg . "Please do not leave email empty<br>";
                $save = false;
            } elseif (!preg_match("/@/", $email)) {
                $msg = "Invalid email format<br>";
                $save = false;
            } else {
                include 'config/database.php';
                $query = "SELECT email FROM customer WHERE email=:email";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $num = $stmt->rowCount();
                if ($email != $num > 0) {
                    $msg = "Wrong Email<br>";
                    $save = false;
                }
            }
            $passd = htmlspecialchars(strip_tags($_POST['passd']));
            if (empty($passd)) {
                $msg = $msg . "Please do not leave password empty<br>";
                $save = false;
            } elseif (strlen($passd) <= 5 || !preg_match("/[a-z]/", $passd) || !preg_match("/[A-Z]/", $passd) || !preg_match("/[1-9]/", $passd)) {
                $msg = $msg . "Invalid password format (Password format should be more than 6 character, at least 1 uppercase, 1 lowercase & 1 number)<br>";
                $save = false;
            } else {
                include 'config/database.php';
                $query = "SELECT passd FROM customer WHERE passd=:passd";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':passd', $passd);
                $stmt->execute();
                $num = $stmt->rowCount();
                if ($passd != $num > 0) {
                    $msg = "Wrong Password<br>";
                    $save = false;
                }
            }

            if ($save != false) {
                header('Location: customer_read.php');
            } else {
                echo "<div class='alert alert-danger'><b>Unable to login:</b><br>$msg</div>";
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form name="customer" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()" method="post" required>
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='passd' class='form-control' value="<?php if (isset($_POST['passd'])) echo $_POST['passd']; ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Login' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->

</body>

</html>