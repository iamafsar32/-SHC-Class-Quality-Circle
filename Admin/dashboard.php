<?php 
include 'auth.php'; 
$conn = new mysqli("localhost", "root", "", "afsar");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct modules and their latest update status
$module_query = "SELECT program_name, MAX(date) as last_updated, COUNT(*) as record_count 
                 FROM academic_attendance 
                 GROUP BY program_name 
                 ORDER BY last_updated DESC";
$module_result = $conn->query($module_query);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Nexus | SmartClass</title>
    
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
            padding: 1rem;
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
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="home" class="w-5 h-5"></i>
            Dashboard
        </a>
        <a href="manage_logins.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="key" class="w-5 h-5"></i>
            Manage Logins
        </a>
        <a href="manage_students.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="users" class="w-5 h-5"></i>
            Students
        </a>
        <a href="module_status.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="activity" class="w-5 h-5"></i>
            Tracker
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
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">System <span class="text-gradient">Control</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Administrative Management Nexus</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">A</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Master Root</p>
                <span class="text-sm font-black italic uppercase"><?php echo date('l, d M'); ?></span>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <a href="manage_logins.php" class="glass-panel p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-6 group-hover:action-gradient group-hover:text-white transition-all">
                <i data-lucide="lock" class="w-7 h-7"></i>
            </div>
            <h3 class="text-xl font-black uppercase tracking-tight">Security</h3>
            <p class="text-xs opacity-50 font-bold uppercase mt-1">Manage Logins</p>
        </a>
        <a href="manage_students.php" class="glass-panel p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 mb-6 group-hover:action-gradient group-hover:text-white transition-all">
                <i data-lucide="graduation-cap" class="w-7 h-7"></i>
            </div>
            <h3 class="text-xl font-black uppercase tracking-tight">Academia</h3>
            <p class="text-xs opacity-50 font-bold uppercase mt-1">Student Records</p>
        </a>
        <a href="#module-tracker" class="glass-panel p-8 rounded-[2.5rem] hover:scale-[1.02] transition-all group">
            <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 mb-6 group-hover:action-gradient group-hover:text-white transition-all">
                <i data-lucide="database" class="w-7 h-7"></i>
            </div>
            <h3 class="text-xl font-black uppercase tracking-tight">Data Stream</h3>
            <p class="text-xs opacity-50 font-bold uppercase mt-1">Update Status</p>
        </a>
    </div>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12" id="module-tracker">
        <div class="flex items-center gap-6 mb-10">
            <div class="w-2 h-12 bg-primary rounded-full"></div>
            <h3 class="text-2xl font-black tracking-tight uppercase">Module Synchronizer</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full status-table text-left">
                <thead>
                    <tr class="border-b border-primary/10">
                        <th>Program Path</th>
                        <th>Last Synchronization</th>
                        <th>Payload Size</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/5">
                    <?php if ($module_result && $module_result->num_rows > 0): ?>
                        <?php while($row = $module_result->fetch_assoc()): ?>
                        <tr class="group hover:bg-primary/5 transition-colors">
                            <td class="p-5 font-black text-sm uppercase tracking-wide"><?php echo htmlspecialchars($row['program_name']); ?></td>
                            <td class="p-5 font-mono text-xs opacity-70"><?php echo date('Y.m.d // H:i', strtotime($row['last_updated'])); ?></td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-lg bg-primary/10 text-primary font-bold text-xs italic">
                                    <?php echo $row['record_count']; ?> Records
                                </span>
                            </td>
                            <td class="p-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-[10px] font-black uppercase text-emerald-500 tracking-widest">Online</span>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-20 text-center opacity-30 font-black uppercase tracking-[0.5em]">No Data Nodes Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> SmartClass Portal • Sacred Heart College • Neural UI v2.0</p>
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