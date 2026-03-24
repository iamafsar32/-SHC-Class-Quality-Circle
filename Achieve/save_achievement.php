<?php
include 'auth.php';
include 'db.php';

if(isset($_POST['save'])){
    $reg=$_POST['regno'];
    $award=$_POST['award'];
    $cat=$_POST['category'];
    $event=$_POST['event'];
    $date=$_POST['event_date'];
    $level=$_POST['level'];

    mysqli_query($conn,
        "INSERT INTO achievements
        (regno,award_name,category,event_name,event_date,event_level)
        VALUES('$reg','$award','$cat','$event','$date','$level')"
    );

    header("Location: dashboard.php");
}
?>
