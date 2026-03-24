<?php 
include 'auth.php'; 
include 'db.php'; 
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Nexus | SmartClass</title>
    
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
        
        .text-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .status-table th { 
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.65rem;
            font-weight: 800;
            opacity: 0.5;
            padding: 1.5rem 1rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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
            <i data-lucide="lock" class="w-5 h-5"></i>
            Manage Logins
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

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Access <span class="text-gradient">Security</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Credential Management Portal</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="user-cog" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Security Officer</p>
                <span class="text-sm font-black italic uppercase"><?php echo date('l, d M'); ?></span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 overflow-hidden">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div class="flex items-center gap-6">
                <div class="w-2 h-12 bg-primary rounded-full"></div>
                <div>
                    <h3 class="text-2xl font-black tracking-tight uppercase">Module Credentials</h3>
                    <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest mt-1">9 ACTIVE AUTHENTICATION NODES</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full status-table text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-primary">
                        <th width="35%">System Module</th>
                        <th width="30%">Assigned Username</th>
                        <th width="35%" class="text-right">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tables=[
                        "attendance_login"=>"Attendance System",
                        "academic_login"=>"Academic Records",
                        "leave_login"=>"Leave Management",
                        "green_login"=>"Green Board",
                        "notice_login"=>"Notice Board",
                        "discipline_login"=>"Discipline Module",
                        "course_login"=>"Course Content",
                        "month_login"=>"Monthly Reports",
                        "report_login"=>"Final Reports"
                    ];

                    foreach($tables as $table => $name){
                        $query = mysqli_query($conn, "SELECT username FROM $table LIMIT 1");
                        $username = '<span class="px-3 py-1 rounded-lg bg-red-500/10 text-red-500 text-[10px] font-black uppercase italic tracking-tighter">Null Access</span>';
                        
                        if($query && mysqli_num_rows($query) > 0) {
                            $r = mysqli_fetch_assoc($query);
                            if(!empty($r['username'])){
                                $username = '<span class="font-mono font-bold text-primary">'.$r['username'].'</span>';
                            }
                        }
                        
                        echo "<tr class='glass-panel hover:bg-white/5 transition-all group overflow-hidden'>
                            <td class='p-6 rounded-l-3xl'>
                                <div class='flex items-center gap-4'>
                                    <div class='w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:action-gradient group-hover:text-white transition-all'>
                                        <i data-lucide='cpu' class='w-4 h-4'></i>
                                    </div>
                                    <span class='font-black text-sm uppercase tracking-wide'>$name</span>
                                </div>
                            </td>
                            <td class='p-6'>$username</td>
                            <td class='p-6 rounded-r-3xl text-right'>
                                <div class='flex gap-3 justify-end'>
                                    <a href='edit_login.php?table=$table' class='btn-action bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white'>
                                        <i data-lucide='edit-3' class='w-3.5 h-3.5'></i> Edit
                                    </a>
                                    <a href='delete_login.php?table=$table' 
                                       onclick='return confirm(\"RESET ALERT: Terminate credentials for $name?\")'
                                       class='btn-action bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white'>
                                        <i data-lucide='refresh-cw' class='w-3.5 h-3.5'></i> Reset
                                    </a>
                                </div>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> SECURE NODE • SACRED HEART COLLEGE • NEURAL UI</p>
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

    // Persist theme choice
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>
</body>
</html>