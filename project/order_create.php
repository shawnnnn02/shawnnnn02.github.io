<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Order Create</h1>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        // define variables and set to empty values

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // posted values
            $customerID = $_POST['customerID'];
            $productID = $_POST['productID'];
            $quantity = $_POST['quantity'];

            if (!empty($customerID)) {
                if (!empty(array_filter($productID))) {
                    if (!empty(array_filter($quantity))) {

                        try {

                            // insert query
                            $query = "INSERT INTO order_summary (customerID) VALUES (?)";
                            // prepare query for execution
                            $stmt = $con->prepare($query);
                            // bind the parameters
                            $stmt->bindParam(1, $customerID);
                            // Execute the query
                            if ($stmt->execute()) {

                                $_SESSION['success'] = "<div>Orders was created.</div>";
                                $last_order_id = $con->lastInsertId();
                                if ($last_order_id > 0) {

                                    for ($i = 0; $i < count($productID); $i++) {

                                        try {
                                            $query = "INSERT INTO order_details (orderID, productID, quantity) VALUES (:lastorderid, :productID, :quantity)";

                                            //prepare query for execute
                                            $stmt = $con->prepare($query);
                                            //posted values
                                            $stmt->bindParam(":lastorderid", $last_order_id);
                                            $stmt->bindParam(":productID", $productID[$i]);
                                            $stmt->bindParam(":quantity", $quantity[$i]);

                                            if ($stmt->execute()) {
                                            }
                                        } catch (PDOException $exception) {
                                            die('ERROR: ' . $exception->getMessage());
                                        }
                                    }
                                }

                                echo "<div class='alert alert-success'>Record was saved.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Unable to save record.</div>";
                            }
                        } catch (PDOException $exception) {
                            die('ERROR: ' . $exception->getMessage());
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Please Enter Your Quantity.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please Select Your Products.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Please Select Your Email.</div>";
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <?php
                $query = "SELECT customerID, email FROM customer";
                $stmt = $con->prepare($query);
                // execute our query
                $stmt->execute();
                echo '<tr class="border">
                            <td>Select Email</td>
                            <td>
                            <div class="col">';
                echo "<select class='form_select' name='customerID' >";
                echo '<option value="">Email</option>';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    echo "<option value='" . $customerID . "' >" . $email . "</option>";
                }

                try {
                    // prepare select query
                    $query = "SELECT * FROM products";
                    $stmt = $con->prepare($query);

                    // execute our query
                    $stmt->execute();
                    echo '<tr class="productrow">
                                <td>Select Product</td>
                                <td>
                                <div class="col">';
                    echo '<select class="form_select" name="productID[]" >';
                    echo '<option value="">Product</option>';

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<option value='" . $productID . "' >" . $name . "</option>";
                    }
                    echo "</select>
                            <div>
                                Quantity
                                <input type='number' name='quantity[]' class='form-control' value='' /></div></td></tr>";
                }
                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>


    </div>

    </div>
    <div>
        <div>
            <button type="button" class="add">Add More Product</button>
            <button type="button" class="del">Delete Last Product</button>
        </div>
    </div>

    <tr>
        <td></td>
        <td>
            <input type='submit' value='Save' class='btn btn-primary' />
            <a href='order_listing.php' class='btn btn-danger'>Back to Order List</a>
        </td>
    </tr>
    </table>
    </form>

    </div>
    <!-- end .container -->

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add')) {
                var element = document.querySelector('.productrow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.del')) {
                var total = document.querySelectorAll('.productrow').length;
                if (total > 1) {
                    var element = document.querySelector('.productrow');
                    element.remove(element);
                }
            }
        }, false);
    </script>

</body>

</html>