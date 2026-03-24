<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Operational Modules | The Syndicate</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --netflix-red: #E50914;
            --dark-bg: #000000;
            --card-bg: #141414;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--dark-bg);
            color: #fff;
            padding-top: 80px;
        }

        .navbar-syndicate {
            position: fixed;
            top: 0;
            width: 100%;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 4%;
            background: #000;
            z-index: 1000;
            border-bottom: 1px solid #333;
        }

        .logo-small {
            color: var(--netflix-red);
            font-family: 'Oswald', sans-serif;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .module-container {
            padding: 40px 4%;
        }

        .row-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #e5e5e5;
            border-left: 4px solid var(--netflix-red);
            padding-left: 15px;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }

        .module-card {
            background: var(--card-bg);
            border-radius: 4px;
            transition: transform 0.3s ease;
            cursor: pointer;
            border: 1px solid #333;
            text-decoration: none;
            display: block;
            overflow: hidden;
        }

        .module-card:hover {
            transform: scale(1.05);
            border-color: var(--netflix-red);
        }

        .card-img {
            height: 140px;
            background: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
        }

        .card-info {
            padding: 15px;
            background: var(--card-bg);
        }

        .card-info h4 {
            font-size: 16px;
            margin: 0;
            color: #fff;
            font-weight: 600;
        }

        .card-tag {
            font-size: 11px;
            color: #46d369;
            margin-top: 5px;
            display: block;
            font-weight: 700;
        }

        footer {
            padding: 50px 4%;
            color: #808080;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar-syndicate">
        <a href="index.html" class="logo-small">SMART CLASS</a>
        <div class="ms-auto">
             <a href="index.html" class="text-white text-decoration-none small opacity-75">Logout</a>
        </div>
    </nav>

    <div class="module-container">
        <h3 class="row-title">Active Operational Modules</h3>
        
        <div class="module-grid">
            <a href="Attendace/attendance_login.php" class="module-card">
                <div class="card-img">🕵️‍♂️</div>
                <div class="card-info">
                    <h4>Hour Attendance</h4>
                    <span class="card-tag">98% Active</span>
                </div>
            </a>

            <a href="Academic/academic_login.php" class="module-card">
                <div class="card-img">💼</div>
                <div class="card-info">
                    <h4>Academic Records</h4>
                    <span class="card-tag">High Priority</span>
                </div>
            </a>

            <a href="Leave/leave_login.php" class="module-card">
                <div class="card-img">📄</div>
                <div class="card-info">
                    <h4>Leave Permits</h4>
                    <span class="card-tag">New Requests</span>
                </div>
            </a>

            <a href="Discipline/discipline_login.php" class="module-card">
                <div class="card-img">🔪</div>
                <div class="card-info">
                    <h4>Discipline Core</h4>
                    <span class="card-tag">Strict Enforcement</span>
                </div>
            </a>

            <a href="GreenBoard/green_board_login.php" class="module-card">
                <div class="card-img">💵</div>
                <div class="card-info">
                    <h4>Green Board</h4>
                    <span class="card-tag">Updated</span>
                </div>
            </a>

            <a href="NoticeBoard/notice_board_login.php" class="module-card">
                <div class="card-img">📰</div>
                <div class="card-info">
                    <h4>The Bulletin</h4>
                    <span class="card-tag">3 New Notices</span>
                </div>
            </a>

            <a href="#" class="module-card">
                <div class="card-img">🍷</div>
                <div class="card-info">
                    <h4>Hall of Fame</h4>
                    <span class="card-tag">Elite Status</span>
                </div>
            </a>

            <a href="#" class="module-card">
                <div class="card-img">⚙️</div>
                <div class="card-info">
                    <h4>Syndicate Settings</h4>
                    <span class="card-tag">System Secure</span>
                </div>
            </a>
        </div>
    </div>

    <footer>
        &copy; 2026 Smart Class Syndicate. The Quality Circle.
    </footer>

</body>
</html>