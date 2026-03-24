<?php
session_start();
include 'db.php';

// Authentication check - Preserving original logic
if(!isset($_SESSION['report_user'])){
    header("Location: login.php");
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'General';
$selected_year = isset($_POST['year_filter']) ? $_POST['year_filter'] : '';
$students = [];

// Fetch students logic - Preserving original logic
if (isset($_POST['fetch_students']) && !empty($selected_year)) {
    $year = mysqli_real_escape_string($conn, $_POST['year_filter']);
    $query = "SELECT reg_no, name FROM students WHERE year = '$year' ORDER BY reg_no ASC";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $type; ?> Entry | Neural Portal</title>
    
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

        .form-input { 
            background: rgba(255,255,255,0.05) !important; 
            border: 1px solid var(--border) !important;
            color: var(--text-main) !important;
        }

        .select-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(168, 85, 247, 0.4);
        }
        .btn-selected {
            background: linear-gradient(135deg, #22c55e 0%, #10b981 100%) !important;
            border-color: transparent !important;
            color: white !important;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
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
            <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">REPORTS</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Node Terminal</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
        </a>
        <a href="generate_report.php?type=Workshop" class="flex items-center gap-3 px-5 py-4 rounded-2xl <?php echo ($type=='Workshop')?'bg-primary/10 text-primary border border-primary/20 font-bold':'opacity-70 font-semibold hover:bg-white/5'; ?>">
            <i data-lucide="briefcase" class="w-5 h-5"></i> Workshop
        </a>
        <a href="generate_report.php?type=Symposium" class="flex items-center gap-3 px-5 py-4 rounded-2xl <?php echo ($type=='Symposium')?'bg-primary/10 text-primary border border-primary/20 font-bold':'opacity-70 font-semibold hover:bg-white/5'; ?>">
            <i data-lucide="award" class="w-5 h-5"></i> Symposium
        </a>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden"></i>
            Theme
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-6 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
        <div>
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase"><?php echo $type; ?> <span class="text-gradient">Report</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Data Entry Phase</p>
        </div>
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white font-black">BC</div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase">Department</p>
                <span class="text-sm font-black italic uppercase">BCA Admin</span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[3rem] p-6 lg:p-10 border-primary/10">
        <div class="mb-12">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-primary mb-6 flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Initialize Student Sequence
            </h2>
            <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-white/5 p-6 rounded-3xl border border-white/5">
                <div class="md:col-span-3">
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2 block ml-2">Academic Year</label>
                    <select name="year_filter" required class="w-full p-4 rounded-2xl glass-panel outline-none font-bold text-sm">
                        <option value="">Select Target Year</option>
                        <option value="1" <?php if($selected_year == '1') echo 'selected'; ?>>1st Year Sequence</option>
                        <option value="2" <?php if($selected_year == '2') echo 'selected'; ?>>2nd Year Sequence</option>
                        <option value="3" <?php if($selected_year == '3') echo 'selected'; ?>>3rd Year Sequence</option>
                    </select>
                </div>
                <button type="submit" name="fetch_students" class="action-gradient text-white p-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg hover:scale-105 transition-transform">
                    Fetch Database
                </button>
            </form>
        </div>

        <form method="post" action="save_report.php">
            <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
            
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-secondary mb-6 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4"></i> Metadata Specification
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2 block ml-2">Event Designation</label>
                    <input type="text" name="event" placeholder="Event Name" required class="w-full p-4 rounded-2xl glass-panel outline-none font-bold text-sm border-white/10">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2 block ml-2">Hosting Institution</label>
                    <input type="text" name="college" placeholder="College Name" required class="w-full p-4 rounded-2xl glass-panel outline-none font-bold text-sm border-white/10">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2 block ml-2">Temporal Marker</label>
                    <input type="date" name="event_date" required class="w-full p-4 rounded-2xl glass-panel outline-none font-bold text-sm border-white/10">
                </div>
            </div>

            <div class="mb-12">
                <label class="text-[10px] font-black uppercase tracking-widest opacity-40 mb-2 block ml-2">Geographic Location / Venue</label>
                <input type="text" name="district" placeholder="District/City" required class="w-full p-4 rounded-2xl glass-panel outline-none font-bold text-sm border-white/10">
            </div>

            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-accent mb-6 flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4"></i> Participation Matrix (<?php echo count($students); ?>)
            </h2>

            <div class="overflow-x-auto mb-10">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40">
                            <th class="px-6 pb-2">#</th>
                            <th class="px-6 pb-2">Reg ID</th>
                            <th class="px-6 pb-2">Entity Name</th>
                            <th class="px-6 pb-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php $i = 1; foreach ($students as $s): ?>
                                <tr class="glass-panel hover:bg-white/5 transition-all group">
                                    <td class="px-6 py-4 first:rounded-l-2xl border-y border-l border-white/5 font-mono text-xs opacity-40">
                                        <?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                                    </td>
                                    <td class="px-6 py-4 border-y border-white/5">
                                        <span class="font-mono font-bold text-primary"><?php echo htmlspecialchars($s['reg_no']); ?></span>
                                    </td>
                                    <td class="px-6 py-4 border-y border-white/5">
                                        <div class="font-black text-xs uppercase tracking-tight"><?php echo htmlspecialchars($s['name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 last:rounded-r-2xl border-y border-r border-white/5 text-center">
                                        <input type="checkbox" name="students[]" value="<?php echo $s['reg_no'].'|'.$s['name']; ?>" id="chk_<?php echo $i; ?>" class="hidden">
                                        <button type="button" class="select-btn px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest" onclick="toggleStudent(this, 'chk_<?php echo $i; ?>')">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            <?php $i++; endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-20 text-center opacity-30 italic font-bold uppercase tracking-[0.3em]">
                                    <i data-lucide="database" class="w-12 h-12 mx-auto mb-4 opacity-20"></i>
                                    Waiting for Database Fetch
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($students)): ?>
                <button type="submit" name="generate" class="w-full action-gradient p-6 rounded-3xl text-white font-black uppercase tracking-[0.3em] shadow-2xl shadow-primary/20 hover:scale-[1.01] transition-transform flex items-center justify-center gap-4">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                    Process and Save Archive
                </button>
            <?php endif; ?>
        </form>
    </div>

    <footer class="mt-20 mb-10 text-center">
        <p class="text-[9px] font-black opacity-20 uppercase tracking-[1em]">
            &copy; <?php echo date('Y'); ?> REPORT NODE • BCA FINAL ARCHIVE
        </p>
    </footer>
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

    function toggleStudent(btn, checkId) {
        const checkbox = document.getElementById(checkId);
        checkbox.checked = !checkbox.checked;
        
        if(checkbox.checked) {
            btn.classList.add('btn-selected');
            btn.innerHTML = '<i data-lucide="check" class="w-3 h-3 inline mr-1"></i> Selected';
        } else {
            btn.classList.remove('btn-selected');
            btn.innerText = 'Select';
        }
        lucide.createIcons();
    }
</script>

</body>
</html>