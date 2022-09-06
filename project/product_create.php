<?php
function dropdown($sday = "", $smonth = "", $syear = "", $datetype = "")
{

    if (empty($sday)) {
        $sday = date('j');
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
function validateDate($date, $format = 'Y-n-j')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
?>


<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create Product - PHP CRUD Tutorial</title>
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
            <h1>Create Product</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        $save = true;
        if (!empty($_POST)) {
            // posted values
            //name check//
            $msg = "";
            $name = htmlspecialchars(strip_tags($_POST['name']));
            if (empty($name)) {
                $msg = $msg . "Please do not leave name empty<br>";
                $save = false;
            }

            $description = htmlspecialchars(strip_tags($_POST['description']));

            //price check//
            $price = htmlspecialchars(strip_tags($_POST['price']));
            if (empty($price)) {
                $msg = $msg . "Please do not leave price empty<br>";
                $save = false;
            } else if (is_numeric($price) == false) {
                $msg = $msg . "Price should be numeric<br>";
                $save = false;
            }

            //manu and expr date check//
            $manu_date = $_POST['manu_date_year'] . "-" . $_POST['manu_date_month'] . "-" . $_POST['manu_date_day'];
            if (validateDate($manu_date) == false) {
                $msg = $msg . "Manufacture date selected is not exist<br>";
                $save = false;
            }

            $expr_date = $_POST['expr_date_year'] . "-" . $_POST['expr_date_month'] . "-" . $_POST['expr_date_day'];
            $dateM = date_create($manu_date);
            $dateE = date_create($expr_date);
            $x = date_diff($dateM, $dateE);
            // var_dump((int)($x->format("%m")));
            // var_dump((int)($x->format("%R%a")));
            if (validateDate($expr_date) == false) {
                $msg = $msg . "Expiry date selected is not exist<br>";
                $save = false;
            }
            if ((int)($x->format("%m") >= 1)) {
                if ((int)($x->format("%R%a") <= 0)) {
                    $msg = $msg . "Expiry date should not earlier than manufacture date<br>";
                    $save = false;
                }
            } elseif ((int)($x->format("%m") < 1)) {
                $msg = $msg . "Expiry date should not earlier than manufacture date<br>";
                $save = false;
            }

            //status check//
            if (isset($_POST['status'])) {
                $status = htmlspecialchars(strip_tags($_POST['status']));
            } else {
                $msg = $msg . "Please do not leave status empty<br>";
                $save = false;
            }

            // new 'image' field
            $pimage = !empty($_FILES["pimage"]["name"])
                ? sha1_file($_FILES['pimage']['tmp_name']) . "-" . basename($_FILES["pimage"]["name"])
                : "";
            $pimage = htmlspecialchars(strip_tags($pimage));
            if ($pimage) {

                $target_directory = "uploads/";
                $target_file = $target_directory . $pimage;
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
                if ($_FILES['pimage']['size'] > 1024000) {
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
                if (move_uploaded_file($_FILES["pimage"]["tmp_name"], $target_file)) {
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
            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, description=:description, price=:price, manu_date=:manu_date, expr_date=:expr_date, status=:status, pimage=:pimage, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':manu_date', $manu_date);
                $stmt->bindParam(':expr_date', $expr_date);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':pimage', $pimage);
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
        <form name="productform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()" method="post" enctype="multipart/form-data" required>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php if (isset($_POST['description'])) echo $_POST['description']; ?></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>" /></td>
                </tr>
                <tr>
                    <td>Manufacture date </td>
                    <td>
                        <?php
                        dropdown($sday = "", $smonth = "", $syear = "", $datetype = "manu_date");
                        ?>
                    </td>

                </tr>
                <tr>
                    <td>Expiry date</td>
                    <td>
                        <?php
                        dropdown($sday = "", $smonth = "", $syear = "", $datetype = "expr_date");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name="status" value="available" <?php if (isset($_POST["status"]) && ($status == "available")) echo 'checked'; ?>><label>Available</label>&nbsp;
                        <input type="radio" name="status" value="not_available" <?php if (isset($_POST["status"]) && ($status == "not_available")) echo 'checked'; ?>><label>Not Available</label>
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td><input type="file" name="pimage" class='form-control' value="<?php if (isset($_POST['pimage'])) echo $_POST['pimage']; ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
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