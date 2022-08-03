<?php
function dropdown($sday = "", $smonth = "", $syear = "", $datetype = "")
{

    if (empty($sday)) {
        $sday = date('j');
    }

    if (empty($smonth)) {
        $smonth = date('n');
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
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS →
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container-->
    <div class="container">
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id, name, description, price, manu_date, expr_date, status FROM products WHERE id = ? ";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $manu_date = $row['manu_date'];
            $expr_date = $row['expr_date'];
            $status = $row['status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- HTML form to update record will be here -->
        <!-- PHP post to update record will be here -->
        <?php
        // check if form was submitted
        $save = true;
        if ($_POST) {
            try {
                $msg = "";
                // posted values
                //name//
                $name = htmlspecialchars(strip_tags($_POST['name']));
                if (empty($name)) {
                    $msg = $msg . "Please do not leave name empty<br>";
                    $save = false;
                }

                $description = htmlspecialchars(strip_tags($_POST['description']));

                //price//
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
                if (isset($_POST['status'])) {
                    $status = htmlspecialchars(strip_tags($_POST['status']));
                }else {
                    $msg = $msg . "Please do not leave status empty<br>";
                    $save = false;
                }

                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE products SET name=:name, description=:description, price=:price, manu_date=:manu_date, expr_date=:expr_date, status=:status WHERE id = :id";

                $stmt = $con->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':manu_date', $manu_date);
                $stmt->bindParam(':expr_date', $expr_date);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':id', $id);
              
                if ($save != false) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                    $stmt->execute();
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record:<br>$msg</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>


        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture date </td>
                    <td>
                        <?php
                        
                        $yearsave_manu = substr($manu_date,0,4);
                        $monthsave_manu = substr($manu_date,5,2);
                        $daysave_manu = substr($manu_date,8,2);
                        //echo $row['manu_date'];
                        //echo $manu_date;
                        dropdown($sday = $daysave_manu, $smonth = $monthsave_manu, $syear = $yearsave_manu, $datetype = "manu_date");
                        ?>
                    </td>

                </tr>
                <tr>
                    <td>Expiry date</td>
                    <td>
                        <?php 
                        $yearsave_expr = substr($expr_date,0,4);
                        $monthsave_expr = substr($expr_date,5,2);
                        $daysave_expr = substr($expr_date,8,2);
                        //echo $row['expr_date'];
                        dropdown($sday = $daysave_expr, $smonth = $monthsave_expr, $syear = $yearsave_expr, $datetype = "expr_date");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name="status" value="available" <?php if($status == "available") echo 'checked'; ?>><label>Available</label>&nbsp;
                        <input type="radio" name="status" value="not_available" <?php if ($status == "not_available") echo 'checked'; ?>><label>Not Available</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
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