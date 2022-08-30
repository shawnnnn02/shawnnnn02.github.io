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
            <h1>Order Details</h1>
        </div>
        <div class="table-responsive">
            <table class="table">

                <!-- PHP read one record will be here -->
                <?php
                // connection
                include 'config/database.php';

                $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : die('ERROR: Record User not found.');

                //select query 
                $query = "SELECT * FROM products p 
                                    INNER JOIN order_details od
                                    ON p.productID = od.productID
                                    INNER JOIN order_summary os 
                                    ON od.orderID = os.orderID
                                    INNER JOIN customer c
                                    ON c.customerID = os.customerID
                                    WHERE od.orderID = ?";

                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $orderID);
                $stmt->execute();
                $num = $stmt->rowCount();

                if ($num > 0) {
                    echo "<table class='table table-hover table-responsive table-bordered'>";

                    // create table head
                    echo "<tr class='border border-3'>";
                    //echo "<th class='border border-3'>Order Details ID</th>";
                    echo "<th class='border border-3'>Order ID</th>";
                    echo "<th class='border border-3'>Username</th>";
                    echo "<th class='border border-3'>Product ID</th>";
                    echo "<th class='border border-3'>Product Name</th>";
                    echo "<th class='border border-3'>Quantity</th>";
                    echo "<th class='border border-3'>Price</th>";
                    echo "<th class='border border-3'>Total Price</th>";
                    echo "</tr>";

                    // retrieve table contents
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        extract($row);
                        // creating new table row per record
                        $totalprice = $quantity * $price;
                        echo "<tr class='border border-3'>";
                        //echo "<td class='border border-3'>{$orderDetailsID}</td>";
                        echo "<td class='border border-3'>{$orderID}</td>";
                        echo "<td class='border border-3'>{$username}</td>";
                        echo "<td class='border border-3'>{$productID}</td>";
                        echo "<td class='border border-3'>{$name}</td>";
                        echo "<td class='border border-3'>{$quantity}</td>";
                        echo "<td class='border border-3'>{$price}</td>";
                        echo "<td class='border border-3 text-end'>" . number_format($totalprice, 2) . "</td>";
                    }

                    // end table
                    echo "</table>";
                } else {
                    echo "<div class='alert alert-danger text-white'>No Records Found.</div>";
                }
                ?>
            </table>
        </div>
        <div>
            <a href='order_listing.php' class='btn btn-danger'>Back to Order Lists</a>
        </div>

    </div>
    <!-- end .container -->
</body>

</html>