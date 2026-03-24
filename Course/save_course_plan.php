<?php
include 'auth.php';
include 'db.php';

if(isset($_POST['save'])){
    $d = $_POST['date'];
    $day = $_POST['day_order'];
    $h = $_POST['hour'];
    $title = $_POST['course_title'];
    $content = $_POST['course_content'];

    mysqli_query($conn,
        "INSERT INTO course_plan(plan_date,day_order,hour,course_title,course_content)
         VALUES('$d','$day','$h','$title','$content')"
    );

    header("Location: dashboard.php");
}
?>
 