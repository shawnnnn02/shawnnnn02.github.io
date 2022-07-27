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
    <title>PDO - Create Product - PHP CRUD Tutorial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
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
            if (validateDate($expr_date) == false) {
                $msg = $msg . "Expiry date selected is not exist<br>";
                $save = false;
            } else if ($x->format("%R%a") < 0) { 
                $msg = $msg . "Expiry date should not earlier than manufacture date<br>";
                $save = false;
            }

            //status check//
            $status = htmlspecialchars(strip_tags($_POST['status']));
            if (empty($status)) {
                $msg = $msg . "Please do not leave status empty<br>";
                $save = false;
            }


            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, description=:description, price=:price, manu_date=:manu_date, expr_date=:expr_date, status=:status, created=:created";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':manu_date', $manu_date);
                $stmt->bindParam(':expr_date', $expr_date);
                $stmt->bindParam(':status', $status);
                // specify when this record was inserted to the database
                $created = date('Y-m-d H:i:s');
                $stmt->bindParam(':created', $created);

                // Execute the query
                // if (!empty($stmt->execute())) {
                //     echo "<div class='alert alert-success'>Record was saved.</div>";
                // }else {
                //     echo "<div class='alert alert-danger'>Unable to save record.</div>";
                // }

                if ($save != false) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                    $stmt->execute();
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record:<br>$msg</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form name="productform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()" method="post" required>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' value /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture date </td>
                    <td>
                        <?php
                        dropdown($sday = "", $smonth = "", $syear = "2021", $datetype = "manu_date");
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
                        <input type="radio" name="status" value="available" checked><label>Available</label>&nbsp;
                        <input type="radio" name="status" value="not_available"><label>Not Available</label>
                    </td>
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

</body>

</html>