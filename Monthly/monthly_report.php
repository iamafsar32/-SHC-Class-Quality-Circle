<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Monthly Report</title>
</head>
<body>

<header>
<h2>Generate Monthly Report</h2>
<a href="dashboard.php">Dashboard</a>
</header>

<form method="post" action="generate_report.php" class="card">
<label>Month</label>
<select name="month" required>
<?php
for($m=1;$m<=12;$m++){
 echo "<option value='$m'>".date("F", mktime(0,0,0,$m))."</option>";
}
?>
</select>

<label>Year</label>
<select name="year">
<?php for($y=2023;$y<=2030;$y++) echo "<option>$y</option>"; ?>
</select>

<button>Generate Report</button>
</form>

</body>
</html>
