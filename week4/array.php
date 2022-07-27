<!DOCTYPE html>
<html>
<body>

<?php
$num = array(3, 6, 2, 45, 34, 63, 4, 63, 76, 21);
$evenArray = array();

echo "\nOrginal array: \n";
for ($i = 0; $i < count($num); $i++) {
    if($i != count($num)-1){
        echo "$num[$i] / ";
    }else{
        echo "$num[$i]";
    }
} 
echo "<br>";

$x = 0;
$y = 0;

for ($i = 0; $i < count($num); $i++) {
    if ($num[$i] % 2 == 0) {
        $evenArray[$x] = $num[$i];
        $x++;
    } else {
        $oddArray[$y] = $num[$i];
        $y++;
    }
}
echo "\nArray of even numbers : \n";
for ($i = 0; $i <= $x; $i++) {
    if($i != count($evenArray)-1){
        echo "$evenArray[$i] / ";
    }else{
        echo "$evenArray[$i]";
    }
}

?>
</body>
</html>