<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="date.css">
    <title>date php(homework 1)</title>
</head>

<body>

    <div id="container">
        <div id="firstdate">
            <div class="box1">
                <?php
                echo date("d");
                ?>
            </div>

            <br />

            <div class="box2">
                <?php
                echo date("M Y");
                ?>
            </div>
        </div>
        <!--the end of firstbox-->
        <br />

        <div id="seconddate">
            <div class="box3">
                <?php
                echo date("d");
                ?>
            </div>

            <div class="box4">
                <?php
                echo date("M Y");
                ?>
            </div>
        </div>
        <!--the end of secondbox-->


        <div id="thirddate">
            <div class="box5">
                <?php
                echo date("d");
                ?>
            </div>

            <div class="box6">
                <?php
                echo date("M");
                ?>

                <br />

                <?php
                echo date("Y");
                ?>

            </div>
        </div>
        <!--the end of secondbox-->
    </div>
    <!--the end of container-->
</body>

</html>