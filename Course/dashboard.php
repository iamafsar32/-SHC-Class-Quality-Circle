<?php
session_start();
include 'db.php';

// Logic to handle form submission
if(isset($_POST['save'])){
    $date           = mysqli_real_escape_string($conn, $_POST['date']);
    $day_order      = mysqli_real_escape_string($conn, $_POST['day_order']);
    $hour           = mysqli_real_escape_string($conn, $_POST['hour']);
    $course_title   = mysqli_real_escape_string($conn, $_POST['course_title']);
    $course_content = mysqli_real_escape_string($conn, $_POST['course_content']);

    $sql = "INSERT INTO course_plan (plan_date, day_order, hour, course_title, course_content) 
            VALUES ('$date', '$day_order', '$hour', '$course_title', '$course_content')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Log Committed to Nexus Successfully!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Entry | SmartClass Nexus</title>
    
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
        
        .input-nexus {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            padding: 1rem 1.2rem;
            outline: none;
            transition: all 0.3s;
            width: 100%;
        }

        .input-nexus:focus {
            border-color: #a855f7;
            background: rgba(168, 85, 247, 0.08);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
        }

        label {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            opacity: 0.5;
            margin-bottom: 0.5rem;
            display: block;
            margin-left: 0.5rem;
        }
    </style>
</head>
<body class="antialiased p-4 lg:p-8">

<div class="neural-bg"></div>
<div class="orb-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<aside class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem]">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/20">
            <i data-lucide="terminal" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">SMART</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Class Nexus</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
        </a>
        <a href="#" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> Session Entry
        </a>
        <a href="course_history.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="history" class="w-5 h-5"></i> Log History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme Toggle
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="power" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Session <span class="text-transparent bg-clip-text action-gradient">Logger</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Commit Academic Data to Database</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Faculty Node</p>
                <span class="text-sm font-black italic uppercase"><?php echo date('l, d M'); ?></span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 action-gradient opacity-5 blur-3xl rounded-full -mr-32 -mt-32"></div>

        <form method="post" class="space-y-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <label>Temporal Stamp (Date)</label>
                    <input type="date" name="date" class="input-nexus font-bold" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div>
                    <label>Phase Order (Day Order)</label>
                    <select name="day_order" class="input-nexus font-black uppercase cursor-pointer" required>
                        <option value="">Select Order</option>
                        <?php for($i=1; $i<=6; $i++) echo "<option value='$i'>Day Order 0$i</option>"; ?>
                    </select>
                </div>
                <div>
                    <label>Chronos Hour (Lecture)</label>
                    <select name="hour" class="input-nexus font-black uppercase cursor-pointer" required>
                        <option value="">Select Hour</option>
                        <?php for($i=1; $i<=5; $i++) echo "<option value='$i'>Hour 0$i</option>"; ?>
                    </select>
                </div>
            </div>

            <div>
                <label>Node Identity (Course Title / Code)</label>
                <div class="relative">
                    <input type="text" name="course_title" class="input-nexus font-bold pl-12" placeholder="e.g. CS6401 - Operating Systems" required>
                    <i data-lucide="book-marked" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 opacity-30"></i>
                </div>
            </div>

            <div>
                <label>Data Payload (Topics & Content Delivered)</label>
                <textarea name="course_content" class="input-nexus font-medium min-h-[180px]" placeholder="Describe topics covered, teaching aids used..." required></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" name="save" class="w-full action-gradient text-white py-5 rounded-[1.5rem] font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-purple-500/30 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                    <i data-lucide="send" class="w-4 h-4"></i> Commit Record to Nexus
                </button>
            </div>
        </form>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?php echo date('Y'); ?> SmartClass Portal • Neural Nexus UI v2.5</p>
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
    } else {
        document.documentElement.classList.add('dark');
    }
</script>

</body>
</html>