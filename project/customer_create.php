<?php

function dropdown($sday = "", $smonth = "", $syear = "", $name = "")
{

    if (empty($sday)) {
        $sday = date('d');
    }

    if (empty($smonth)) {
        $smonth = date('m');
    }

    if (empty($syear)) {
        $syear = date('Y');
    }

    $nameday = $name . "_day";
    $namemonth = $name . "_month";
    $nameyear = $name . "_year";

    //---v---select day---v---//

    echo "<select name = $nameday>";
    for ($day = 1; $day <= 31; $day++) {
        $s = ($day == $sday) ? 'selected' : '';
        echo "<option value = $day $s> $day </option>";
    }
    echo '</select>';

    //---v---select month---v---//
    echo "<select name = $namemonth>";
    for ($month = 1; $month <= 12; $month++) {
        $s = ($month == $smonth) ? 'selected' : '';
        echo "<option value = $month $s>" . date('F', mktime(0, 0, 0, $month)) . "</option>";
    }
    echo '</select>';

    //---v---select year---v---//
    $nowyear = date('Y');
    echo "<select name = $nameyear>";
    for ($year = 1990; $year <= $nowyear; $year++) {
        $s = ($year == $syear) ? 'selected' : '';
        echo "<option value = $year $s> $year </option>";
    }
    echo '</select>';
    echo "<br>";
}

function validateDate($date, $format = 'Y-n-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customer Account</h1>
        </div>
        <!-- html form to create product will be here -->

        <?php

        $save = true;

        if (!empty($_POST)) {

            // posted values

            $emptyMes = "";

            $first_name = $_POST['first_name'];

            if (empty($first_name)) {
                $emptyMes = $emptyMes . "please do not leave first name empty<br>";
                $save = false;
            }

            $last_name = $_POST['last_name'];

            if (empty($last_name)) {
                $emptyMes = $emptyMes . "please do not leave last name empty<br>";
                $save = false;
            }

            $email = $_POST['email'];

            if (empty($email)) {
                $emptyMes = $emptyMes . "please do not leave email empty<br>";
                $save = false;
            } elseif (!preg_match('/^[^0-9][_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
                $emptyMes = "Invalid email format<br>";
                $save = false;
            }

            $passw = $_POST['passw'];

            if (empty($passw)) {
                $emptyMes = $emptyMes . "Please do not leave password empty<br>";
                $save = false;
            } elseif (strlen($passw) <= 5) {
                $emptyMes = $emptyMes . "Password format should be more than 6 character<br>";
                $save = false;
            } elseif (!preg_match("/[a-z]/", $passw) || !preg_match("/[A-Z]/", $passw) || !preg_match("/[1-9]/", $passw)) {
                $emptyMes = $emptyMes . "Password must have <br>Uppercase letters [A-Z]<br>Lowercase letters [a-z] <br>Numbers [1-9]";
                $save = false;
            }

            if (isset($_POST['gender'])) {
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
            } else {
                $emptyMes = $emptyMes . "please do not leave gender empty<br>";
                $save = false;
            }

            $birth_date = $_POST['birth_date_year'] . "-" . $_POST['birth_date_month'] . "-" . $_POST['birth_date_day'];
            $today = date('Y-n-d');
            $birth = date_create($birth_date);
            $todate = date_create($today);
            $diff = date_diff($birth, $todate);
            if (validateDate($birth_date) == false) {
                $emptyMes = $emptyMes . "Birthdate selected is not exist<br>";
                $save = false;
            } elseif ($diff->format("%R%a") < 6570) {
                $emptyMes = $emptyMes . "Customer must be over 18 years old<br>";
                $save = false;
            }
            $today = date('Y-n-d');
            $birth = date_create($birth_date);
            $todate = date_create($today);
            $diff = date_diff($birth, $todate);

            if (isset($_POST['status'])) {
                $status = htmlspecialchars(strip_tags($_POST['status']));
            } else {
                $emptyMes = $emptyMes . "please do not leave status empty<br>";
                $save = false;
            }

            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO customer SET first_name=:first_name, last_name=:last_name, email=:email, passw=:passw, gender=:gender, birth_date=:birth_date, status=:status, created=:created";
                // prepare query for execution

                $stmt = $con->prepare($query);


                // bind the parameters
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':passw', $passw);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':birth_date', $birth_date);
                $stmt->bindParam(':status', $status);
                // specify when this record was inserted to the database
                // Execute the query


                if ($save != false) {
                    echo "<div class='alert alert-success'> Record was saved.</div>";
                    echo $stmt->execute();
                } else {
                    echo "<div class='alert alert-danger'> Unable to save record.<br>$emptyMes</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>
        <!-- html form here where the product information will be entered -->

        <form name="customer" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' class='form-control' value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input name='last_name' class='form-control' value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /> </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='passw' class='form-control' value="<?php if (isset($_POST['passw'])) echo $_POST['passw']; ?>" /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name='gender' value="male" <?php if (isset($_POST['gender']) && ($gender == "male")) echo 'checked'; ?>><label for="html">Male</label>

                        &nbsp;

                        <input type="radio" name='gender' value="female" <?php if (isset($_POST['gender']) && ($gender == "female")) echo 'checked'; ?>><label for="html">Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Birth Date</td>
                    <td>
                        <?php dropdown($sday = "", $smonth = "", $syear = "2021", $name = "birth_date"); ?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name='status' value="active" <?php if (isset($_POST['status']) && ($gender == "active")) echo 'checked'; ?>> <label for="html">Active</label>

                        &nbsp;

                        <input type="radio" name='status' value="deactive" <?php if (isset($_POST['status']) && ($gender == "deactive")) echo 'checked'; ?>><label for="html">Deactive</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                    </td>
                </tr>
            </table>
        </form>


    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


</body>

</html>