<?php
include 'auth.php';
$data = $_SESSION['report_user'];

header("Content-Type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Monthly_Report.doc");

echo "<h2>Department of Computer Applications (UG)</h2>";
echo "<h3>Monthly Report</h3>";
echo "<p>Month: ".date("F",mktime(0,0,0,$data['month']))." ".$data['year']."</p>";
echo "<hr>";

echo "<h4>Attendance Summary</h4>";
// You can print same tables here (simplified)
?>
