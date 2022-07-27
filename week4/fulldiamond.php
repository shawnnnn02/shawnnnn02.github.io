<!DOCTYPE html>
<html>
<body>

<?php
function generateStars($noOfStar,$s1){
    return [$noOfStar,$s1];
}

[$noOfStar,$s1] = generateStars("7","*");

$size = $noOfStar - 3;

echo "<pre>";
  for ($i = 1; $i <=$size; $i++) {
      for ($j = $i; $j <=$size; $j++)
        echo "&nbsp;";
      for ($j = 2 * $i - 1; $j >= 1; $j--)
        echo $s1;
        echo "<br>";
  }
  for ($i = $size -1; $i >= 1; $i--) {
      for ($j = 5 - $i; $j >= 1; $j--)
        echo "&nbsp;";
      for ($j = 2 * $i - 1; $j >= 1; $j--)
        echo $s1;
        echo "<br>";
  }
?>

</body>
</html>