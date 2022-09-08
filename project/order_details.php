<style>
    .alineright{
        text-align:right; 
         float: right;
         width:50%;
         display:block;
    }
</style>

<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("location: index.php");
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
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
                $TotalAmount = 0;

                if ($num > 0) {
                    echo "<table class='table table-hover table-responsive table-bordered'>";

                    // create table head
                    echo "<tr class='border border-3'>";
                    //echo "<th class='border border-3'>Order Details ID</th>";
                    echo "<th>Order ID</th>";
                    echo "<th>Username</th>";
                    echo "<th>Product ID</th>";
                    echo "<th>Product Name</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Price</th>";
                    echo "<th>Total Price</th>";
                    echo "</tr>";

                    // retrieve table contents
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        extract($row);
                        // creating new table row per record

                        $totalprice = $quantity * $price;
                        $TotalAmount = $TotalAmount + $totalprice;
                        
                        echo "<tr class='border border-3'>";

                        echo "<td>{$orderID}</td>";
                        echo "<td>{$firstname} {$lastname}</td>";
                        echo "<td>{$productID}</td>";
                        echo "<td>{$name}</td>";
                        echo "<td>{$quantity}</td>";
                        echo "<td><div class='alineright'>{$price}</td>";
                        echo "<td><div class='alineright'>" . number_format($totalprice, 2) . "</td>";
                    }

                    // end table
                    echo "</table>";
                } else {
                    echo "<div class='alert alert-danger text-white'>No Records Found.</div>";
                }

                ?>

                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td><b>Total Amount</b></td>
                        <td>
                        <div class="alineright">
                        <span>
                        <?php echo number_format($TotalAmount, 2); ?>
                        </span>
                        </div>
                        </td>
                    </tr>
                </table>
            </table>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <td class="d-flex justify-content-end gap-2">
                <a href='order_listing.php' class='btn btn-danger'>Back to Order Lists</a>
        </div>

    </div>
    <!-- end .container -->

    <?php include 'footer/footer.php'; ?>
    
</body>

</html>