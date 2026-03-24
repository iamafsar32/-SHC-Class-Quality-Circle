<?php
include 'db.php';

// Data Sanitization
$date = mysqli_real_escape_string($conn, $_GET['date'] ?? date('Y-m-d'));
$day = mysqli_real_escape_string($conn, $_GET['day'] ?? 'N/A');
$hour = mysqli_real_escape_string($conn, $_GET['hour'] ?? '1');

$q = mysqli_query($conn,
    "SELECT s.reg_no, s.name 
    FROM attendance a
    JOIN students s ON a.reg_no=s.reg_no
    WHERE a.status='Absent'
    AND a.date='$date'
    AND a.hour='$hour'"
);

$absentees = [];
if ($q) {
    while($r = mysqli_fetch_assoc($q)){
        $absentees[] = $r;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report | <?= htmlspecialchars($date) ?></title>
    
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
        
        .text-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
            .glass-panel { border: 1px solid #eee !important; backdrop-filter: none !important; background: white !important; }
            .orb-container, .neural-bg { display: none; }
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

<aside class="no-print fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem]">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
            <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">AMS CORE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Report Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="attendance_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl opacity-60 hover:opacity-100 hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Dashboard
        </a>
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary">
            <i data-lucide="users" class="w-5 h-5"></i>
            Absentees List
        </div>
        <button onclick="window.print()" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl opacity-60 hover:opacity-100 hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold">
            <i data-lucide="printer" class="w-5 h-5"></i>
            Generate PDF
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
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Absentee <span class="text-gradient">Registry</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Data Integrity Node // <?= htmlspecialchars($date) ?></p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Status</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-black">Verified Report</span>
                </div>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="glass-panel p-8 rounded-[2.5rem] relative overflow-hidden group">
            <p class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em]">Absentees Count</p>
            <h2 class="text-6xl font-black mt-2 text-gradient"><?= count($absentees) ?></h2>
            <i data-lucide="frown" class="absolute -right-4 -bottom-4 w-24 h-24 opacity-5 -rotate-12 group-hover:rotate-0 transition-transform"></i>
        </div>
        <div class="glass-panel p-8 rounded-[2.5rem]">
            <p class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em]">Chronicle Info</p>
            <h2 class="text-3xl font-black mt-2 uppercase">Day Order <?= htmlspecialchars($day) ?></h2>
            <p class="text-xs font-bold opacity-40 mt-1 italic">Period: Hour <?= htmlspecialchars($hour) ?></p>
        </div>
        <div class="glass-panel p-8 rounded-[2.5rem] flex flex-col justify-center">
             <p class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em]">Session Date</p>
             <h2 class="text-2xl font-black mt-2 font-mono"><?= date('D, d M Y', strtotime($date)) ?></h2>
        </div>
    </div>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-2 h-8 action-gradient rounded-full"></div>
            <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em]">Detailed Intelligence Registry</h2>
        </div>
        
        <?php if (count($absentees) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-4">
                    <thead>
                        <tr class="opacity-30 text-[10px] font-black uppercase tracking-[0.5em]">
                            <th class="px-8 pb-2 text-left">UID / Reg No</th>
                            <th class="px-8 pb-2 text-left">Student Entity</th>
                            <th class="px-8 pb-2 text-center">Session</th>
                            <th class="px-8 pb-2 text-right">Verification</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($absentees as $r): ?>
                        <tr class="group transition-all hover:translate-x-1">
                            <td class="px-8 py-6 bg-black/5 dark:bg-white/5 border-l-4 border-primary rounded-l-2xl">
                                <span class="font-mono font-bold text-primary italic"><?= htmlspecialchars($r['reg_no']) ?></span>
                            </td>
                            <td class="px-8 py-6 bg-black/5 dark:bg-white/5 font-bold text-sm">
                                <?= htmlspecialchars($r['name']) ?>
                            </td>
                            <td class="px-8 py-6 bg-black/5 dark:bg-white/5 text-center">
                                <span class="text-[10px] font-black opacity-40 px-3 py-1 bg-black/10 dark:bg-white/10 rounded-full">HOUR <?= htmlspecialchars($hour) ?></span>
                            </td>
                            <td class="px-8 py-6 bg-black/5 dark:bg-white/5 text-right rounded-r-2xl">
                                <span class="px-4 py-2 bg-red-500/10 text-red-500 text-[10px] font-black uppercase rounded-xl border border-red-500/20">
                                    ABSENT
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-24">
                <div class="w-24 h-24 action-gradient opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="check" class="w-12 h-12 text-white"></i>
                </div>
                <h3 class="text-3xl font-black tracking-tighter">Perfect Attendance!</h3>
                <p class="opacity-40 font-bold uppercase text-[10px] tracking-widest mt-2">Zero anomalies detected for this session</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="mt-20 text-center no-print">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> AMS Node • Neural Infrastructure</p>
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