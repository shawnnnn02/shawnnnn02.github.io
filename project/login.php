<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Login - PHP CRUD Tutorial</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Login</h1>
        </div>
        <!-- PHP insert code will be here -->
        <?php
        $save = true;
        $msg = "";
        if (!empty($_POST)) {
            // posted values
            
            //email//
            $passdw = htmlspecialchars(strip_tags($_POST['passdw']));
            if (empty($passdw)) {
                $msg = $msg . "Please do not leave password empty<br>";
                $save = false;

            }
            $email = htmlspecialchars(strip_tags($_POST['email']));
            if (empty($email)) {
                $msg = $msg . "Please do not leave email empty<br>";
                $save = false;
            }elseif (!preg_match("/@/", $email)) {
                $msg = "Invalid email format<br>";
                $save = false;
            }else{
                include 'config/database.php';
                $query = "SELECT email, passd, status FROM customer WHERE email=:email";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $num = $stmt->rowCount();
                if ($num == 0){
                    $msg = "This email is not fund<br>";
                    $save = false;
                }else{
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $email = $row['email'];
                    $passd = $row['passd'];
                    $status = $row['status'];
                    if ($passdw != $passd){
                        $msg = "Wrong password<br>";
                        $save = false;
                    }else{
                        if($status == "deactive"){
                            $msg = "You are deactive<br>";
                            $save = false;
                        }
                    }
                    
                }
                
            }
                      

            if ($save != false) {
                header('Location: customer_read.php');
            } else {
                echo "<div class='alert alert-danger'><b>Unable to login:</b><br>$msg</div>";
            }           
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form name="customer" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()" method="post" required>
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' value= "<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='text' name='passdw' class='form-control' value= "<?php if (isset($_POST['passdw'])) echo $_POST['passdw']; ?>" /></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Login' class='btn btn-primary' />
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>