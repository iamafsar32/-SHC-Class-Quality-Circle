<?php
session_start();
include 'db.php';

// Check for session and redirect if not set
if(!isset($_SESSION['notice_user'])){
    header("Location: notice_login.php");
    exit;
}

// Logic for saving records
$message_type = ""; 
$message_text = "";

if (isset($_POST['save'])) {
    $regno = strtoupper(mysqli_real_escape_string($conn, trim($_POST['regno'])));
    $notice_date = mysqli_real_escape_string($conn, $_POST['date']);
    $day_order = mysqli_real_escape_string($conn, $_POST['day_order']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $notice_content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "INSERT INTO notice (regno, notice_date, day_order, `yearr`, notice_content) 
            VALUES ('$regno', '$notice_date', '$day_order', '$year', '$notice_content')";

    if (mysqli_query($conn, $sql)) {
        $message_type = "success";
        $message_text = "Announcement deployed to neural board!";
    } else {
        $message_type = "error";
        $message_text = "Protocol Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Node | Control Panel</title>
    
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
        :root { --bg-body: #f8fafc; --glass-bg: rgba(255, 255, 255, 0.7); --border: rgba(0, 0, 0, 0.05); --text-main: #0f172a; }
        .dark { --bg-body: #020617; --glass-bg: rgba(15, 23, 42, 0.6); --border: rgba(255, 255, 255, 0.1); --text-main: #f8fafc; }

        body { background-color: var(--bg-body); color: var(--text-main); min-height: 100vh; transition: all 0.4s ease; overflow-x: hidden; }
        ::-webkit-scrollbar { display: none; }

        .neural-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.1) 1px, transparent 0); background-size: 32px 32px; }
        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; left: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; right: -5%; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(100px, 50px) scale(1.1); } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(25px) saturate(180%); border: 1px solid var(--border); }
        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        input, select, textarea { 
            background: rgba(0,0,0,0.05) !important; 
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
        }
        .dark input, .dark select, .dark textarea { background: rgba(255,255,255,0.05) !important; }
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
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
            <i data-lucide="megaphone" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">NOTICE BOARD</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Admin Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary">
            <i data-lucide="plus-circle" class="w-5 h-5"></i>
            Post Notice
        </div>
        <a href="notice_show_all.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl opacity-60 hover:opacity-100 hover:bg-black/5 dark:hover:bg-white/5 transition-all font-semibold">
            <i data-lucide="history" class="w-5 h-5"></i>
            Notice History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme Mode
        </button>
        <a href="notice_logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Broadcast <span class="text-gradient">Panel</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Active Session: <?= $_SESSION['notice_user'] ?></p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Authorized</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-black">Admin Access</span>
                </div>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl relative overflow-hidden">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-2 h-8 action-gradient rounded-full"></div>
            <h2 class="text-xs font-black text-primary uppercase tracking-[0.4em]">Initialize New Broadcast</h2>
        </div>

        <?php if($message_type): ?>
            <div class="mb-8 p-6 rounded-2xl flex items-center gap-4 <?= $message_type == 'success' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-red-500/10 text-red-500 border border-red-500/20' ?>">
                <i data-lucide="<?= $message_type == 'success' ? 'check-circle' : 'alert-circle' ?>"></i>
                <span class="font-bold text-sm uppercase tracking-wider"><?= $message_text ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Release Date</label>
                    <input type="date" name="date" value="<?= date('Y-m-d'); ?>" required 
                           class="w-full p-4 rounded-2xl outline-none focus:ring-2 ring-primary/50 transition-all font-mono">
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Day Cycle</label>
                    <select name="day_order" required class="w-full p-4 rounded-2xl outline-none focus:ring-2 ring-primary/50 transition-all font-bold">
                        <option value="">Select Order</option>
                        <?php for($i=1; $i<=6; $i++) echo "<option value='$i'>Day Order 0$i</option>"; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Academic Tier</label>
                    <select name="year" required class="w-full p-4 rounded-2xl outline-none focus:ring-2 ring-primary/50 transition-all font-bold">
                        <option value="">Select Year</option>
                        <option value="1">1st Year (Junior)</option>
                        <option value="2">2nd Year (Intermediate)</option>
                        <option value="3">3rd Year (Senior)</option>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Registration Reference</label>
                <input type="text" name="regno" placeholder="E.G. 22BCA101" oninput="this.value = this.value.toUpperCase()" required 
                       class="w-full p-4 rounded-2xl outline-none focus:ring-2 ring-primary/50 transition-all font-mono font-bold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black opacity-40 uppercase tracking-[0.2em] ml-2">Message Payload</label>
                <textarea name="content" rows="5" placeholder="Enter broadcast details..." required 
                          class="w-full p-6 rounded-[2rem] outline-none focus:ring-2 ring-primary/50 transition-all"></textarea>
            </div>

            <button type="submit" name="save" class="w-full action-gradient text-white font-black py-6 rounded-[2rem] shadow-xl shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition-all uppercase tracking-[0.3em] flex items-center justify-center gap-3">
                <i data-lucide="send" class="w-5 h-5"></i>
                Transmit Announcement
            </button>
        </form>
    </div>

    <footer class="mt-20 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> NOTICE NODE • BCA FINAL YEAR PROJECT</p>
    </footer>
</main>

<script>
    lucide.createIcons();
    
    // Theme Sync
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>
</body>
</html>