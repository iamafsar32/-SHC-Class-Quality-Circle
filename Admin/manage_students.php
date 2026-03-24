<?php 
include 'auth.php'; 
include 'db.php'; 

// --- 1. FILTER LOGIC ---
$filter_year = isset($_GET['view_year']) ? mysqli_real_escape_string($conn, $_GET['view_year']) : '1';

// --- 2. ADD LOGIC ---
if(isset($_POST['add'])){
    $reg = mysqli_real_escape_string($conn, $_POST['reg']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']); 
    $email = mysqli_real_escape_string($conn, $_POST['email']);   
    
    $query = "INSERT INTO students (reg_no, name, year, mobile, email) VALUES ('$reg', '$name', '$year', '$mobile', '$email')";
    if(mysqli_query($conn, $query)){
        header("Location: manage_students.php?view_year=" . $year);
        exit();
    }
}

// --- 3. DELETE LOGIC ---
if(isset($_GET['delete_reg'])){
    $reg_to_delete = mysqli_real_escape_string($conn, $_GET['delete_reg']);
    mysqli_query($conn, "DELETE FROM students WHERE reg_no = '$reg_to_delete'");
    header("Location: manage_students.php?view_year=" . $filter_year);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Nexus | SmartClass</title>
    
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

        .input-nexus {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 0.8rem 1.2rem;
            outline: none;
            transition: all 0.3s;
        }

        .input-nexus:focus {
            border-color: #a855f7;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.2);
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
        <a href="manage_logins.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="lock" class="w-5 h-5"></i>
            Manage Logins
        </a>
        <a href="manage_students.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
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
            <h1 class="text-5xl font-extrabold tracking-tighter uppercase">Student <span class="text-gradient">Registry</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Academic Database Management</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="database" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Admin Node</p>
                <span class="text-sm font-black italic uppercase"><?php echo date('l, d M'); ?></span>
            </div>
        </div>
    </header>

    <div class="glass-panel rounded-[2.5rem] p-8 mb-12">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
            </div>
            <h2 class="text-xl font-black uppercase tracking-tight">Register New Ingress</h2>
        </div>
        
        <form method="post" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <input name="reg" placeholder="Reg No" class="input-nexus text-sm font-bold" required>
            <input name="name" placeholder="Full Name" class="input-nexus text-sm font-bold md:col-span-2" required>
            <input name="mobile" placeholder="Mobile" class="input-nexus text-sm font-bold" required>
            <input name="email" type="email" placeholder="Email" class="input-nexus text-sm font-bold" required>
            <select name="year" class="input-nexus text-sm font-black uppercase cursor-pointer">
                <option value="1">Year 01</option>
                <option value="2">Year 02</option>
                <option value="3">Year 03</option>
            </select>
            <button name="add" class="lg:col-span-6 action-gradient text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-lg shadow-purple-500/30 hover:scale-[1.01] transition-all active:scale-95">
                Commit Student to Database
            </button>
        </form>
    </div>

    <div class="flex items-center gap-3 mb-8 glass-panel p-2 rounded-2xl w-fit">
        <?php for($i=1; $i<=3; $i++): ?>
            <a href="?view_year=<?= $i ?>" class="px-8 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all <?= $filter_year == $i ? 'action-gradient text-white shadow-lg' : 'opacity-40 hover:opacity-100' ?>">
               PHASE <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <div class="glass-panel rounded-[3rem] p-8 lg:p-12 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full status-table text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-primary">
                        <th>Reg ID</th>
                        <th>Student Identity</th>
                        <th>Comm Channels</th>
                        <th class="text-right">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($conn, "SELECT * FROM students WHERE year = '$filter_year' ORDER BY reg_no ASC");
                    if(mysqli_num_rows($q) > 0){
                        while($s = mysqli_fetch_assoc($q)){
                            echo "<tr class='glass-panel hover:bg-white/5 transition-all group'>
                                <td class='p-6 rounded-l-3xl'>
                                    <span class='font-mono font-bold text-primary tracking-tighter'>{$s['reg_no']}</span>
                                </td>
                                <td class='p-6'>
                                    <div class='font-black text-sm uppercase tracking-wide'>{$s['name']}</div>
                                    <div class='text-[10px] opacity-40 font-bold uppercase'>Academic Year 0$filter_year</div>
                                </td>
                                <td class='p-6'>
                                    <div class='flex flex-col gap-1'>
                                        <span class='text-xs font-bold'><i data-lucide='phone' class='w-3 h-3 inline mr-2 opacity-50'></i>{$s['mobile']}</span>
                                        <span class='text-[10px] opacity-60 font-medium tracking-tight underline'>{$s['email']}</span>
                                    </div>
                                </td>
                                <td class='p-6 rounded-r-3xl text-right'>
                                    <div class='flex gap-3 justify-end'>
                                        <a href='edit_student.php?reg={$s['reg_no']}' class='btn-action bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white'>
                                            <i data-lucide='edit-3' class='w-3.5 h-3.5'></i> Edit
                                        </a>
                                        <a href='?view_year=$filter_year&delete_reg={$s['reg_no']}' 
                                           class='btn-action bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white'
                                           onclick='return confirm(\"WIPE DATA: Permanent deletion of record ID: {$s['reg_no']}?\")'>
                                            <i data-lucide='trash-2' class='w-3.5 h-3.5'></i> Purge
                                        </a>
                                    </div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='p-20 text-center opacity-30 font-black uppercase tracking-[0.5em]'>No Data Nodes Found</td></tr>";
                    }
                    ?>
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

    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>

</body>
</html>