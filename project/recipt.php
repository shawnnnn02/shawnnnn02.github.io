<!DOCTYPE HTML>
<html>

<head>
    <title>Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="container">
    <h1>Receipt</h1>  
    <?php
    //get order_id from url
    $orderID = $_GET['orderID'];
    
        include 'config/database.php';

        try{
            //customer order detail
            $query ="SELECT order_summary.orderID, order_summary.orderTime, customer.firstname, customer.lastname FROM order_summary
            INNER JOIN customer ON order_summary.customerID = customer.customerID WHERE order_summary.orderID =$orderID "; 
            
            $stmt = $con->prepare($query);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderID = $row['orderID'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $orderTime = $row['orderTime'];

        }// show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }


    ?>
    <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td><b>Order ID</b></td>
                <td><?php echo htmlspecialchars($orderID, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td><b>Customer Name</b></td>
                <td><?php echo htmlspecialchars($firstname . $lastname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td><b>Order Date</b></td>
                <td><?php echo htmlspecialchars($orderTime, ENT_QUOTES);  ?></td>
            </tr>
        </table>


            <?php
            include 'config/database.php';

            //order product detail
            $query = "SELECT * FROM order_details INNER JOIN products ON order_details.productID = products.productID WHERE order_details.orderID =$orderID";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $TotalAmount = 0;
            $num = $stmt->rowCount();
            if ($num > 0) {
                echo "<table class='table table-hover table-responsive table-bordered'>";

                echo "<tr>";
                echo "<th>Product Name</th>";
                echo "<th>Single Price</th>";
                echo "<th>Quantity</th>";
                echo "<th>Total Price</th>";
                echo "</tr>";
                

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // extract row
                    extract($row);

                    $totalPrice = $price*$quantity;
                    $TotalAmount = $TotalAmount + $totalPrice;
                    
                    // creating new table row per record
                    echo "<tr>";
                    echo "<td>{$name}</td>";
                    echo "<td>RM&nbsp{$price}</td>";
                    echo "<td>{$quantity}</td>";
                    echo "<td>RM&nbsp$totalPrice</td>";

                    
                }
            }
            ?>

            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td><b>Total Amount</b></td>
                    <td><?php echo"RM&nbsp $TotalAmount"?></td>
                </tr>
            </table>
    
    
    </div>

</body>

</html>