<?php
session_start();
include 'db.php';

// --- Auth Check ---
if(!isset($_SESSION['discipline'])){
    header("Location: discipline_login.php");
    exit();
}

// --- FIXED LOGIC: Bulk Update Process ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_fines'])) {
    if (!empty($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
        
        $sanitized_ids = array_map('intval', $_POST['selected_ids']);
        $id_list = implode(',', $sanitized_ids);

        $update_query = "UPDATE discipline_issues 
                         SET fine_status='Submitted' 
                         WHERE id IN ($id_list) 
                         AND fine_status='Not Submitted'";
        
        if(mysqli_query($conn, $update_query)){
            header("Location: pending_fines.php?success=1");
            exit();
        }
    } else {
        header("Location: pending_fines.php?error=no_selection");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Recovery | Neural Portal</title>
    
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

        body { background-color: var(--bg-body); color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.4s ease; }
        .neural-bg { position: fixed; inset: 0; z-index: -2; background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.15) 1px, transparent 0); background-size: 32px 32px; }
        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; right: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; left: -5%; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-100px, -50px) scale(1.1); } }
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(20px) saturate(180%); border: 1px solid var(--border); }
        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .text-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .btn-collect { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(168, 85, 247, 0.4); }
        .btn-selected { background: linear-gradient(135deg, #22c55e 0%, #10b981 100%) !important; border-color: transparent !important; color: white !important; transform: scale(1.05); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }
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
            <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">FINANCE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Billing Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="discipline_dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="users" class="w-5 h-5"></i> Student List
        </a>
        <a href="pending_fines.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 text-primary border border-primary/20 transition-all font-bold">
            <i data-lucide="credit-card" class="w-5 h-5"></i> Pending Fines
        </a>
        <a href="issue_students_list.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="history" class="w-5 h-5"></i> Issue History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme
        </button>
        <a href="discipline_logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Pending <span class="text-gradient">Fines</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Active Collections required</p>
        </div>
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">AD</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase">Administrator</p>
                <span class="text-sm font-black italic uppercase">Finance Officer</span>
            </div>
        </div>
    </header>

    <form method="POST" id="mainFineForm">
        <div class="glass-panel rounded-[3rem] p-6 lg:p-10 border-primary/10">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-4">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">
                            <th class="px-6 pb-2">Record ID</th>
                            <th class="px-6 pb-2">Student Entity</th>
                            <th class="px-6 pb-2">Details</th>
                            <th class="px-6 pb-2">Amount</th>
                            <th class="px-6 pb-2 text-center">Selection</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT di.*, s.name 
                                FROM discipline_issues di 
                                JOIN students s ON di.reg_no = s.reg_no 
                                WHERE di.fine_status='Not Submitted' 
                                ORDER BY di.id DESC";
                        $q = mysqli_query($conn, $sql);
                        
                        if(mysqli_num_rows($q) > 0):
                            while($r = mysqli_fetch_assoc($q)):
                        ?>
                        <tr class="glass-panel hover:bg-white/5 transition-all group">
                            <td class="px-6 py-5 first:rounded-l-3xl border-y border-l border-white/5">
                                <span class="font-mono text-sm opacity-40">#<?= $r['id'] ?></span>
                            </td>
                            
                            <td class="px-6 py-5 border-y border-white/5">
                                <div class="font-extrabold text-sm uppercase tracking-tight text-gradient"><?= htmlspecialchars($r['name']) ?></div>
                                <div class="font-mono text-[10px] opacity-60 mt-1"><?= $r['reg_no'] ?></div>
                            </td>

                            <td class="px-6 py-5 border-y border-white/5">
                                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold uppercase block mb-1">
                                    <?= htmlspecialchars($r['issue_type']) ?>
                                </span>
                                <span class="text-[9px] opacity-40 uppercase font-bold">
                                    Date: <?= $r['issue_date'] ?> | Hr: <?= $r['Hour'] ?>
                                </span>
                            </td>

                            <td class="px-6 py-5 border-y border-white/5">
                                <span class="font-black text-primary text-lg">₹<?= number_format($r['fine']) ?></span>
                            </td>

                            <td class="px-6 py-5 last:rounded-r-3xl border-y border-r border-white/5 text-center">
                                <input type="checkbox" name="selected_ids[]" value="<?= $r['id'] ?>" id="check_<?= $r['id'] ?>" class="hidden">
                                
                                <button type="button" 
                                        onclick="toggleFine(this, <?= $r['id'] ?>)"
                                        class="btn-collect px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 mx-auto">
                                    <i data-lucide="circle" class="w-4 h-4"></i>
                                    Collect
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="py-20 text-center opacity-30 italic font-bold uppercase tracking-[0.3em]">
                                    <i data-lucide="shield-check" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                                    All Fines Cleared
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if(mysqli_num_rows($q) > 0): ?>
            <div class="mt-10">
                <button type="submit" name="update_fines" class="w-full action-gradient p-6 rounded-[2rem] text-white font-black uppercase tracking-[0.2em] shadow-2xl shadow-primary/20 hover:scale-[1.01] transition-transform flex items-center justify-center gap-4">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                    Process Selected Recoveries
                </button>
            </div>
            <?php endif; ?>
        </div>
    </form>
</main>

<script>
    lucide.createIcons();

    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    }

    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    }

    function toggleFine(btn, id) {
        const checkbox = document.getElementById('check_' + id);
        checkbox.checked = !checkbox.checked;
        
        if(checkbox.checked) {
            btn.classList.add('btn-selected');
            btn.innerHTML = '<i data-lucide="check-circle-2" class="w-4 h-4"></i> Selected';
        } else {
            btn.classList.remove('btn-selected');
            btn.innerHTML = '<i data-lucide="circle" class="w-4 h-4"></i> Collect';
        }
        lucide.createIcons();
    }
</script>
</body>
</html>