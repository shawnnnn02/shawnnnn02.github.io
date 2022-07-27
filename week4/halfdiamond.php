<!DOCTYPE html>
<html>

<body>

  <?php

  function generateStars($maxStar)
  {

    echo "<pre>";
    for ($x = 1; $x < $maxStar; $x++) {  //got many line u want
      for ($y = 0; $y < $x; $y++) { //print how many star
        echo "*";
      }
      echo "<br>";
    }
    for ($x = $maxStar; $x > 0; $x--) { //got many line u want
      for ($y = 0; $y < $x; $y++) {  //print how many star
        echo "*";
      }
      echo "<br>";
    }
  }
  $maxStar = generateStars(6);

  ?>

</body>

</html>