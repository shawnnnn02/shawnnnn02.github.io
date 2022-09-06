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
            <h1>Order List</h1>
        </div>

                <!-- PHP code to read records will be here -->
                <?php
                // include database connection
                include 'config/database.php';

                // delete message prompt will be here
                $action = isset($_GET['action']) ? $_GET['action'] : "";

                // if it was redirected from delete.php
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success text-white'>Record was deleted.</div>";
                } else if ($action == 'deleteerror') {
                    echo "<div class='alert alert-danger text-white'>Unable to delete record.</div>";
                }

                // select all data
                $query = "SELECT o.orderID, o.customerID, o.orderTime, c.firstname, c.lastname, c.email

                                            FROM order_summary o
                                            INNER JOIN customer c
                                            ON o.customerID = c.customerID;
                                            ORDER BY o.orderID DESC";

                $stmt = $con->prepare($query);
                $stmt->execute();

                // this is how to get number of rows returned
                $num = $stmt->rowCount();


                // link to create record form
                echo "<a href='order_create.php' class='btn btn-primary m-b-1em'>Create New Order</a>";

                //check if more than 0 record found
                if ($num > 0) {

                    // data from database will be here
                    echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

                    //creating our table heading
                    
                    echo "<th>order ID</th>";
                    echo "<th>Customer ID</th>";
                    echo "<th>Name</th>";
                    echo "<th>Email</th>";
                    echo "<th>Order DateTime</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";

                    // table body will be here
                    // retrieve our table contents
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // extract row
                        // this will make $row['firstname'] to just $firstname only

                        extract($row);

                        // creating new table row per record
                        echo "<tr>";
                        echo "<td>{$orderID}</td>";
                        echo "<td>{$customerID}</td>";
                        echo "<td>{$firstname} {$lastname}</td>";
                        echo "<td>{$email}</td>";
                        echo "<td>{$orderTime}</td>";
                        echo "<td>";

                        // read one record
                        echo "<a href='order_details.php?orderID={$orderID}' class='btn btn-info m-r-1em'>Read</a>";

                        // we will use this links on next part of this post
                        echo "<a href='order_update.php?orderID={$orderID}' class='btn btn-primary m-r-1em'>Edit</a>";

                        // we will use this links on next part of this post
                        echo "<a href='#' onclick='delete_order({$orderID});'  class='btn btn-danger'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    // end table
                    echo "</table>";
                }
                // if no records found
                else {
                    echo "<div class='alert alert-danger text-white'>No records Order ID found.</div>";
                }
                ?>
            </table>
        </div>
    </div> <!-- end .container -->

    <script>
        // confirm record deletion
        function delete_order(orderID) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'delete_order.php?orderID=' + orderID;
            }
        }
    </script>

<?php include 'footer/footer.php'; ?>

</body>

</html>