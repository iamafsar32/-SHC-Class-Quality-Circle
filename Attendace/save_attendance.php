<?php
include 'db.php';

$date=$_POST['date'];
$day=$_POST['dayorder'];
$hour=$_POST['hour'];
$regs=$_POST['reg'];
$status=$_POST['status'];

for($i=0;$i<count($regs);$i++){
mysqli_query($conn,
 "DELETE FROM attendance 
  WHERE reg_no='{$regs[$i]}' 
  AND date='$date' AND hour='$hour'"
);

mysqli_query($conn,
 "INSERT INTO attendance
 (reg_no,date,day_order,hour,status)
 VALUES
 ('{$regs[$i]}','$date','$day','$hour','{$status[$i]}')"
);
}

header("Location: absentees_today.php?date=$date&day=$day&hour=$hour");
?>
