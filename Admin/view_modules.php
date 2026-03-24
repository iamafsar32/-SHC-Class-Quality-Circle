<?php
// admin_dashboard.php
include 'auth.php'; // Assuming you want protection like previous pages

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "smartclass";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel | Smart Class</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #001f3f;
            --accent-color: #0074D9;
            --bg-color: #f4f7f9;
            --white: #ffffff;
            --text-main: #333;
            --sidebar-width: 260px;
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background-color: var(--bg-color); display: flex; color: var(--text-main); }

        /* --- Sidebar --- */
        .sidebar { width: var(--sidebar-width); background-color: var(--primary-color); height: 100vh; position: fixed; color: white; display: flex; flex-direction: column; z-index: 1000; }
        .sidebar-header { padding: 25px 20px; font-size: 1.2rem; font-weight: 700; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu { list-style: none; padding: 20px 0; margin: 0; flex-grow: 1; overflow-y: auto; }
        .sidebar-menu li a { padding: 15px 25px; display: block; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.95rem; transition: 0.2s; }
        .sidebar-menu li a:hover { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid var(--accent-color); }

        /* --- Main Content --- */
        .main-container { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); min-height: 100vh; display: flex; flex-direction: column; }
        header { background-color: var(--white); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 999; }
        .content-wrapper { padding: 40px; }

        .card { background: var(--white); border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 40px; }
        .card h2 { margin: 0 0 20px 0; font-size: 1.3rem; color: var(--primary-color); border-left: 5px solid var(--accent-color); padding-left: 15px; text-transform: uppercase; letter-spacing: 1px; }

        /* --- Table Styling --- */
        .table-responsive { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8fafc; padding: 12px 15px; text-align: left; color: var(--primary-color); border-bottom: 2px solid #edf2f7; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 12px 15px; border-bottom: 1px solid #edf2f7; font-size: 0.9rem; color: #444; }
        tr:hover { background-color: #f1f5f9; }

        /* Status Badge Colors */
        .status-present { color: #27ae60; font-weight: 700; }
        .status-absent { color: #e74c3c; font-weight: 700; }

        @media (max-width: 992px) {
            .sidebar { display: none; }
            .main-container { margin-left: 0; width: 100%; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">SMART CLASS</div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php">Dashboard Home</a></li>
        <li><a href="manage_logins.php">Manage Logins</a></li>
        <li><a href="manage_students.php">Manage Students</a></li>
        <li><a href="logout.php" style="color: #ff7675;">Logout</a></li>
    </ul>
</div>

<div class="main-container">
    <header>
        <div style="font-weight: 700; color: var(--primary-color);">ADMIN MODULE CONTROL PANEL</div>
        <div style="font-size: 0.85rem; color: #666;">System Status: Online</div>
    </header>

    <div class="content-wrapper">

        <div class="card">
            <h2>Academic Attendance Modules</h2>
            <div class="table-responsive">
                <?php
                $sql_acad = "SELECT * FROM academic_attendance ORDER BY date DESC LIMIT 20";
                $result_acad = $conn->query($sql_acad);
                if ($result_acad->num_rows > 0) {
                    echo "<table><tr><th>Reg No</th><th>Date</th><th>Program</th><th>Session</th><th>Status</th></tr>";
                    while($row = $result_acad->fetch_assoc()) {
                        echo "<tr><td>".$row["reg_no"]."</td><td>".$row["date"]."</td><td>".$row["program_name"]."</td><td>".$row["session"]."</td><td>".$row["status"]."</td></tr>";
                    }
                    echo "</table>";
                } else { echo "<p>No academic records found.</p>"; }
                ?>
            </div>
        </div>

        <div class="card">
            <h2>General Class Attendance Modules</h2>
            <div class="table-responsive">
                <?php
                $sql_att = "SELECT * FROM attendance ORDER BY date DESC LIMIT 20";
                $result_att = $conn->query($sql_att);
                if ($result_att->num_rows > 0) {
                    echo "<table><tr><th>Reg No</th><th>Date</th><th>Hour</th><th>Day Order</th><th>Status</th></tr>";
                    while($row = $result_att->fetch_assoc()) {
                        echo "<tr><td><strong>".$row["reg_no"]."</strong></td><td>".$row["date"]."</td><td>Hour ".$row["hour"]."</td><td>Day ".$row["day_order"]."</td><td>".$row["status"]."</td></tr>";
                    }
                    echo "</table>";
                } else { echo "<p>No attendance records found.</p>"; }
                ?>
            </div>
        </div>

        <div class="card">
            <h2>Student Achievement Module</h2>
            <div class="table-responsive">
                <?php
                $sql_achieve = "SELECT * FROM achievements ORDER BY id DESC";
                $result_achieve = $conn->query($sql_achieve);
                if ($result_achieve->num_rows > 0) {
                    echo "<table><tr><th>Reg No</th><th>Achievement Details</th></tr>";
                    while($row = $result_achieve->fetch_assoc()) {
                        echo "<tr><td>".$row["regno"]."</td><td>".$row["event_name"]."</td></tr>";
                    }
                    echo "</table>";
                } else { echo "<p>No achievements recorded.</p>"; }
                ?>
            </div>
        </div>


        <div class="card">
            <h2>Notice Board Module</h2>
            <div class="table-responsive">
                <?php
                $sql_notice = "SELECT * FROM notice_board ORDER BY id DESC";
                $result_notice = $conn->query($sql_notice);
                if ($result_notice->num_rows > 0) {
                    echo "<table><tr><th>Announcements / Notices</th></tr>";
                    while($row = $result_notice->fetch_assoc()) {
                        echo "<tr><td>".$row["notice_content"]."</td></tr>";
                    }
                    echo "</table>";
                } else { echo "<p>No active notices.</p>"; }
                ?>
            </div>
        </div>

        <div class="card">
            <h2>Reports Module</h2>
            <div class="table-responsive">
                <?php
                $sql_reports = "SELECT * FROM reports ORDER BY id DESC";
                $result_reports = $conn->query($sql_reports);
                if ($result_reports->num_rows > 0) {
                    echo "<table><tr><th>S.No</th><th>Report Details</th></tr>";
                    while($row = $result_reports->fetch_assoc()) {
                        echo "<tr><td>".$row["id"]."</td><td>".$row["report_type"]."</td></tr>";
                    }
                    echo "</table>";
                } else { echo "<p>No reports found.</p>"; }
                ?>
            </div>
        </div>

    </div> <footer style="padding: 20px 40px; font-size: 0.8rem; color: #888; border-top: 1px solid #eee; background: white; text-align: center;">
        &copy; <?php echo date('Y'); ?> Smart Class Admin Portal | All Rights Reserved
    </footer>
</div>

</body>
</html>

<?php $conn->close(); ?>