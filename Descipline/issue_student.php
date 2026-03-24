<?php
session_start();
include 'db.php';

if(!isset($_SESSION['discipline'])) {
    header("Location: discipline_login.php");
    exit();
}

$reg = mysqli_real_escape_string($conn, $_GET['reg']);
$stu_query = mysqli_query($conn,"SELECT * FROM students WHERE reg_no='$reg'");
$stu = mysqli_fetch_assoc($stu_query);

// Handle Form Submission
if(isset($_POST['save'])){
    $date   = mysqli_real_escape_string($conn, $_POST['date']);
    $day    = mysqli_real_escape_string($conn, $_POST['day_order']);
    $hour   = mysqli_real_escape_string($conn, $_POST['hour']);
    $type   = mysqli_real_escape_string($conn, $_POST['issue_type']);
    $fine   = mysqli_real_escape_string($conn, $_POST['fine']);
    $action = mysqli_real_escape_string($conn, $_POST['action']);

    $res = mysqli_query($conn,"INSERT INTO discipline_issues 
        (reg_no, issue_date, day_order, Hour, issue_type, fine, action_taken, fine_status) 
        VALUES ('$reg', '$date', '$day', 'Hour $hour', '$type', '$fine', '$action', 'Not Submitted')");

    if($res){
        echo "<script>window.location.href='issue_student.php?reg=$reg';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Punishment | Neural Portal</title>
    
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
    </script>

    <style>
        :root { --bg-body: #f8fafc; --glass-bg: rgba(255, 255, 255, 0.7); --border: rgba(0, 0, 0, 0.05); --text-main: #0f172a; }
        .dark { --bg-body: #020617; --glass-bg: rgba(15, 23, 42, 0.6); --border: rgba(255, 255, 255, 0.1); --text-main: #f8fafc; }

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.4s ease; }
        .neural-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.15) 1px, transparent 0); background-size: 32px 32px; }
        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; right: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; left: -5%; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-100px, -50px) scale(1.1); } }
        
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(20px) saturate(180%); border: 1px solid var(--border); }
        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .form-input { background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 1rem; padding: 1rem; width: 100%; outline: none; transition: 0.3s; }
        .form-input:focus { border-color: #a855f7; box-shadow: 0 0 15px rgba(168, 85, 247, 0.2); }
    </style>
</head>
<body class="antialiased p-4">

<div class="neural-bg"></div>
<div class="orb-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<aside class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem]">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg">
            <i data-lucide="shield-alert" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">DISCIPLINE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Enforcement</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="discipline_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="users" class="w-5 h-5"></i> Student List
        </a>
        <a href="pending_fines.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="credit-card" class="w-5 h-5"></i> Pending Fines
        </a>
        <a href="issue_students_list.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="history" class="w-5 h-5"></i> Issue History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme Toggle
        </button>
        <a href="discipline_logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Record <span class="text-gradient">Violation</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Target: <?= htmlspecialchars($stu['name'] ?? 'Unknown') ?></p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black text-xl">
                <?= substr($stu['name'] ?? 'S', 0, 1) ?>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Registration ID</p>
                <span class="text-sm font-black italic uppercase"><?= $reg ?></span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[2.5rem] p-8 lg:p-12 mb-10 border-primary/10">
        <div class="flex items-center gap-4 mb-8">
            <i data-lucide="gavel" class="text-primary w-8 h-8"></i>
            <h2 class="text-2xl font-black uppercase tracking-tight">Issue New Punishment</h2>
        </div>

        <form method="post" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Date of Incident</label>
                <input type="date" name="date" class="form-input" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Day Order</label>
                <select name="day_order" class="form-input appearance-none cursor-pointer" required>
                    <?php for($i=1; $i<=6; $i++) echo "<option value='$i' class='bg-darkSurface'>Day $i</option>"; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Incident Hour</label>
                <select name="hour" class="form-input appearance-none cursor-pointer" required>
                    <?php for($i=1; $i<=5; $i++) echo "<option value='$i' class='bg-darkSurface'>Hour $i</option>"; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Violation Type</label>
                <select name="issue_type" class="form-input appearance-none cursor-pointer">
                    <option class="bg-darkSurface">Dress Code</option>
                    <option class="bg-darkSurface">ID Card</option>
                    <option class="bg-darkSurface">Behavioral Issues</option>
                    <option class="bg-darkSurface">Late Arrival</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Fine Amount (₹)</label>
                <select name="fine" class="form-input appearance-none cursor-pointer">
                    <?php foreach([5,10,20,50,100] as $f) echo "<option value='$f' class='bg-darkSurface'>₹$f</option>"; ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Action Taken</label>
                <input name="action" placeholder="Warning / Parents Called" class="form-input" required>
            </div>

            <div class="md:col-span-2 lg:col-span-3 pt-4">
                <button type="submit" name="save" class="w-full action-gradient text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.01] transition-all active:scale-95 flex items-center justify-center gap-4">
                    <i data-lucide="save" class="w-5 h-5"></i> Submit Record to Database
                </button>
            </div>
        </form>
    </div>

    <div class="glass-panel rounded-[3rem] p-6 lg:p-10">
        <div class="flex items-center gap-6 mb-10">
            <div class="w-2 h-12 bg-red-500 rounded-full shadow-[0_0_15px_rgba(239,68,68,0.5)]"></div>
            <h3 class="text-2xl font-black tracking-tight uppercase">Audit Trail & Logs</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Violation Details</th>
                        <th class="px-6 py-4 text-center">Fine</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Action Log</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $records = [];
                    $hist_q = mysqli_query($conn, "SELECT issue_date as event_date, issue_type, fine, fine_status, action_taken, 'discipline' as source FROM discipline_issues WHERE reg_no='$reg'");
                    while($row = mysqli_fetch_assoc($hist_q)) { $records[] = $row; }

                    $att_q = mysqli_query($conn, "SELECT date as event_date, hour, status, 'attendance' as source FROM attendance WHERE reg_no='$reg' AND status='Absent'");
                    while($row = mysqli_fetch_assoc($att_q)) {
                        $records[] = [
                            'event_date' => $row['event_date'],
                            'issue_type' => "Absent at Hour " . $row['hour'],
                            'fine' => "-",
                            'fine_status' => "Logged",
                            'action_taken' => "Auto-logged Absence",
                            'source' => 'attendance'
                        ];
                    }

                    usort($records, function($a, $b) { return strtotime($b['event_date']) - strtotime($a['event_date']); });

                    foreach($records as $r):
                        $color = ($r['fine_status'] == 'Submitted') ? 'text-green-500 bg-green-500/10' : 
                                 (($r['source'] == 'attendance') ? 'text-orange-500 bg-orange-500/10' : 'text-red-500 bg-red-500/10');
                    ?>
                    <tr class="glass-panel hover:bg-white/5 transition-all">
                        <td class="px-6 py-5 first:rounded-l-2xl border-y border-l border-white/5">
                            <span class="font-mono font-bold text-xs opacity-60"><?= date('d-M-Y', strtotime($r['event_date'])) ?></span>
                        </td>
                        <td class="px-6 py-5 border-y border-white/5 font-extrabold text-sm uppercase"><?= htmlspecialchars($r['issue_type']) ?></td>
                        <td class="px-6 py-5 border-y border-white/5 text-center font-bold text-primary"><?= ($r['fine'] != "-" ? "₹".$r['fine'] : "-") ?></td>
                        <td class="px-6 py-5 border-y border-white/5 text-center">
                            <span class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter border border-white/5 <?= $color ?>">
                                <?= $r['fine_status'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 last:rounded-r-2xl border-y border-r border-white/5 italic opacity-50 text-xs"><?= htmlspecialchars($r['action_taken']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($records)) echo "<tr><td colspan='5' class='text-center py-20 opacity-20 font-black uppercase tracking-[0.3em]'>System: No Records Found</td></tr>"; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">
            &copy; <?= date('Y') ?> DISCIPLINE NODE • ENFORCEMENT PORTAL
        </p>
    </footer>
</main>

<script>
    lucide.createIcons();

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

    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    }
</script>
</body>
</html>