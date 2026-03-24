<?php
session_start();
include 'db.php';

// Check for session and redirect if not set
if(!isset($_SESSION['green_user'])){
    header("Location: green_login.php");
    exit;
}

$success = null;
$error = null;

// Logic for saving records
if(isset($_POST['save'])){
    $date    = mysqli_real_escape_string($conn, $_POST['date']);
    $day     = mysqli_real_escape_string($conn, $_POST['day_order']);
    $year    = mysqli_real_escape_string($conn, $_POST['year']);
    $regno   = strtoupper(mysqli_real_escape_string($conn, trim($_POST['regno']))); 
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $checkStudent = mysqli_query($conn, "SELECT * FROM students WHERE reg_no = '$regno' AND year = '$year'");

    if(mysqli_num_rows($checkStudent) > 0) {
        $sql = "INSERT INTO green (board_date, day_order, yearr, regno, content) 
                VALUES ('$date', '$day', '$year', '$regno', '$content')";
        
        if(mysqli_query($conn, $sql)){
            $success = "Data transmitted and archived successfully!";
        } else {
            $error = "System Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Identity Mismatch: Student '$regno' not found in Year $year.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Board | AMS CORE</title>
    
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
            --input-bg: rgba(0, 0, 0, 0.03);
        }

        .dark {
            --bg-body: #020617;
            --glass-bg: rgba(15, 23, 42, 0.6);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --input-bg: rgba(255, 255, 255, 0.03);
        }

        ::-webkit-scrollbar { display: none; }
        body { 
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.4s ease, color 0.4s ease;
            overflow-x: hidden;
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
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid var(--border);
        }

        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        input, select, textarea {
            background: var(--input-bg) !important;
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
            transition: all 0.3s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #a855f7 !important;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
            outline: none;
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
            <i data-lucide="edit-3" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">GREEN BOARD</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Maintenance Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="green_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Entry Portal
        </a>
        <a href="green_show_all.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="database" class="w-5 h-5"></i>
            Board History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Switch Theme
        </button>
        <a href="green_logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Board <span class="text-gradient">Maintenance</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Active Data Transmission Portal</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">G</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">System User</p>
                <span class="text-sm font-black italic uppercase">Administrator</span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl">
        <div class="flex items-center gap-6 mb-10">
            <div class="w-2 h-12 bg-primary rounded-full"></div>
            <h3 class="text-2xl font-black tracking-tight uppercase">Entry Details</h3>
        </div>

        <?php if($success): ?>
            <div class="mb-8 p-5 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-4 text-emerald-500">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                <span class="font-bold text-sm tracking-wide uppercase"><?= $success ?></span>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="mb-8 p-5 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center gap-4 text-red-500">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                <span class="font-bold text-sm tracking-wide uppercase"><?= $error ?></span>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Entry Date</label>
                    <input type="date" name="date" value="<?= date('Y-m-d'); ?>" required 
                           class="w-full p-4 rounded-2xl font-mono text-sm">
                </div>
                
                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Day Order</label>
                    <select name="day_order" required class="w-full p-4 rounded-2xl font-bold text-sm appearance-none">
                        <option value="">Select Day</option>
                        <?php for($i=1; $i<=6; $i++) echo "<option value='$i' class='text-black dark:text-white dark:bg-darkSurface'>Day $i</option>"; ?>
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Academic Year</label>
                    <select name="year" required class="w-full p-4 rounded-2xl font-bold text-sm appearance-none">
                        <option value="">Select Year</option>
                        <option value="1" class="text-black dark:text-white dark:bg-darkSurface">1st Year</option>
                        <option value="2" class="text-black dark:text-white dark:bg-darkSurface">2nd Year</option>
                        <option value="3" class="text-black dark:text-white dark:bg-darkSurface">3rd Year</option>
                    </select>
                </div>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Registration Number</label>
                <input type="text" name="regno" placeholder="E.G. BU231038" required 
                       oninput="this.value = this.value.toUpperCase()" 
                       class="w-full p-4 rounded-2xl font-mono font-bold text-primary tracking-widest placeholder:opacity-20">
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 ml-2">Board Content</label>
                <textarea name="content" rows="6" placeholder="Initialize content stream..." required 
                          class="w-full p-6 rounded-[2rem] text-sm font-medium leading-relaxed"></textarea>
            </div>

            <button type="submit" name="save" 
                    class="w-full action-gradient text-white font-black py-5 rounded-2xl shadow-2xl shadow-primary/20 hover:scale-[1.01] transition-all uppercase text-xs tracking-[0.3em] flex items-center justify-center gap-3">
                <i data-lucide="server" class="w-4 h-4"></i>
                Initialize Record Save
            </button>
        </form>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> AMS GREEN NODE • BCA FINAL YEAR PROJECT</p>
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
    }
</script>
</body>
</html>