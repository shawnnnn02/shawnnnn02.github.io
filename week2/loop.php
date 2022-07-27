<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loop.css">
    <title>loop php(homework 2)</title>
</head>

<body>
<div id="container">
<div class="box1">
<select name = "day">
        <option>Day</option>
        <?php
	        for($day = 1; $day <= 31; $day++){
		       echo "<option value = '".$day."'>".$day."</option>";
		}
	?>
</select>
</div><!--the end of box1-->

<div class="box2">
<select name = "month">
	<option>Month</option>
	<?php
		for($i=0;$i<=11;$i++){
            $month=date('F',strtotime("first day of -$i month"));
            echo "<option value=$month>$month</option> ";
        }
	?>
</select>
</div><!--the end of box2-->

<div class="box3">
<select name = "year">
	<option>Year</option>
	<?php
		$y = date("Y", strtotime("+8 HOURS"));
		for($year = 1990; $y >= $year; $y--){
			echo "<option value = '".$y."'>".$y."</option>";
		}
	?>
</select>
</div><!--the end of box3-->
</div>
</body>
</html>