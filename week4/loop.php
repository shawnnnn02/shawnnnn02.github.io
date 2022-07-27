<!DOCTYPE html>
<html>
<body>

<?php
function generateDate() {

$date = date('d-m-Y');

$day  = substr($date,0,2);
$month = substr($date,3,2); 
$year   = substr($date,6,4);

echo '<select name = "day">';
echo "<option> $day </option>";
	for($day = 1; $day <= 31; $day++){
		echo "<option value = '".$day."'>".$day."</option>";
}
echo '</select>';

echo '<select id="month">';
echo "<option> $month </option>";
for($month = 1; $month <= 12; $month++){
    echo "<option>". date('m', mktime(0,0,0,$month))."</option>" ;
}
echo '</select>';

echo '<select name = "year">';
echo "<option> $year </option>";
	$y = date("Y", strtotime("+8 HOURS"));
		for($year = 1990; $y >= $year; $y--){
			echo "<option value = '".$y."'>".$y."</option>";
}
echo '</select>';
}
generateDate();
?>

</body>
</html>