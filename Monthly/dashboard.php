<?php
include 'auth.php';
// Include db connection if needed
// include 'db.php'; 

if(!isset($_SESSION['green_user'])){
    // header("Location: green_login.php");
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Config Report | CQC Portal</title>
    
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.4s ease; min-height: 100vh; }
        
        .neural-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.1) 1px, transparent 0); background-size: 32px 32px; }
        .orb { position: fixed; border-radius: 50%; opacity: 0.15; filter: blur(100px); z-index: -3; }
        
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(25px); border: 1px solid var(--border); }
        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        input, select {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
        }
        .dark input, .dark select {
            background: rgba(0,0,0,0.2) !important;
        }
    </style>
</head>
<body class="antialiased">

<div class="neural-bg"></div>
<div class="orb w-[600px] h-[600px] bg-primary top-[-10%], left-[-5%]"></div>
<div class="orb w-[500px] h-[500px] bg-secondary bottom-[-10%] right-[-5%]"></div>

<aside id="sidebar" class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem] transition-all duration-300">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
            <i data-lucide="layers" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">CQC NODE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Management System</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl opacity-60 hover:opacity-100 hover:bg-primary/10 transition-all font-semibold">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary">
            <i data-lucide="file-plus" class="w-5 h-5"></i> Generate Report
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i> Toggle Theme
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Report <span class="text-gradient">Config</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Set parameters for monthly analytics</p>
        </div>
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white"><i data-lucide="settings"></i></div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Active Dept</p>
                <span class="text-sm font-black text-primary uppercase">BCA Department</span>
            </div>
        </div>
    </header>

    <section class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl overflow-hidden relative">
        <div class="absolute top-0 right-0 p-12 opacity-5 pointer-events-none">
            <i data-lucide="file-text" class="w-64 h-64"></i>
        </div>

        <form method="post" action="generate_report.php" class="relative z-10">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-1.5 h-8 action-gradient rounded-full"></div>
                <h2 class="text-2xl font-black uppercase tracking-tight">Report Parameters</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 ml-1">Select Reporting Month</label>
                    <div class="relative">
                        <select name="month" required class="w-full p-4 rounded-2xl outline-none focus:ring-2 ring-primary/50 appearance-none font-semibold">
                            <?php
                            for($m=1;$m<=12;$m++){
                                $monthName = date("F", mktime(0,0,0,$m,10));
                                $selected = ($m == date('n')) ? 'selected' : '';
                                echo "<option value='$m' $selected>$monthName</option>";
                            }
                            ?>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-4 opacity-40 w-5 h-5"></i>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 ml-1">Select Academic Year</label>
                    <div class="relative">
                        <select name="year" class="w-full p-4 rounded-2xl outline-none focus:ring-2 ring-primary/50 appearance-none font-semibold">
                            <?php for($y=date('Y')-1; $y<=2030; $y++) {
                                $selected = ($y == date('Y')) ? 'selected' : '';
                                echo "<option $selected>$y</option>";
                            } ?>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-4 opacity-40 w-5 h-5"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 pt-8 border-t border-primary/10">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 ml-1">Class Teacher</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-4 opacity-40 w-5 h-5"></i>
                        <input type="text" name="class_teacher" placeholder="Full Name" required class="w-full p-4 pl-12 rounded-2xl outline-none focus:ring-2 ring-primary/50 font-medium">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 ml-1">Head of Department</label>
                    <div class="relative">
                        <i data-lucide="award" class="absolute left-4 top-4 opacity-40 w-5 h-5"></i>
                        <input type="text" name="hod_name" placeholder="HOD Name" required class="w-full p-4 pl-12 rounded-2xl outline-none focus:ring-2 ring-primary/50 font-medium">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-50 ml-1">Report Prepared By</label>
                    <div class="relative">
                        <i data-lucide="edit-3" class="absolute left-4 top-4 opacity-40 w-5 h-5"></i>
                        <input type="text" name="prepared_by" placeholder="Staff / Student Name" required class="w-full p-4 pl-12 rounded-2xl outline-none focus:ring-2 ring-primary/50 font-medium">
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="action-gradient w-full py-6 rounded-2xl text-white font-black uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-4">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                    Compile & Generate Report
                </button>
            </div>
        </form>
    </section>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?php echo date('Y'); ?> CQC Node • Smart Reporting System</p>
    </footer>
</main>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Theme Management
    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    }

    // Load saved theme
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>

</body>
</html>