<!DOCTYPE html>
<html>
<body>

<?php
    // pyramid star pattern
    $size = 4;
    for($i = 0; $i < $size; $i++) {
        for($j = 0; $j < $size - $i - 1; $j++) { // print spaces
            echo "&nbsp;&nbsp;";
        }
        for($k = 0; $k < 2 * $i + 1; $k++) { // print stars
            echo "*";
        }
        echo "<br>";
    }
    for($i = 1; $i < $size; $i++) {
        for($j = 0; $j < $i; $j++) { // print spaces
            echo "&nbsp;&nbsp;";
        }
        for($k = 0; $k < 2 * ($size - $i) - 1; $k++) { // print stars
            echo "*";
        }
        echo "<br>";
    }
?>

</body>
</html>