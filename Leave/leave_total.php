<?php
session_start();
include 'db.php';

// Check authentication
if(!isset($_SESSION['leave'])){
    header("Location: leave_login.php");
    exit();
}

// Fetching only 'Submitted' leave letters
$q = mysqli_query($conn, "
    SELECT l.id, s.reg_no, s.name, l.absent_date, l.status, l.file_path
    FROM leave_letters l
    JOIN students s ON l.reg_no = s.reg_no
    WHERE l.status='Submitted'
    ORDER BY l.absent_date DESC
");

$count = mysqli_num_rows($q);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History | AMS CORE</title>
    
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
            overflow-x: hidden;
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
            <i data-lucide="history" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">AMS LEAVE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Management Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="leave_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="clock" class="w-5 h-5"></i>
            Pending Letters
        </a>
        <a href="#" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="history" class="w-5 h-5"></i>
            Leave History
        </a>
        <a href="#table-archive" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="file-text" class="w-5 h-5"></i>
            View Archive
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

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Leave <span class="text-gradient">History</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Verified records & archive</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black text-xl">
                <?= $count ?>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Total Filed</p>
                <span class="text-sm font-black italic">Archived Letters</span>
            </div>
        </div>
    </header>

    <div id="table-archive" class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl">
        <div class="flex items-center gap-6 mb-10">
            <div class="w-2 h-12 bg-emerald-500 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h3 class="text-2xl font-black tracking-tight uppercase">Submission Log</h3>
        </div>

        <?php if($count > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-separate border-spacing-y-3">
                    <thead>
                        <tr class="opacity-30 text-[10px] font-black uppercase tracking-[0.5em]">
                            <th class="px-6 pb-2 text-left">S.No</th>
                            <th class="px-6 pb-2 text-left">Reg No</th>
                            <th class="px-6 pb-2 text-left">Student Name</th>
                            <th class="px-6 pb-2 text-left">Date Absent</th>
                            <th class="px-6 pb-2 text-center">Document</th>
                            <th class="px-6 pb-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        while($r = mysqli_fetch_assoc($q)): 
                        ?>
                        <tr class="group hover:translate-x-1 transition-transform duration-300">
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 rounded-l-2xl font-mono text-xs opacity-50">
                                <?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?>
                            </td>
                            
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 font-mono font-bold text-primary italic">
                                <?= htmlspecialchars($r['reg_no']) ?>
                            </td>
                            
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 font-bold text-sm tracking-tight">
                                <?= htmlspecialchars($r['name']) ?>
                            </td>
                            
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 font-mono text-sm font-bold text-accent">
                                <?= htmlspecialchars($r['absent_date']) ?>
                            </td>

                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 text-center">
                                <?php if(!empty($r['file_path'])): ?>
                                    <a href="uploads/<?= htmlspecialchars($r['file_path']) ?>" target="_blank" class="w-10 h-10 action-gradient rounded-xl inline-flex items-center justify-center text-white shadow-lg hover:scale-110 transition-all">
                                        <i data-lucide="external-link" class="w-5 h-5"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="opacity-20"><i data-lucide="file-x-2" class="w-6 h-6 mx-auto"></i></span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 rounded-r-2xl text-center">
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase border border-emerald-500/20 bg-emerald-500/10 text-emerald-500 shadow-sm inline-flex items-center gap-2">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($r['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-24 bg-black/5 dark:bg-white/5 rounded-[3rem] border border-dashed border-black/10 dark:border-white/10">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="folder-open" class="w-10 h-10 opacity-20"></i>
                </div>
                <h4 class="text-xl font-bold opacity-80">History Empty</h4>
                <p class="text-xs font-medium opacity-40 mt-2 uppercase tracking-widest">No letters have been marked as submitted yet</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> AMS LEAVE NODE • BCA FINAL YEAR PROJECT</p>
    </footer>
</main>

<script>
    lucide.createIcons();

    // Preserve theme on load
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    }
</script>
</body>
</html>