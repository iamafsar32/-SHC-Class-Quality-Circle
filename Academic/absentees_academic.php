<?php
// --- Core PHP Logic (UNMODIFIED) ---
include 'db.php';

$date = $_GET['date'] ?? date('Y-m-d');
$session = $_GET['session'] ?? 'Morning';
$program = $_GET['program_name'] ?? 'N/A';

// WARNING: This query is vulnerable to SQL Injection. Use prepared statements in production.
$q = mysqli_query($conn,
    "SELECT s.reg_no,s.name,a.day_order,a.session,a.program_name
    FROM academic_attendance a
    JOIN students s ON a.reg_no=s.reg_no
    WHERE a.status='Absent'
    AND a.date='$date'
    AND a.session='$session'
    AND a.program_name='$program'"
);
// -----------------------------------
?>
<!DOCTYPE html>
<html lang="en" class="dark" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absentees Archive | AMS CORE</title>
    
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
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; right: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; left: -5%; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-100px, -50px) scale(1.1); }
        }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid var(--border);
        }

        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        
        .text-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media print {
            .no-print, aside, .orb-container, .neural-bg { display: none !important; }
            body { background: white !important; color: black !important; }
            .glass-panel { background: transparent !important; border: 1px solid #eee !important; color: black !important; backdrop-filter: none !important; }
            .text-gradient { -webkit-text-fill-color: black !important; background: none !important; }
            main { margin-left: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="antialiased">

<div class="neural-bg"></div>
<div class="orb-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<aside class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem] no-print">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg">
            <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">AMS CORE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Report Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="academic_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary">
            <i data-lucide="user-x" class="w-5 h-5"></i>
            Absentees List
        </a>
        <button onclick="window.print()" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="printer" class="w-5 h-5"></i>
            Print Report
        </button>
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
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Absentees <span class="text-gradient">Archive</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Historical Attendance Data Retrieval</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20 no-print">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">System Role</p>
                <span class="text-sm font-black italic">Academic Admin</span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
            <div class="flex items-center gap-6">
                <div class="w-2 h-16 action-gradient rounded-full hidden sm:block"></div>
                <div>
                    <h3 class="text-3xl font-black tracking-tight text-gradient uppercase"><?= htmlspecialchars($program) ?></h3>
                    <div class="flex flex-wrap gap-4 mt-2">
                        <span class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest opacity-60">
                            <i data-lucide="calendar" class="w-3 h-3 text-primary"></i> <?= htmlspecialchars($date) ?>
                        </span>
                        <span class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest opacity-60">
                            <i data-lucide="clock" class="w-3 h-3 text-secondary"></i> <?= htmlspecialchars($session) ?>
                        </span>
                    </div>
                </div>
            </div>

            <button onclick="window.print()" class="no-print flex items-center justify-center gap-3 px-8 py-4 bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary/10 transition-all">
                <i data-lucide="download-cloud" class="w-4 h-4"></i>
                Export PDF
            </button>
        </div>

        <?php if(mysqli_num_rows($q) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-3">
                    <thead>
                        <tr class="opacity-30 text-[10px] font-black uppercase tracking-[0.5em]">
                            <th class="px-6 pb-2 text-left">Reg No</th>
                            <th class="px-6 pb-2 text-left">Student Name</th>
                            <th class="px-6 pb-2 text-left">Day Order</th>
                            <th class="px-6 pb-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($r = mysqli_fetch_assoc($q)): ?>
                        <tr class="group hover:translate-x-1 transition-transform duration-300">
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 rounded-l-2xl font-mono font-bold text-primary italic underline decoration-primary/20">
                                <?= htmlspecialchars($r['reg_no']) ?>
                            </td>
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 font-bold text-sm tracking-tight">
                                <?= htmlspecialchars($r['name']) ?>
                            </td>
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 font-mono text-xs opacity-50">
                                DAY-0<?= htmlspecialchars($r['day_order']) ?>
                            </td>
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 rounded-r-2xl text-center">
                                <span class="px-4 py-1.5 rounded-lg bg-red-500/10 text-red-500 text-[10px] font-black uppercase border border-red-500/20 shadow-lg shadow-red-500/10">
                                    Absent
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-24 bg-black/5 dark:bg-white/5 rounded-[3rem] border border-dashed border-black/10 dark:border-white/10">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="user-check" class="w-10 h-10 text-primary"></i>
                </div>
                <h4 class="text-xl font-bold opacity-80">Full Attendance Achieved</h4>
                <p class="text-xs font-medium opacity-40 mt-2 uppercase tracking-widest">No absentee records found for this deployment</p>
            </div>
        <?php endif; ?>

        <div class="mt-12 no-print">
            <a href="academic_dashboard.php" class="block w-full py-5 text-center bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 text-inherit font-black rounded-2xl hover:bg-primary/10 hover:border-primary/30 transition-all uppercase tracking-[0.4em] text-[10px]">
                Return to Command Dashboard
            </a>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center no-print">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> SmartClass AMS Node • Secure Report Generation</p>
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