<?php
function dropdown($sday = "", $smonth = "", $syear = "", $datetype = "")
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

    //---v---select day---v---//
    $nameday = $datetype . "_day";
    $namemonth = $datetype . "_month";
    $nameyear = $datetype . "_year";

    echo "<select name= $nameday>";
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
?>

<?php
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
    <title>PDO - Create Customer - PHP CRUD Tutorial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <?php
    include 'head/head.php';
    ?>
</head>

<body>
    <!-- container -->
    <div class="container">
    <?php include 'navbar/navbar.php'; ?>
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        $save = true;
        if (!empty($_POST)) {
            // posted values
            //name check//
            $msg = "";
            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
            if (empty($firstname)) {
                $msg = $msg . "Please do not leave firstname empty<br>";
                $save = false;
            }
            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
            if (empty($lastname)) {
                $msg = $msg . "Please do not leave lastname empty<br>";
                $save = false;
            }

            //email//
            $email = htmlspecialchars(strip_tags($_POST['email']));
            if (empty($email)) {
                $msg = $msg . "Please do not leave email empty<br>";
                $save = false;
            } elseif (!preg_match("/@/", $email)) {
                $msg = "Invalid email format<br>";
                $save = false;
            }
            include 'config/database.php';
            $query = "SELECT email FROM customer WHERE email=:email";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $msg = "This email is duplicates<br>";
                $save = false;
            }


            $passd = htmlspecialchars(strip_tags($_POST['passd']));
            if (empty($passd)) {
                $msg = $msg . "Please do not leave password empty<br>";
                $save = false;
            } elseif (strlen($passd) <= 5 || !preg_match("/[a-z]/", $passd) || !preg_match("/[A-Z]/", $passd) || !preg_match("/[1-9]/", $passd)) {
                $msg = $msg . "Invalid password format (Password format should be more than 6 character, at least 1 uppercase, 1 lowercase & 1 number)<br>";
                $save = false;
            }
            $confirmpassd = $_POST['confirmpassd'];
            if (empty($confirmpassd)) {
                $msg = $msg . "Please do not leave confirm password empty<br>";
                $save = false;
            } elseif ($confirmpassd != $passd) {
                $msg = $msg . "Password must be same with confirm password";
                $save = false;
            }

            //birth date check//
            $birth_date = $_POST['birth_date_year'] . "-" . $_POST['birth_date_month'] . "-" . $_POST['birth_date_day'];
            $today = date('Y-n-d');
            $date1 = date_create($birth_date);
            $date2 = date_create($today);
            $diff = date_diff($date1, $date2);
            if (validateDate($birth_date) == false) {
                $msg = $msg . "Birthdate selected is not exist<br>";
                $save = false;
            } elseif ($diff->format("%R%a") < 6570) {
                $msg = $msg . "Customer must be over 18 years old<br>";
                $save = false;
            }
            $today = date('Y-n-d');
            $date1 = date_create($birth_date);
            $date2 = date_create($today);
            $diff = date_diff($date1, $date2);


            //status check//
            if (isset($_POST['gender'])) {
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
            } else {
                $msg = $msg . "Please do not leave gender empty<br>";
                $save = false;
            }

            if (isset($_POST['status'])) {
                $status = htmlspecialchars(strip_tags($_POST['status']));
            } else {
                $msg = $msg . "Please do not leave status empty<br>";
                $save = false;
            }
            // new 'image' field
            $user_image = !empty($_FILES["user_image"]["name"])
                ? sha1_file($_FILES['user_image']['tmp_name']) . "-" . basename($_FILES["user_image"]["name"])
                : "";
            $user_image = htmlspecialchars(strip_tags($user_image));

            if ($user_image) {

                $target_directory = "uploads/";
                $target_file = $target_directory . $user_image;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                // error message is empty
                $file_upload_error_messages = "";

                // make sure certain file types are allowed
                $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                if (!in_array($file_type, $allowed_file_types)) {
                    $file_upload_error_messages .= "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                }
                // make sure file does not exist
                if (file_exists($target_file)) {
                    $file_upload_error_messages .= "<div>Image already exists. Try to change file name.</div>";
                }
                // make sure submitted file is not too large, can't be larger than 1MB
                if ($_FILES['user_image']['size'] > 1024000) {
                    $file_upload_error_messages .= "<div>Image must be less than 1 MB in size.</div>";
                }
                // make sure the 'uploads' folder exists
                // if not, create it
                if (!is_dir($target_directory)) {
                    mkdir($target_directory, 0777, true);
                }
            }
            // if $file_upload_error_messages is still empty
            if (empty($file_upload_error_messages)) {
                // it means there are no errors, so try to upload the file
                if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file)) {
                    // it means photo was uploaded
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "<div>Unable to upload photo.</div>";
                    echo "<div>Update the record to upload photo.</div>";
                    echo "</div>";
                }
            } // if $file_upload_error_messages is NOT empty
            else {
                // it means there are some errors, so show them to user
                echo "<div class='alert alert-danger'>";
                echo "<div>{$file_upload_error_messages}</div>";
                echo "<div>Update the record to upload photo.</div>";
                echo "</div>";
            }

            // include database connection
            include 'config/database.php';
            include 'function/function.php';
            
            try {
                // insert query
                $query = "INSERT INTO customer SET firstname=:firstname, lastname=:lastname, email=:email, passd=:passd, confirmpassd=:confirmpassd, birth_date=:birth_date, gender=:gender, status=:status, user_image=:user_image, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':passd', $passd);
                $stmt->bindParam(':confirmpassd', $confirmpassd);
                $stmt->bindParam(':birth_date', $birth_date);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':user_image', $user_image);

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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                    <td><input type='text' name='email' class='form-control' value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='passd' class='form-control' value="<?php if (isset($_POST['passd'])) echo $_POST['passd']; ?>" /></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='text' name='confirmpassd' class='form-control' value="<?php if (isset($_POST['confirmpassd'])) echo $_POST['confirmpassd']; ?>" /></td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td>
                        <?php
                        $yearago = date("Y", strtotime('18 years ago'));
                        dropdown($sday = "", $smonth = "", $syear = $yearago, $datetype = "birth_date");
                        ?>
                    </td>

                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="male" <?php if (isset($_POST["gender"]) && ($gender == "male")) echo 'checked'; ?>><label>Male</label>&nbsp;
                        <input type="radio" name="gender" value="female" <?php if (isset($_POST["gender"]) && ($gender == "female")) echo 'checked'; ?>><label>Female</label>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name="status" value="active" <?php if (isset($_POST["status"]) && ($status == "active")) echo 'checked'; ?>><label>Active</label>&nbsp;
                        <input type="radio" name="status" value="deactive" <?php if (isset($_POST["status"]) && ($status == "deactive")) echo 'checked'; ?>><label>Deactive</label>
                    </td>
                </tr>
                <tr>
                    <td>Profile Image</td>
                    <td><input type="file" name="user_image"/></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customer list</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <?php include 'footer/footer.php'; ?>

</body>
</html>