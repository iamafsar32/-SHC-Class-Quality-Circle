<?php
session_start();
include 'db.php';

// Authentication check (Preserving your original logic)
if(!isset($_SESSION['report_user'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Dashboard | Neural Portal</title>
    
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
        
        .neural-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.15) 1px, transparent 0); background-size: 32px 32px; }
        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; right: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; left: -5%; }
        
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-100px, -50px) scale(1.1); } }
        
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(20px) saturate(180%); border: 1px solid var(--border); }
        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .nav-link.active {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
            border: 1px solid rgba(168, 85, 247, 0.2);
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
            <i data-lucide="layout-grid" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">GREEN BOARD</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Report Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="nav-link active flex items-center gap-3 px-5 py-4 rounded-2xl transition-all font-bold">
            <i data-lucide="home" class="w-5 h-5"></i> Dashboard
        </a>
        <a href="generate_report.php?type=Workshop" class="nav-link flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="file-text" class="w-5 h-5"></i> Workshop Report
        </a>
        <a href="generate_report.php?type=Symposium" class="nav-link flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="layers" class="w-5 h-5"></i> Symposium Report
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Welcome <span class="text-gradient">Admin</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Management Portal Active</p>
        </div>
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black text-xl shadow-lg">A</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase">System Admin</p>
                <span class="text-sm font-black italic uppercase">Root Access</span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-10 lg:p-20 text-center border-primary/10 min-h-[50vh] flex flex-col items-center justify-center">
        <div class="w-24 h-24 action-gradient rounded-[2rem] flex items-center justify-center text-white mb-8 shadow-2xl shadow-primary/40 rotate-3">
            <i data-lucide="shield-check" class="w-12 h-12"></i>
        </div>
        
        <h2 class="text-3xl font-black uppercase tracking-tighter mb-4">Management portal is ready</h2>
        
        <p class="max-w-md opacity-60 font-medium text-lg mb-10">
            Please select a <span class="text-primary font-bold">report category</span> from the navigation sidebar to generate or view archived documents.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="generate_report.php?type=Workshop" class="px-8 py-4 glass-panel border-primary/20 rounded-2xl hover:bg-primary/10 transition-all font-bold uppercase text-xs tracking-widest flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Workshop
            </a>
            <a href="generate_report.php?type=Symposium" class="px-8 py-4 glass-panel border-secondary/20 rounded-2xl hover:bg-secondary/10 transition-all font-bold uppercase text-xs tracking-widest flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Symposium
            </a>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">
            &copy; <?= date('Y') ?> GREEN BOARD NODE • ARCHIVE SYSTEM
        </p>
    </footer>
</main>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    }

    // Preserve theme on load
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    }
</script>
</body>
</html>