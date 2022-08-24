<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
</head>

<?php 
    include 'config/database.php';

    if($_POST){
        $product_id =$_POST['product'];

            $name = ($_POST['name']);
            


        try {
            // insert query
            $query = "INSERT INTO order_summary SET customerID=:customerID";
            // prepare query for execution
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $customerID);
            
            $stmt->execute();
            
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
    }

    
    
?>
<body>
    <form action="" method="post">
        <table class="table">
            <tr class="product-row">
                <td>Product</td>
                <td>
                    <div class="row">
                        <div class="col">
                        <?php   
                            $query = "SELECT productID, name FROM products ORDER BY productID DESC";
                            $stmt = $con->prepare($query);
                            $stmt->execute();

                            // this is how to get number of rows returned
                            $product_num = $stmt->rowCount();
                            if($product_num > 0){
                                echo '<select name="product">';
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        extract($row);
                                        echo "<option value='$productID'>$name</option>";
                                    }
                                    echo $name;
                                echo '</select>';
                            }
                            
                               
                        ?>
                        </div>
                        <div class="col">
                            <select name="quantity[]">
                                <option value="">Quantity</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
            <tr><td><input type="text" name="name"></td></tr>
            <tr>
                <td colspan="2">
                    <div class="d-flex justify-content-center flex-column flex-lg-row">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="add_one btn mb-1 mx-2">Add More Product</button>
                            <button type="button" class="del_last btn mb-1 mx-2">Delete Last Product</button>

                            <button type="submit">Submit</button>
                        </div>
                    </div>
                </td>
            </tr>

        </table>

    </form>

    <?php

        //if($_POST){
           // $product = $_POST['product'];
            //$quantity = $_POST['quantity'];
        
           // for($i=0;$i<count($product);$i++){
           //     echo $product[$i];
           //     echo $quantity[$i];
           //     echo "<br>";
            //}
        //}
     ?>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.product-row');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.del_last')) {
                var total = document.querySelectorAll('.product-row').length;
                if (total > 1) {
                    var element = document.querySelector('.product-row');
                    element.remove(element);
                }
            }
        }, false);
    </script>
</body>

</html>