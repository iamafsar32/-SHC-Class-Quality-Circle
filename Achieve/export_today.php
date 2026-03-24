<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievement History | Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #001f3f;
            --accent-color: #0074D9;
            --bg-color: #f4f7f9;
            --white: #ffffff;
            --text-main: #333;
            --sidebar-width: 260px;
            --border-color: #dde1e5;
        }

        * { box-sizing: border-box; }
        body { 
            margin: 0; 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-color);
            display: flex;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            height: 100vh;
            position: fixed;
            color: white;
            z-index: 1001;
            display: flex;
            flex-direction: column;
            transition: 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-menu { list-style: none; padding: 20px 0; margin: 0; flex-grow: 1; }
        .sidebar-menu li a {
            padding: 15px 25px;
            display: block;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: 0.2s;
            font-size: 0.9rem;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 4px solid var(--accent-color);
        }

        /* Download Button Specific Styling */
        .btn-download {
            background-color: rgba(0, 116, 217, 0.15);
            margin: 10px 15px;
            border-radius: 8px;
            border: 1px dashed var(--accent-color) !important;
            color: var(--accent-color) !important;
            font-weight: 600;
        }
        .btn-download:hover {
            background-color: var(--accent-color) !important;
            color: white !important;
        }

        /* --- Main Content --- */
        .main-container {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: 0.3s ease;
        }

        header {
            background-color: var(--white);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .mobile-header-left { display: flex; align-items: center; gap: 15px; }
        .menu-toggle {
            display: none;
            cursor: pointer;
            font-size: 1.5rem;
            color: var(--primary-color);
            background: none;
            border: none;
        }

        .content-wrapper { padding: 20px; flex: 1; }

        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }

        h2 {
            margin: 0 0 25px 0;
            font-size: 1.3rem;
            color: var(--primary-color);
            border-left: 5px solid var(--primary-color);
            padding-left: 15px;
        }

        /* --- Responsive Table --- */
        .table-container { overflow-x: auto; min-height: 300px; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            border: 1px solid var(--border-color);
        }
        table th {
            background-color: var(--primary-color);
            color: white;
            text-align: left;
            padding: 18px 20px;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
        }
        table td {
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            color: #444;
        }

        .level-badge {
            color: #0369a1;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: #e0f2fe;
            display: inline-block;
        }

        footer {
            padding: 20px;
            font-size: 0.8rem;
            color: #888;
            border-top: 1px solid #eee;
            background: white;
            text-align: center;
        }

        /* --- Mobile Media Queries --- */
        @media (max-width: 992px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .main-container { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }

            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { border: 1px solid var(--border-color); margin-bottom: 15px; border-radius: 8px; overflow: hidden; background: #fff; }
            td { border: none; border-bottom: 1px solid #f1f1f1; position: relative; padding-left: 45%; text-align: right; }
            td:before { 
                position: absolute; top: 12px; left: 15px; width: 40%; padding-right: 10px; 
                content: attr(data-label); font-weight: 700; color: var(--primary-color); 
                text-align: left; text-transform: uppercase; font-size: 0.7rem;
            }
            td:last-child { border-bottom: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        ACHIEVEMENTS
        <button class="menu-toggle" style="color:white;" onclick="toggleMenu()">✕</button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="history.php" class="active">View History</a></li>
        
        <li>
            <a href="export_today.php" class="btn-download">
                📄 Download Today's History
            </a>
        </li>

        <li style="margin-top: auto;"><a href="logout.php" style="color: #ff7675;">Logout</a></li>
    </ul>
</div>

<div class="main-container">
    <header>
        <div class="mobile-header-left">
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
            <div style="font-weight: 700; color: var(--primary-color); letter-spacing: 0.5px;">ACHIEVEMENT RECORDS</div>
        </div>
        <div style="font-size: 0.75rem; color: #666; font-weight: 600; text-align: right;"><?php echo date('d M Y'); ?></div>
    </header>

    <div class="content-wrapper">
        <div class="card">
            <h2>RECORDS LIST</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Reg No</th>
                            <th>Student Name</th>
                            <th>Award</th>
                            <th>Category</th>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $query = "SELECT a.*, s.name as student_name 
                                  FROM achievements a 
                                  LEFT JOIN students s ON a.regno = s.reg_no 
                                  ORDER BY a.event_date DESC";
                        
                        $q = mysqli_query($conn, $query);
                        
                        if(mysqli_num_rows($q) > 0){
                            while($r = mysqli_fetch_assoc($q)){
                                $studentName = $r['student_name'] ? $r['student_name'] : "Not Found";
                                echo "<tr>
                                    <td data-label='S.No' style='text-align:center;'>$i</td>
                                    <td data-label='Reg No' style='font-weight:700;'>{$r['regno']}</td>
                                    <td data-label='Student Name'>$studentName</td>
                                    <td data-label='Award'>{$r['award_name']}</td>
                                    <td data-label='Category'>{$r['category']}</td>
                                    <td data-label='Event'>{$r['event_name']}</td>
                                    <td data-label='Date' style='white-space:nowrap;'>".date('d-M-Y', strtotime($r['event_date']))."</td>
                                    <td data-label='Level'><span class='level-badge'>{$r['event_level']}</span></td>
                                </tr>";
                                $i++;
                            }
                        } else {
                            echo "<tr><td colspan='8' style='text-align:center; padding:30px;'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> Secured SFMS | v2.0
    </footer>
</div>

<script>
    function toggleMenu() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('active');
    }
</script>

</body>
</html>