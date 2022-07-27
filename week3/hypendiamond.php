<!DOCTYPE html>
<html>
<body>

<?php
echo "<pre>";
for ($i = 1; $i <=5; $i++) {
    for ($j = $i; $j <=4; $j++)
        echo "&nbsp;&nbsp;";
    for ($j = 2 * $i - 1; $j > 0; $j--)
        if($i % 2 == 0){
        	echo "-";   
        }else{
        	echo ("*");
        }
    echo "<br>";
}
for ($i = 4; $i >=1; $i--) {
    for ($j = $i; $j <=4; $j++)
        echo "&nbsp;&nbsp;";
    for ($j = 2 * $i - 1; $j > 0; $j--)
        if($i % 2 == 0){
        	echo "-";   
        }else{
        	echo ("*");
        }
    echo "<br>";
}
?>

</body>
</html>