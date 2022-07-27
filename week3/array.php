<!DOCTYPE html>
<html>
<body>

<?php
$num = array(3, 6, 2, 45, 34, 63, 4, 63, 76, 21);
$oddArray = array();
$evenArray = array();
$total = 0;
$totalOdd = 0;  
$totalEven = 0;

echo "\nOrginal array: \n";
for ($i = 0; $i < count($num); $i++) {
    if($i != count($num)-1){
        echo "$num[$i] / ";
    }
    $total = $total + $num[$i];
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
    
    $totalEven = $totalEven + $evenArray[$i];
}
echo "<br>";
echo "\nArray of odd numbers : \n";
for ($i = 0; $i <= $y; $i++) {
    if($i != count($oddArray)-1){
        echo "$oddArray[$i] / ";
    }else{
        echo "$oddArray[$i]";
    }
    $totalOdd = $totalOdd + $oddArray[$i];
}
echo "<br>";
echo "Total Even: ",$totalEven,"<br>";
echo "Total Odd: ",$totalOdd,"<br>";
echo "Total Even And Odd: ",$total,"<br>";
?>

</body>
</html>