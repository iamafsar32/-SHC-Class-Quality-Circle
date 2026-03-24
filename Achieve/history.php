<?php
session_start();
include 'db.php';

// --- Auth Check (Optional) ---
// if(!isset($_SESSION['admin_user'])){
//     header("Location: login.php");
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Achievement History | Neural Portal</title>
    
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

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #a855f7; border-radius: 10px; }

        body { 
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.4s ease;
        }

        .neural-bg {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.15) 1px, transparent 0);
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
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--border);
        }

        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        
        .text-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .table-row-hover:hover {
            background: rgba(168, 85, 247, 0.04);
            transform: scale(1.002);
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
            <i data-lucide="award" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">ACHIEVE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Data History</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Entry Desk
        </a>
        <a href="history.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="layers" class="w-5 h-5"></i>
            View Records
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme Toggle
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="power" class="w-4 h-4"></i>
            Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Legacy <span class="text-gradient">Vault</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Chronological Achievement Stream</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">A</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Active Admin</p>
                <span class="text-sm font-black italic uppercase"><?php echo date('l, d M'); ?></span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-6 lg:p-10">
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-6">
                <div class="w-2 h-12 bg-primary rounded-full"></div>
                <h3 class="text-2xl font-black tracking-tight uppercase">Achievement Logs</h3>
            </div>
            <div class="hidden md:block">
                <span class="px-4 py-2 bg-primary/10 text-primary rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/20">
                    Neural Database Stream
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">
                        <th class="px-6 py-4">Register</th>
                        <th class="px-6 py-4">Student Identity</th>
                        <th class="px-6 py-4">Award Detail</th>
                        <th class="px-6 py-4">Event & Scale</th>
                        <th class="px-6 py-4">Timeline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT a.*, s.name as student_name 
                              FROM achievements a 
                              LEFT JOIN students s ON a.regno = s.reg_no 
                              ORDER BY a.event_date DESC";
                    
                    $q = mysqli_query($conn, $query);
                    
                    if(mysqli_num_rows($q) > 0) {
                        while($r = mysqli_fetch_assoc($q)){
                            $studentName = $r['student_name'] ? htmlspecialchars($r['student_name']) : "UNIDENTIFIED NODE";
                            ?>
                            <tr class="glass-panel table-row-hover transition-all group">
                                <td class="px-6 py-5 first:rounded-l-2xl border-y border-l border-white/5">
                                    <span class="font-mono font-bold text-primary text-sm"><?php echo $r['regno']; ?></span>
                                </td>
                                <td class="px-6 py-5 border-y border-white/5">
                                    <div class="flex flex-col">
                                        <span class="font-extrabold uppercase text-sm tracking-tight"><?php echo $studentName; ?></span>
                                        <span class="text-[10px] opacity-50 font-bold uppercase tracking-widest"><?php echo htmlspecialchars($r['category']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 border-y border-white/5">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-lg bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                            <i data-lucide="award" class="w-4 h-4"></i>
                                        </div>
                                        <span class="font-bold text-sm"><?php echo htmlspecialchars($r['award_name']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 border-y border-white/5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm"><?php echo htmlspecialchars($r['event_name']); ?></span>
                                        <span class="mt-1 text-[9px] font-black uppercase tracking-tighter bg-white/5 border border-white/10 px-2 py-0.5 rounded w-max text-accent">
                                            <?php echo $r['event_level']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 last:rounded-r-2xl border-y border-r border-white/5">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 font-mono font-bold text-sm">
                                            <i data-lucide="calendar" class="w-3 h-3 text-primary"></i>
                                            <?php echo date('d M, Y', strtotime($r['event_date'])); ?>
                                        </div>
                                        <span class="text-[10px] opacity-40 uppercase font-black ml-5">
                                            <?php echo date('l', strtotime($r['event_date'])); ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-20 opacity-30 font-bold uppercase tracking-widest'>No Data Packets Found in Vault</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?php echo date('Y'); ?> ACHIEVE NODE • SECURE DATA HISTORY • BCA PROJECT</p>
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

    // Preserve theme on load
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>
</body>
</html>