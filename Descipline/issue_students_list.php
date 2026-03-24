<?php
session_start();
include 'db.php';

if(!isset($_SESSION['discipline'])) {
    header("Location: discipline_login.php");
    exit();
}

// Logic to calculate total fine amount
$total_q = mysqli_query($conn, "SELECT SUM(fine) as total FROM discipline_issues");
$total_data = mysqli_fetch_assoc($total_q);
$total_fine = $total_data['total'] ?? 0;

// Logic to calculate ONLY pending fines
$pending_q = mysqli_query($conn, "SELECT SUM(fine) as p_total FROM discipline_issues WHERE fine_status != 'Submitted'");
$pending_data = mysqli_fetch_assoc($pending_q);
$pending_fine = $pending_data['p_total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Archive | Neural Portal</title>
    
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.4s ease; overflow-x: hidden; }
        
        /* Neural Background Effects */
        .neural-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.15) 1px, transparent 0); background-size: 32px 32px; }
        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; right: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; left: -5%; }
        
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-100px, -50px) scale(1.1); } }
        
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(20px) saturate(180%); border: 1px solid var(--border); }
        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* Pro Stats Styling */
        .stat-card-pro {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card-pro:hover { transform: translateY(-5px) scale(1.02); }
        .snow-particle {
            position: absolute;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.3;
            animation: fall linear infinite;
        }
        @keyframes fall {
            to { transform: translateY(150px); }
        }
    </style>
</head>
<body class="antialiased">

<div class="neural-bg"></div>
<div class="orb-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<aside class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem]">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg">
            <i data-lucide="database" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">ARCHIVE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">History Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="discipline_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="users" class="w-5 h-5"></i> Student List
        </a>
        <a href="pending_fines.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="credit-card" class="w-5 h-5"></i> Pending Fines
        </a>
        <a href="issue_students_list.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 text-primary border border-primary/20 transition-all font-bold">
            <i data-lucide="history" class="w-5 h-5"></i> Issue History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme
        </button>
        <a href="discipline_logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Incident <span class="text-gradient">Logs</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Central Discipline Archive System</p>
        </div>
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">A</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase">Administrator</p>
                <span class="text-sm font-black italic uppercase">Access Level 01</span>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="stat-card-pro glass-panel p-8 rounded-[2rem] border-primary/20 overflow-hidden group">
            <div class="snow-box" id="snow1"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-primary/20 rounded-xl"><i data-lucide="trending-up" class="text-primary w-6 h-6"></i></div>
                    <span class="text-[10px] font-bold text-primary bg-primary/10 px-3 py-1 rounded-full uppercase">Total Generated</span>
                </div>
                <h3 class="text-4xl font-black tracking-tighter">₹<?= number_format($total_fine) ?></h3>
                <p class="text-xs opacity-50 mt-2 font-bold uppercase tracking-wider italic">Historical Fine Revenue</p>
            </div>
        </div>

        <div class="stat-card-pro glass-panel p-8 rounded-[2rem] border-red-500/20 group">
            <div class="snow-box" id="snow2"></div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-red-500/20 rounded-xl"><i data-lucide="alert-circle" class="text-red-500 w-6 h-6"></i></div>
                    <span class="text-[10px] font-bold text-red-500 bg-red-500/10 px-3 py-1 rounded-full uppercase">Pending Recovery</span>
                </div>
                <h3 class="text-4xl font-black tracking-tighter text-red-500">₹<?= number_format($pending_fine) ?></h3>
                <p class="text-xs opacity-50 mt-2 font-bold uppercase tracking-wider italic">Outstanding Balance</p>
            </div>
        </div>
    </div>

    <div class="glass-panel rounded-[3rem] p-6 lg:p-10 border-primary/10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">
                        <th class="px-6 pb-2">Timestamp</th>
                        <th class="px-6 pb-2">Student Entity</th>
                        <th class="px-6 pb-2">Violation</th>
                        <th class="px-6 pb-2">Fine</th>
                        <th class="px-6 pb-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT di.*, s.name 
                              FROM discipline_issues di 
                              INNER JOIN students s ON di.reg_no = s.reg_no 
                              ORDER BY di.id DESC";
                    $q = mysqli_query($conn, $query);
                    
                    while($r = mysqli_fetch_assoc($q)):
                        $statusColor = ($r['fine_status'] == 'Submitted') ? 'text-green-500 bg-green-500/10 border-green-500/20' : 'text-red-500 bg-red-500/10 border-red-500/20';
                        $timestamp = strtotime($r['issue_date']);
                    ?>
                    <tr class="glass-panel hover:bg-white/5 transition-all group">
                        <td class="px-6 py-5 first:rounded-l-2xl border-y border-l border-white/5">
                            <div class="font-mono font-bold text-sm"><?= date('d M Y', $timestamp) ?></div>
                            <div class="text-[10px] opacity-40 font-black uppercase tracking-tighter"><?= date('l', $timestamp) ?></div>
                        </td>
                        
                        <td class="px-6 py-5 border-y border-white/5">
                            <div class="font-extrabold text-sm uppercase tracking-tight text-gradient"><?= htmlspecialchars($r['name']) ?></div>
                            <div class="font-mono text-[10px] opacity-60 mt-1"><?= htmlspecialchars($r['reg_no']) ?></div>
                        </td>

                        <td class="px-6 py-5 border-y border-white/5">
                            <span class="text-xs font-bold uppercase opacity-80"><?= htmlspecialchars($r['issue_type']) ?></span>
                        </td>

                        <td class="px-6 py-5 border-y border-white/5">
                            <span class="font-black text-primary">₹<?= number_format($r['fine']) ?></span>
                        </td>

                        <td class="px-6 py-5 last:rounded-r-2xl border-y border-r border-white/5 text-center">
                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest border <?= $statusColor ?>">
                                <?= $r['fine_status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">
            &copy; <?= date('Y') ?> DISCIPLINE NODE • SYSTEM ARCHIVE
        </p>
    </footer>
</main>

<script>
    lucide.createIcons();

    // Snowfall Effect for Pro Stats
    function createSnow(containerId) {
        const container = document.getElementById(containerId);
        for (let i = 0; i < 15; i++) {
            const snow = document.createElement('div');
            snow.className = 'snow-particle';
            const size = Math.random() * 4 + 2 + 'px';
            snow.style.width = size;
            snow.style.height = size;
            snow.style.left = Math.random() * 100 + '%';
            snow.style.top = '-20px';
            snow.style.animationDuration = Math.random() * 3 + 2 + 's';
            snow.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(snow);
        }
    }
    createSnow('snow1');
    createSnow('snow2');

    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    }

    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    }
</script>
</body>
</html>