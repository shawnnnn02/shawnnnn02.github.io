<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Sign In</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customer Account</h1>
        </div>

        <?php

        $firstname = $lastname = $email = $password = $gender = $birthdate = $status = "";

        $save = true;
        if (!empty($_POST)) {

            // posted values
            $msg = "";

            $firstname = $_POST['firstname'];
            if (empty($firstname)) {
                $msg = $msg . "Please do not leave firstname empty<br>";
                $save = false;
            }
            $lastname = $_POST['lastname'];
            if (empty($lastname)) {
                $msg = $msg . "Please do not leave lastname empty<br>";
                $save = false;
            }
            $email = $_POST['email'];
            if (empty($email)) {
                $msg = $msg . "Please do not leave email empty<br>";
                $save = false;
            } else {
                include 'config/database.php';
                $query = "SELECT * FROM customer WHERE email = :email";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $num = $stmt->rowCount();
                    if ($num > 0) {
                        $msg = "Repeated email detected<br>";
                        $save = false;
                }
            }
            $password = $_POST['password'];
            if (empty($password)) {
                $msg = $msg . "Please do not leave password empty<br>";
                $save = false;
            } elseif (strlen($password) <= 6) {
                $msg = $msg . "Password format should be more than 6 character<br>";
                $save = false;
            } elseif (!preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[1-9]/", $password)) {
                $msg = $msg . "Invalid password format<br>";
                $save = false;
            }
            if (isset($_POST['gender'])) {
                $gender = $_POST['gender'];
            } else {
                $msg = $msg . "Please do not leave gender empty<br>";
                $save = false;
            }
            if (isset($_POST['status'])) {
                $status = $_POST['status'];
            } else {
                $msg = $msg . "Please do not leave status empty<br>";
                $save = false;
            }
            $year_ = $_POST['year'];
            $month_ = $_POST['month'];
            $day_ = $_POST['day'];
            $birthdate = "$year_/$month_/$day_";

            // include database connection
            include 'config/database.php';

            try {
                // insert query
                $query = "INSERT INTO customer SET firstname=:firstname, lastname=:lastname, email=:email, password=:password, gender=:gender, birthdate=:birthdate, status=:status";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':birthdate', $birthdate);
                $stmt->bindParam(':status', $status);

                // specify when this record was inserted to the database

                // Execute the query
                if ($save != false) {
                    echo "<div class='alert alert-success'>Account Created</div>";
                    $stmt->execute();
                } else {
                    echo "<div class='alert alert-danger'>$msg</div>";
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
                    <td>First Name</td>
                    <td><input type='text' name='firstname' class='form-control' value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>" /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lastname' class='form-control' value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>" /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='email' class='form-control' name='email' value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='password' class='form-control' value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="male" <?php if (isset($_POST["gender"]) && ($gender == "male")) echo 'checked' ?>><label>Male</label>&nbsp;
                        <input type="radio" name="gender" value="female" <?php if (isset($_POST["gender"]) && ($gender == "female")) echo 'checked' ?>><label>Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Date Of Birth</td>
                    <td>
                        <?php
                        echo '<select id="day" name="day">';
                        for ($day = 1; $day <= 31; $day++) {
                            $selected = ($day_ == $day ? ' selected' : '');
                            echo '<option value="' . $day . '"' . $selected . '>' . $day . '</option>';
                        }
                        echo '</select>';
                        ?>

                        <!--month-->
                        <?php
                        $birthdate = date('m'); //current month

                        echo '<select id="month" name="month">';
                        for ($month = 1; $month <= 12; $month++) {
                            $selected = ($month_ == $month ? ' selected' : '');
                            echo '<option value="' . $month . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $month)) . '</option>';
                        }
                        echo '</select>';
                        if ((isset($_GET['month'])) && ($value))
                        ?>

                        <!--year-->
                        <?php
                    $year_start  = 2022;

                    echo '<select id="year" name="year">';
                    for ($year = $year_start; $year >= 1990; $year--) {
                        $selected = ($year_ == $year ? ' selected' : '');
                        echo '<option value="' . $year . '"' . $selected . '>' . $year . '</option>';
                    }
                    echo '</select>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name="status" value="active" <?php if (isset($_POST['status']) && ($status == "active")) echo "checked" ?>><label>Active</label>&nbsp;
                        <input type="radio" name="status" value="deactive" <?php if (isset($_POST['status']) && ($status == "deactive")) echo "checked" ?>><label>Deactive</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customer</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>