<?php
session_start();
include 'db.php';

// --- Configuration ---
$ACADEMIC_START_DATE = "2025-01-01"; 

if (!isset($_SESSION['attendance'])) {
    header("Location: attendance_login.php");
    exit();
}

function getDayOrder($date){
    $start = new DateTime($GLOBALS['ACADEMIC_START_DATE']); 
    $current = new DateTime($date);
    $diff = $start->diff($current)->days;
    return ($diff % 6) + 1;
}

$defaultDate = date('Y-m-d');
$defaultDayOrder = getDayOrder($defaultDate);
?>

<!DOCTYPE html>
<html lang="en" class="dark" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance | AMS CORE</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#a855f7',
                        secondary: '#ec4899',
                        accent: '#22d3ee',
                        darkSurface: "#020617"
                    },
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    }
                }
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>

    <style>
        :root {
            --bg-body: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --border: rgba(0, 0, 0, 0.05);
            --text-main: #0f172a;
        }

        .dark {
            --bg-body: #020617;
            --glass-bg: rgba(15, 23, 42, 0.6);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
        }

        ::-webkit-scrollbar { display: none; }
        body { 
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.4s ease;
        }

        .neural-bg {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.1) 1px, transparent 0);
            background-size: 32px 32px;
        }

        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; left: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; right: -5%; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 50px) scale(1.1); }
        }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid var(--border);
        }

        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .present-btn { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white !important; }
        .absent-btn { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white !important; }

        .custom-input {
            background: rgba(0,0,0,0.05) !important;
            border: 1px solid var(--border) !important;
            color: inherit !important;
            border-radius: 1rem;
        }
        .dark .custom-input { background: rgba(255,255,255,0.03) !important; }

        .text-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

    <script>
        function toggle(btn){
            const statusInput = btn.previousElementSibling; 
            if(btn.value === "Present"){
                btn.value = "Absent";
                btn.classList.replace('present-btn', 'absent-btn');
                statusInput.value = "Absent";
            } else {
                btn.value = "Present";
                btn.classList.replace('absent-btn', 'present-btn');
                statusInput.value = "Present";
            }
        }

        function updateDayOrder() {
            const dateInput = document.getElementById('date');
            const dayOrderSelect = document.getElementById('dayorder');
            const academicStartDate = new Date("<?= $ACADEMIC_START_DATE ?>"); 
            const currentDate = new Date(dateInput.value);
            const diffTime = currentDate.getTime() - academicStartDate.getTime();
            const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));
            dayOrderSelect.value = (diffDays % 6) + 1;
        }

        function filterByRegNo() {
            const filter = document.getElementById('regFilter').value.toUpperCase();
            const rows = document.querySelectorAll('.student-row');
            rows.forEach(row => {
                const reg = row.querySelector('.reg-cell').textContent.toUpperCase();
                row.style.display = reg.includes(filter) ? "" : "none";
            });
        }
    </script>
</head>
<body class="antialiased">

<div class="neural-bg"></div>
<div class="orb-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<aside class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem]">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
            <i data-lucide="cpu" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">AMS CORE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Neural Node v4</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="#" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary">
            <i data-lucide="layers" class="w-5 h-5"></i>
            Attendance
        </a>
        <a href="attendance_report.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl opacity-60 hover:opacity-100 hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold">
            <i data-lucide="activity" class="w-5 h-5"></i>
            Analytics
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme Mode
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Digital <span class="text-gradient">Roster</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Academic Management Intelligence</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">AD</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Incharge</p>
                <span class="text-sm font-black">Live Session</span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 mb-10 shadow-2xl">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-2 h-8 action-gradient rounded-full"></div>
            <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em]">Protocol Parameters</h2>
        </div>
        
        <form method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 ml-1">Session Date</label>
                    <input type="date" id="date" name="date" value="<?= $defaultDate ?>" required onchange="updateDayOrder()" class="w-full p-4 custom-input font-bold text-sm outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 ml-1">Day Order</label>
                    <select id="dayorder" name="dayorder" class="w-full p-4 custom-input font-bold text-sm outline-none cursor-pointer">
                        <?php for($i=1;$i<=6;$i++){ $sel = ($i == $defaultDayOrder) ? "selected" : ""; echo "<option value='$i' $sel>$i</option>"; } ?>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 ml-1">Period / Hour</label>
                    <select name="hour" class="w-full p-4 custom-input font-bold text-sm outline-none cursor-pointer">
                        <?php for($h=1; $h<=5; $h++) echo "<option value='$h'>Hour $h</option>"; ?>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 ml-1">Target Batch</label>
                    <select name="year" class="w-full p-4 custom-input font-bold text-sm outline-none cursor-pointer">
                        <option value="1">I Year</option>
                        <option value="2">II Year</option>
                        <option value="3" selected>III Year</option>
                    </select>
                </div>
            </div>
            <button name="show" class="mt-8 w-full py-5 action-gradient rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:scale-[1.01] transition-all">
                Initialize Student Terminal
            </button>
        </form>
    </div>

    <?php
    if (isset($_POST['show'])) {
        $year = mysqli_real_escape_string($conn, $_POST['year']);
        $students = mysqli_query($conn, "SELECT * FROM students WHERE year='$year' ORDER BY reg_no ASC");
        if (!$students || mysqli_num_rows($students) === 0) {
            echo "<div class='glass-panel rounded-3xl p-16 text-center text-red-500 font-black uppercase tracking-widest'>Null Data Returned</div>";
        } else {
            ?>
            <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
                    <div class="flex items-center gap-6">
                        <div class="w-2 h-12 bg-emerald-500 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
                        <div>
                            <h3 class="text-2xl font-black tracking-tight uppercase">Active Listing</h3>
                            <p class="text-xs font-bold opacity-40 mt-1">Sector <?= $year ?> • <?= mysqli_num_rows($students) ?> Entities</p>
                        </div>
                    </div>
                    
                    <div class="relative w-full lg:w-80 group">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-30 group-focus-within:text-primary group-focus-within:opacity-100 transition-all"></i>
                        <input type="text" id="regFilter" onkeyup="filterByRegNo()" placeholder="FILTER REGISTER NO..." class="w-full pl-12 pr-4 py-3 text-[10px] tracking-widest rounded-xl outline-none custom-input">
                    </div>
                </div>

                <form method="post" action="save_attendance.php">
                    <input type="hidden" name="date" value="<?= $_POST['date'] ?>">
                    <input type="hidden" name="dayorder" value="<?= $_POST['dayorder'] ?>">
                    <input type="hidden" name="hour" value="<?= $_POST['hour'] ?>">

                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-y-3" id="studentTable">
                            <thead>
                                <tr class="opacity-30 text-[10px] font-black uppercase tracking-[0.5em]">
                                    <th class="px-6 pb-2 text-left">UID</th>
                                    <th class="px-6 pb-2 text-left">Identity</th>
                                    <th class="px-6 pb-2 text-left">Name</th>
                                    <th class="px-6 pb-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; while($s=mysqli_fetch_assoc($students)){ ?>
                                <tr class="student-row group hover:translate-x-1 transition-transform duration-300">
                                    <td class="px-6 py-5 bg-black/5 dark:bg-white/5 rounded-l-2xl font-mono text-[10px] opacity-40"><?= sprintf("%02d", $i++) ?></td>
                                    <td class="px-6 py-5 bg-black/5 dark:bg-white/5 font-mono font-bold text-primary italic reg-cell"><?= $s['reg_no'] ?></td>
                                    <td class="px-6 py-5 bg-black/5 dark:bg-white/5 font-bold text-sm tracking-tight"><?= $s['name'] ?></td>
                                    <td class="px-6 py-5 bg-black/5 dark:bg-white/5 rounded-r-2xl text-center w-48">
                                        <input type="hidden" name="reg[]" value="<?= $s['reg_no'] ?>">
                                        <input type="hidden" name="status[]" value="Present">
                                        <input type="button" value="Present" class="present-btn w-full py-2.5 rounded-xl font-black cursor-pointer text-[10px] uppercase tracking-widest active:scale-95 transition-all shadow-lg" onclick="toggle(this);">
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="mt-12 w-full action-gradient font-black py-6 rounded-[2rem] uppercase tracking-[0.4em] text-xs shadow-2xl shadow-primary/20 hover:scale-[1.01] transition-all">
                        Commit Records to Infrastructure
                    </button>
                </form>
            </div>
            <?php
        }
    }
    ?>
    <footer class="mt-20 mb-10 text-center no-print">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; 2026 SmartClass AMS Node • Encrypted Entry</p>
    </footer>
</main>

<script>
    lucide.createIcons();
    // Theme initialization
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>
</body>
</html>