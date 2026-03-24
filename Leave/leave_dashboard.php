<?php
session_start();
include 'db.php';

if(!isset($_SESSION['leave'])){
    header("Location: leave_login.php");
    exit();
}

/* --- Core Logic: Syncing Attendance --- */
$attendance = mysqli_query($conn,"
SELECT reg_no, date
FROM attendance
WHERE status='Absent'
GROUP BY reg_no, date
HAVING COUNT(hour)=5
");

while($a=mysqli_fetch_assoc($attendance)){
    mysqli_query($conn,"
    INSERT IGNORE INTO leave_letters(reg_no, absent_date)
    VALUES('{$a['reg_no']}', '{$a['date']}')
    ");
}

$q = mysqli_query($conn,"
SELECT l.id, l.reg_no, s.name, l.absent_date, l.status
FROM leave_letters l
JOIN students s ON l.reg_no = s.reg_no
WHERE l.status='Not Submitted'
ORDER BY l.absent_date DESC
");
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Admin | AMS CORE</title>
    
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

        function toggleStatus(btn, index) {
            let hiddenStatus = btn.parentElement.querySelector("input[name='status[]']");
            let fileInput = document.getElementById('file_' + index);
            
            if(hiddenStatus.value === "Not Submitted") {
                if (fileInput.files.length === 0) {
                    alert("⚠️ Validation Error: Please select the document file before marking as Submitted.");
                    fileInput.focus();
                    return;
                }
                hiddenStatus.value = "Submitted";
                btn.value = "Submitted";
                btn.classList.remove('bg-red-500/10', 'text-red-500', 'border-red-500/20');
                btn.classList.add('bg-emerald-500/10', 'text-emerald-500', 'border-emerald-500/20');
            } else {
                hiddenStatus.value = "Not Submitted";
                btn.value = "Not Submitted";
                btn.classList.remove('bg-emerald-500/10', 'text-emerald-500', 'border-emerald-500/20');
                btn.classList.add('bg-red-500/10', 'text-red-500', 'border-red-500/20');
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

        ::-webkit-scrollbar { display: none; }
        body { 
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.4s ease;
            overflow-x: hidden;
        }

        .neural-bg {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.1) 1px, transparent 0);
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

        ::-webkit-file-upload-button {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
            border: 1px solid rgba(168, 85, 247, 0.2);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
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
            <i data-lucide="mail-warning" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">AMS LEAVE</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Management Node</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="#" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="clock" class="w-5 h-5"></i>
            Pending Letters
        </a>
        <a href="leave_total.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="history" class="w-5 h-5"></i>
            Leave History
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme Mode
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-2 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Pending <span class="text-gradient">Letters</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Document verification & Status Sync</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">L</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Active Incharge</p>
                <span class="text-sm font-black italic">Staff Portal</span>
            </div>
        </div>
    </header>

    <form method="post" action="leave_update.php" enctype="multipart/form-data">
        <div class="glass-panel rounded-[3rem] p-8 lg:p-12 shadow-2xl">
            <div class="flex items-center gap-6 mb-10">
                <div class="w-2 h-12 action-gradient rounded-full"></div>
                <h3 class="text-2xl font-black tracking-tight uppercase">Action Required</h3>
            </div>

            <?php if(mysqli_num_rows($q) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr class="opacity-30 text-[10px] font-black uppercase tracking-[0.5em]">
                                <th class="px-6 pb-2 text-left">S.No</th>
                                <th class="px-6 pb-2 text-left">Reg No</th>
                                <th class="px-6 pb-2 text-left">Student Name</th>
                                <th class="px-6 pb-2 text-left">Date Absent</th>
                                <th class="px-6 pb-2 text-left">Document</th>
                                <th class="px-6 pb-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $count = 1;
                            while($r = mysqli_fetch_assoc($q)): 
                            ?>
                            <tr class="group hover:translate-x-1 transition-transform duration-300">
                                <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 rounded-l-2xl font-mono text-xs opacity-50">
                                    <?= str_pad($count, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                
                                <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 font-mono font-bold text-primary italic underline decoration-primary/20">
                                    <?= htmlspecialchars($r['reg_no']) ?>
                                </td>
                                
                                <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 font-bold text-sm tracking-tight">
                                    <?= htmlspecialchars($r['name']) ?>
                                </td>
                                
                                <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 font-mono text-sm font-bold text-accent">
                                    <?= htmlspecialchars($r['absent_date']) ?>
                                </td>
                                
                                <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5">
                                    <input type="file" name="leave_file[]" id="file_<?= $count ?>" class="text-[10px] font-mono opacity-80">
                                </td>
                                
                                <td class="px-6 py-5 bg-black/5 dark:bg-white/5 group-hover:bg-primary/5 rounded-r-2xl text-center">
                                    <input type="hidden" name="id[]" value="<?= $r['id'] ?>">
                                    <input type="hidden" name="status[]" value="<?= $r['status'] ?>">
                                    <input type="button" 
                                           value="<?= $r['status'] ?>" 
                                           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase border cursor-pointer transition-all duration-300
                                           <?= $r['status'] == 'Not Submitted' ? 'bg-red-500/10 text-red-500 border-red-500/20 hover:bg-red-500/20' : 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20 hover:bg-emerald-500/20' ?>"
                                           onclick="toggleStatus(this, <?= $count ?>)">
                                </td>
                            </tr>
                            <?php $count++; endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-12">
                    <button type="submit" class="w-full py-6 action-gradient text-white font-black rounded-[2rem] hover:scale-[1.01] transition-all shadow-xl shadow-primary/20 uppercase tracking-[0.4em] text-xs">
                        Update Status & Synchronize Records
                    </button>
                </div>

            <?php else: ?>
                <div class="text-center py-24 bg-black/5 dark:bg-white/5 rounded-[3rem] border border-dashed border-white/10">
                    <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="check-circle" class="w-10 h-10 text-primary"></i>
                    </div>
                    <h4 class="text-xl font-bold opacity-80">All Records Clear</h4>
                    <p class="text-xs font-medium opacity-40 mt-2 uppercase tracking-widest">No pending leave letters found in the buffer</p>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">&copy; <?= date('Y') ?> AMS LEAVE NODE • BCA FINAL YEAR PROJECT</p>
    </footer>
</main>

<script>
    lucide.createIcons();

    // Preserve theme on load
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    }
</script>
</body>
</html>