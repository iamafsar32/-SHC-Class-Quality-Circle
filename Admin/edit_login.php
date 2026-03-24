<?php 
include 'auth.php'; 
include 'db.php'; 

$table = $_GET['table'];

if(isset($_POST['update'])){
    mysqli_query($conn,
      "UPDATE $table SET 
        username='{$_POST['username']}',
        password='{$_POST['password']}'"
    );
    header("Location: manage_logins.php");
    exit;
}

$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM $table"));
$module_name = ucfirst(str_replace('_login', '', $table));
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Edit | Neural Portal</title>
    
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
            <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">SMART</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Class Admin</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            Dashboard
        </a>
        <a href="manage_logins.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="key" class="w-5 h-5"></i>
            Edit Access
        </a>
        <a href="manage_students.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="users" class="w-5 h-5"></i>
            Students
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

<main class="lg:ml-80 p-2 lg:p-10 flex flex-col items-center justify-center min-h-[90vh]">
    <header class="w-full max-w-xl mb-12 text-center lg:text-left">
        <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tighter uppercase">Update <span class="text-gradient">Access</span></h1>
        <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Configuring Node: <?=$module_name?></p>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 w-full max-w-xl relative overflow-hidden">
        <div class="flex items-center gap-6 mb-10">
            <div class="w-2 h-12 bg-primary rounded-full"></div>
            <h3 class="text-2xl font-black tracking-tight uppercase">Credential Override</h3>
        </div>

        <form method="post" class="space-y-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">System Username</label>
                <div class="relative">
                    <input type="text" name="username" value="<?=htmlspecialchars($data['username'])?>" required
                        class="form-input w-full px-6 py-4 rounded-2xl font-bold pl-14">
                    <i data-lucide="user" class="w-5 h-5 absolute left-6 top-1/2 -translate-y-1/2 opacity-30"></i>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest opacity-50 ml-2">Security Password</label>
                <div class="relative">
                    <input type="text" name="password" value="<?=htmlspecialchars($data['password'])?>" required
                        class="form-input w-full px-6 py-4 rounded-2xl font-bold pl-14 font-mono">
                    <i data-lucide="fingerprint" class="w-5 h-5 absolute left-6 top-1/2 -translate-y-1/2 opacity-30"></i>
                </div>
            </div>

            <div class="pt-4 space-y-4">
                <button type="submit" name="update" class="w-full action-gradient text-white font-black uppercase tracking-[0.2em] py-5 rounded-2xl shadow-xl shadow-purple-500/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    Synchronize Credentials
                </button>
                
                <a href="manage_logins.php" class="w-full flex items-center justify-center gap-2 px-4 py-4 rounded-2xl hover:bg-white/5 transition-all text-[10px] font-black uppercase tracking-widest opacity-40 hover:opacity-100">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Return to Security Console
                </a>
            </div>
        </form>
    </div>

    <footer class="mt-20 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?php echo date('Y'); ?> SECURE AUTH NODE • BCA PROJECT</p>
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