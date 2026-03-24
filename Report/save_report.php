<?php
include 'auth.php';
include 'db.php';

$type     = $_POST['type'];
$event    = $_POST['event'];
$college  = $_POST['college'];
$district = $_POST['district'];
$date     = $_POST['event_date'];

/* Insert report (same as your logic) */
mysqli_query($conn,
"INSERT INTO reports(report_type, event_name, college_name, district, event_date)
 VALUES('$type', '$event', '$college', '$district', '$date')");

$rid = mysqli_insert_id($conn);

/* ✅ SAFETY: Clear old students for this report (prevents duplicates) */
mysqli_query($conn,
"DELETE FROM report_students WHERE report_id = '$rid'");

/* Insert only selected students */
if (!empty($_POST['students'])) {
    foreach ($_POST['students'] as $s) {
        list($r, $n) = explode("|", $s);

        mysqli_query($conn,
        "INSERT INTO report_students(report_id, regno, name)
         VALUES('$rid', '$r', '$n')");
    }
}

/* Redirect (same as your logic) */
header("Location: report_view.php?id=$rid");
exit;
