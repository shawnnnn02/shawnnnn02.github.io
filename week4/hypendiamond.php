<!DOCTYPE html>
<html>

<body>

    <?php
    function generateStars($s1, $s2)
    {

        echo "<pre>";
        for ($coluum = 1; $coluum <= 5; $coluum++) { //got many line u want
            for ($j = $coluum; $j <= 5; $j++) //create empty at left and push to right
                echo "&nbsp;";
            for ($j = $coluum; $j >= 1; $j--)
                if ($coluum % 2 == 0) {
                    echo $s1;
                } else {
                    echo $s2;
                }
            echo "<br>";
        }
        for ($coluum = 4; $coluum >= 1; $coluum--) { //got many line u want
            for ($j = $coluum; $j <= 5; $j++) //create empty at left and push to right
                echo "&nbsp;";
            for ($j = $coluum; $j >= 1; $j--)
                if ($coluum % 2 == 0) {
                    echo $s1;
                } else {
                    echo $s2;
                }
            echo "<br>";
        }
    }
    generateStars("-", "*");

    ?>

</body>

</html>