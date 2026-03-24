<?php
include 'db.php';

$date=$_POST['date'];
$day=$_POST['dayorder'];
$session=$_POST['session'];
$program=$_POST['program_name'];
$regs=$_POST['reg'];
$status=$_POST['status'];

for($i=0;$i<count($regs);$i++){
mysqli_query($conn,
 "DELETE FROM academic_attendance 
  WHERE reg_no='{$regs[$i]}' 
  AND date='$date' 
  AND session='$session'
  AND program_name='$program'"
);

mysqli_query($conn,
 "INSERT INTO academic_attendance
 (reg_no,date,day_order,session,program_name,status)
 VALUES
 ('{$regs[$i]}','$date','$day','$session','$program','{$status[$i]}')"
);
}

header("Location: absentees_academic.php?date=$date&session=$session&program_name=".urlencode($program));
?>
