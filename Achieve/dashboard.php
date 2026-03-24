<?php
session_start();
include 'db.php';

$success_msg = false;

if(isset($_POST['save'])){
    $regno      = mysqli_real_escape_string($conn, $_POST['regno']);
    $award      = mysqli_real_escape_string($conn, $_POST['award']);
    $category   = mysqli_real_escape_string($conn, $_POST['category']);
    $event      = mysqli_real_escape_string($conn, $_POST['event']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $level      = mysqli_real_escape_string($conn, $_POST['level']);

    $sql = "INSERT INTO achievements (regno, award_name, category, event_name, event_date, event_level) 
            VALUES ('$regno', '$award', '$category', '$event', '$event_date', '$level')";
    
    if(mysqli_query($conn, $sql)) {
        $success_msg = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Achievements | Neural Portal</title>
    
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
            --input-bg: rgba(255, 255, 255, 0.8);
        }

        .dark {
            --bg-body: #020617;
            --glass-bg: rgba(15, 23, 42, 0.6);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --input-bg: rgba(255, 255, 255, 0.03);
        }

        body { 
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.4s ease;
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

        .form-input {
            background: var(--input-bg);
            border: 1px solid var(--border);
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 4px rgba(168, 85, 247, 0.1);
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
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/20">
            <i data-lucide="trophy" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">ACHIEVE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Master Portal</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Entry Desk
        </a>
        <a href="history.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
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
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Update <span class="text-gradient">Records</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Achievement Management Stream</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">A</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Active Admin</p>
                <span class="text-sm font-black italic uppercase"><?php echo date('l, d M'); ?></span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 relative overflow-hidden">
        <?php if($success_msg): ?>
            <div class="mb-8 flex items-center gap-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 animate-pulse">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                <span class="font-bold uppercase text-xs tracking-widest">Data Stream Synchronized: Record Saved!</span>
            </div>
        <?php endif; ?>

        <div class="flex items-center gap-6 mb-10">
            <div class="w-2 h-12 bg-primary rounded-full"></div>
            <h3 class="text-2xl font-black tracking-tight uppercase">New Entry Node</h3>
        </div>

        <form method="post" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Register Number</label>
                    <input type="text" name="regno" placeholder="E.G. BU20101" required
                        class="form-input w-full px-6 py-4 rounded-2xl font-bold">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Award Title</label>
                    <input type="text" name="award" placeholder="Gold Medal / 1st Place" required
                        class="form-input w-full px-6 py-4 rounded-2xl font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Category</label>
                    <select name="category" class="form-input w-full px-6 py-4 rounded-2xl font-bold appearance-none">
                        <option value="Individual">Individual Participant</option>
                        <option value="Team">Team Representation</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Event Name</label>
                    <input type="text" name="event" placeholder="Tech Symposium 2026" required
                        class="form-input w-full px-6 py-4 rounded-2xl font-bold">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Timeline Date</label>
                    <input type="date" name="event_date" value="<?php echo date('Y-m-d'); ?>" required
                        class="form-input w-full px-6 py-4 rounded-2xl font-bold">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Event Scale</label>
                    <select name="level" class="form-input w-full px-6 py-4 rounded-2xl font-bold appearance-none">
                        <option value="College Level">College Level</option>
                        <option value="Inter College">Inter College</option>
                        <option value="University">University</option>
                        <option value="National">National</option>
                        <option value="International">International</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="save" class="w-full action-gradient text-white font-black uppercase tracking-[0.2em] py-5 rounded-2xl shadow-xl shadow-purple-500/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Transmit to Database
            </button>
        </form>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?php echo date('Y'); ?> ACHIEVE NODE • SECURE DATA ENTRY • BCA PROJECT</p>
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