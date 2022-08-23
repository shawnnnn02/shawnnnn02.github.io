<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
</head>

<body>
    <!-- container -->
    <div class="container">
        
        <div class="page-header">
            <h1>Create An Orders</h1>
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
                <table class='table table-hover table-bordered'>
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
                            echo '<tr class="productrow border">
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
                        //}
                        ?>


                    </div>
                </table>
            </div>
            <div>
                <div>
                    <button type="button" class="add">Add More Product</button>
                    <button type="button" class="del">Delete Last Product</button>
                </div>
            </div>

            <div>
                <input type='submit' value='Save Changes' class='btn btn-primary' />
            </div>
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