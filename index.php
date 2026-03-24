<?php
// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "afsar";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');

// 1. Today's Absentees List (Joined with students table for names)
$absent_list_query = "SELECT DISTINCT a.reg_no, s.name
                      FROM attendance a
                      JOIN students s ON a.reg_no = s.reg_no
                      WHERE a.date = '$today' AND a.status = 'Absent'";

$absent_list_res = $conn->query($absent_list_query);
$absent_count = $absent_list_res->num_rows;

// 2. Pending Leave Letters List
$leave_list_query = "SELECT l.reg_no, s.name 
                     FROM leave_letters l 
                     JOIN students s ON l.reg_no = s.reg_no 
                     WHERE l.status = 'Not Submitted'";
$leave_list_res = $conn->query($leave_list_query);
$leave_count = $leave_list_res->num_rows;

// 3. Pending Fines (Fixed column names from your SQL schema)
$fine_query = "SELECT SUM(fine) as total FROM discipline_issues WHERE fine_status = 'Not Submitted'";
$fine_res = $conn->query($fine_query);
$fine_total = $fine_res->fetch_assoc()['total'] ?? 0;

// 4. Notice Board Content
$notice_query = "SELECT notice_content FROM notice ORDER BY id ASC LIMIT 1";
$notice_res = $conn->query($notice_query);
$latest_notice = $notice_res->fetch_assoc()['notice_content'] ?? "No active notices";
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartClass.OS | Quantum Interface</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: "var(--bg-color)",
                        accent: "var(--text-color)",
                        deep: "#0a0a0c",
                        hologram: "#4f46e5"
                    },
                    fontFamily: { 
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    },
                    animation: {
                        'spin-slow': 'spin 12s linear infinite',
                        'reverse-spin': 'reverse-spin 15s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        'reverse-spin': {
                            from: { transform: 'rotate(360deg)' },
                            to: { transform: 'rotate(0deg)' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(2deg)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        :root { 
            --bg-color: #000000;
            --text-color: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.08);
            --sub-text: rgba(255, 255, 255, 0.4);
            --hologram-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
        }

        .light {
            --bg-color: #f8fafc;
            --text-color: #0f172a;
            --card-bg: rgba(0, 0, 0, 0.03);
            --card-border: rgba(0, 0, 0, 0.08);
            --sub-text: rgba(15, 23, 42, 0.6);
        }
        
        body { 
            background: var(--bg-color); 
            color: var(--text-color); 
            overflow-x: hidden;
            font-family: 'Outfit', sans-serif;
            transition: background 0.5s cubic-bezier(0.4, 0, 0.2, 1), color 0.5s ease;
        }

        .liquid-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: 
                radial-gradient(circle at 20% 30%, rgba(79, 70, 229, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(236, 72, 153, 0.1) 0%, transparent 40%);
            filter: blur(80px);
        }

        .aurora {
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: conic-gradient(from 180deg at 50% 50%, var(--bg-color) 0deg, #4f46e5 100deg, var(--bg-color) 180deg, #ec4899 260deg, var(--bg-color) 360deg);
            opacity: 0.15;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        .hologram-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }

        .hologram-card:hover {
            background: rgba(128, 128, 128, 0.1);
            border-color: rgba(128, 128, 128, 0.2);
            transform: translateY(-5px);
        }

        .side-dock {
            width: 80px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            position: fixed;
            left: 24px; top: 50%;
            transform: translateY(-50%);
            z-index: 100;
            display: flex; flex-direction: column; gap: 20px; padding: 20px 0; align-items: center;
        }

        .nav-icon {
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            color: var(--sub-text);
            transition: all 0.3s;
        }

        .nav-icon:hover {
            background: var(--card-border);
            color: var(--text-color);
            transform: scale(1.1);
        }

        .hero-title {
            font-size: clamp(3rem, 10vw, 8rem);
            line-height: 0.9;
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .mask-text {
            background: linear-gradient(to right, #6366f1 20%, #a855f7 50%, #ec4899 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 200% auto;
            animation: textFlow 5s linear infinite;
        }

        @keyframes textFlow { to { background-position: 200% center; } }

        .status-dot {
            width: 8px; height: 8px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 10px #22c55e;
            animation: status-pulse 2s infinite;
        }

        @keyframes status-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.5); opacity: 0.5; }
        }

        .geometric-stage {
            position: relative;
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .orb-core {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: var(--hologram-gradient);
            filter: blur(60px);
            opacity: 0.4;
            position: absolute;
            animation: pulse 4s ease-in-out infinite;
        }

        .glass-ring {
            position: absolute;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            backdrop-filter: blur(2px);
        }

        .floating-badge {
            position: absolute;
            padding: 8px 16px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 100px;
            font-family: 'JetBrains Mono';
            font-size: 10px;
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 10;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.6; }
        }
    </style>
</head>
<body class="antialiased">

    <div class="liquid-bg"></div>
    <div class="aurora"></div>

    <div class="side-dock hidden md:flex">
        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mb-4 shadow-lg shadow-indigo-500/20">
            <span class="text-white font-black">S</span>
        </div>
        <a href="#" class="nav-icon" title="Dashboard"><i data-lucide="layout-grid"></i></a>
        <a href="#modules" class="nav-icon" title="Modules"><i data-lucide="box"></i></a>
        <a href="Course/login.php" class="nav-icon" title="Analytics"><i data-lucide="activity"></i></a>
        <a href="Green/green_login.php" class="nav-icon" title="Schedule"><i data-lucide="calendar"></i></a>
        
        <button onclick="toggleTheme()" class="nav-icon mt-4" title="Toggle Theme">
            <i data-lucide="sun" class="hidden dark:block"></i>
            <i data-lucide="moon" class="block dark:hidden"></i>
        </button>

        <div class="mt-auto">
            <a href="Admin/login.php" class="nav-icon" title="Admin Access"><i data-lucide="lock"></i></a>
        </div>
    </div>

    <main class="md:ml-32 px-6 lg:px-12">
        
        <header class="py-10 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-[10px] font-bold tracking-widest uppercase text-indigo-500">System v4.0.1</span>
                <span class="status-dot"></span>
                <span class="text-[10px] font-mono opacity-40 uppercase tracking-tighter">Latent Connection: Stable</span>
            </div>
            <div class="flex items-center gap-6">
                <div class="hidden lg:flex items-center bg-gray-500/5 border border-gray-500/10 rounded-full px-4 py-2 gap-3 focus-within:border-indigo-500/50 transition-all">
                    <i data-lucide="search" class="w-4 h-4 opacity-30"></i>
                    <input type="text" placeholder="Jump to module..." class="bg-transparent border-none outline-none text-xs w-48 font-light placeholder:opacity-20">
                    <span class="text-[10px] opacity-20 font-mono">⌘K</span>
                </div>
                <span id="clock" class="font-mono text-sm opacity-50">00:00:00</span>
                <button class="bg-indigo-600 text-white px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/20">Command Line</button>
            </div>
        </header>

        <section class="min-h-[85vh] flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1" data-aos="fade-up">
                <h1 class="hero-title mb-8">
                    SMART<br><span class="mask-text">CLASS</span>
                </h1>
                <p class="opacity-50 text-xl md:text-2xl max-w-xl font-light mb-12 leading-relaxed">
                    The next evolution of institutional intelligence. A unified quantum layer for managing academic data with absolute clarity.
                </p>
                <div class="flex gap-4">
                    <button onclick="document.getElementById('modules').scrollIntoView({behavior: 'smooth'})" class="h-16 px-10 bg-indigo-600 text-white font-bold uppercase tracking-widest rounded-2xl flex items-center gap-3 group hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20">
                        Enter Workspace
                        <i data-lucide="arrow-right" class="group-hover:translate-x-2 transition-transform"></i>
                    </button>
                    <button class="h-16 px-8 hologram-card text-current font-bold uppercase tracking-widest rounded-2xl flex items-center gap-3 hover:bg-white/10 transition-all">
                        <i data-lucide="terminal" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 w-full hidden lg:block" data-aos="zoom-in" data-aos-delay="200">
                <div class="geometric-stage">
                    <div class="orb-core"></div>
                    <div class="glass-ring w-64 h-64 animate-spin-slow opacity-20 border-indigo-500"></div>
                    <div class="glass-ring w-80 h-80 animate-reverse-spin opacity-10 border-pink-500" style="border-style: dashed;"></div>
                    <div class="glass-ring w-48 h-48 animate-spin-slow opacity-30 border-white/20"></div>

                    <div class="floating-badge animate-float top-10 right-20" style="animation-delay: 0s;">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> CORE_ACTIVE
                    </div>
                    <div class="floating-badge animate-float bottom-20 left-10" style="animation-delay: -2s;">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> DATA_SYNC_04
                    </div>
                    <div class="floating-badge animate-float top-1/2 -right-4" style="animation-delay: -4s;">
                        <i data-lucide="cpu" class="w-3 h-3 text-pink-500"></i> NEURAL_LINK
                    </div>

                    <svg class="absolute inset-0 w-full h-full opacity-20" viewBox="0 0 400 400">
                        <defs>
                            <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#6366f1" />
                                <stop offset="100%" style="stop-color:#ec4899" />
                            </linearGradient>
                        </defs>
                        <path d="M50 200 L150 200 M250 200 L350 200 M200 50 L200 150 M200 250 L200 350" stroke="url(#lineGrad)" stroke-width="1" />
                        <circle cx="200" cy="200" r="140" fill="none" stroke="white" stroke-opacity="0.05" stroke-dasharray="4 8" />
                    </svg>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
            <div class="hologram-card p-6 rounded-3xl flex flex-col gap-4">
                <span class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Today Absentees</span>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold font-mono"><?php echo $absent_count; ?></span>
                    <div class="flex gap-1 h-8 items-end">
                        <div class="w-1 bg-rose-500 h-8"></div>
                        <div class="w-1 bg-gray-500/20 h-5"></div>
                        <div class="w-1 bg-gray-500/20 h-3"></div>
                    </div>
                </div>
            </div>
            <div class="hologram-card p-6 rounded-3xl flex flex-col gap-4">
                <span class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Pending Leaves</span>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold font-mono"><?php echo $leave_count; ?></span>
                    <i data-lucide="mail-warning" class="text-emerald-500/50 mb-1"></i>
                </div>
            </div>
            <div class="hologram-card p-6 rounded-3xl flex flex-col gap-4">
                <span class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Pending Fines</span>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-bold font-mono">₹<?php echo number_format($fine_total); ?></span>
                    <i data-lucide="indian-rupee" class="text-blue-500/50 mb-1"></i>
                </div>
            </div>
            <div class="hologram-card p-6 rounded-3xl flex flex-col gap-4">
                <span class="text-[10px] font-bold opacity-30 uppercase tracking-widest">Latest Notice</span>
                <div class="flex items-end justify-between overflow-hidden">
                    <span class="text-sm font-bold truncate opacity-80"><?php echo $latest_notice; ?></span>
                    <i data-lucide="megaphone" class="text-pink-500/50 mb-1 shrink-0"></i>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-24" data-aos="fade-up" data-aos-delay="100">
            <div class="hologram-card p-8 rounded-[2rem]">
                <div class="flex items-center gap-3 mb-6">
                    <i data-lucide="users-round" class="w-5 h-5 text-rose-500"></i>
                    <h3 class="text-lg font-bold uppercase tracking-tighter">Last Hour Absentees</h3>
                </div>
                <div class="max-h-48 overflow-y-auto custom-scrollbar pr-2 flex flex-col gap-3">
                    <?php if($absent_list_res->num_rows > 0): ?>
                        <?php while($row = $absent_list_res->fetch_assoc()): ?>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 hover:border-rose-500/30 transition-all">
                                <div class="flex flex-col">
                                    <span class="text-xs font-mono text-rose-400"><?php echo $row['reg_no']; ?></span>
                                    <span class="text-sm font-semibold opacity-80 uppercase"><?php echo $row['name']; ?></span>
                                </div>
                                <span class="text-[9px] px-2 py-1 rounded bg-rose-500/10 text-rose-500 font-bold uppercase">Absent</span>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="opacity-20 text-xs italic py-4">No absentees reported in the last hour sync.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hologram-card p-8 rounded-[2rem]">
                <div class="flex items-center gap-3 mb-6">
                    <i data-lucide="file-clock" class="w-5 h-5 text-emerald-500"></i>
                    <h3 class="text-lg font-bold uppercase tracking-tighter">Total Pending Leave Letters</h3>
                </div>
                <div class="max-h-48 overflow-y-auto custom-scrollbar pr-2 flex flex-col gap-3">
                    <?php if($leave_list_res->num_rows > 0): ?>
                        <?php while($row = $leave_list_res->fetch_assoc()): ?>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 hover:border-emerald-500/30 transition-all">
                                <div class="flex flex-col">
                                    <span class="text-xs font-mono text-emerald-400"><?php echo $row['reg_no']; ?></span>
                                    <span class="text-sm font-semibold opacity-80 uppercase"><?php echo $row['name']; ?></span>
                                </div>
                                <i data-lucide="external-link" class="w-4 h-4 opacity-20"></i>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="opacity-20 text-xs italic py-4">Clean slate. No pending leave applications.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="modules" class="py-12">
            <div class="flex items-center gap-6 mb-16">
                <h2 class="text-4xl font-bold italic uppercase tracking-tighter">Core Modules</h2>
                <div class="h-px flex-1 bg-gray-500/10"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 hologram-card rounded-[2.5rem] p-12 group" data-aos="fade-up">
                    <div class="flex justify-between items-start mb-20">
                        <div class="p-5 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-500">
                            <i data-lucide="user-check" class="w-10 h-10"></i>
                        </div>
                        <div class="text-right">
                            <span class="font-mono text-[10px] opacity-30 block">MODULE_UID: 001</span>
                            <span class="text-[10px] text-green-500 font-bold uppercase tracking-widest">Online</span>
                        </div>
                    </div>
                    <h3 class="text-5xl font-bold mb-6 italic uppercase tracking-tighter">Live Attendance</h3>
                    <p class="opacity-40 text-lg max-w-sm mb-10 italic">Real-time tracking of academic movement across the neural network.</p>
                    <a href="Attendace/attendance_login.php" class="inline-flex items-center gap-3 text-sm font-bold uppercase tracking-widest text-indigo-500 hover:text-current transition-colors">
                        Launch System <i data-lucide="chevron-right"></i>
                    </a>
                </div>

                <div class="hologram-card rounded-[2.5rem] p-10 border-t-pink-500/30" data-aos="fade-up" data-aos-delay="100">
                    <i data-lucide="file-spreadsheet" class="w-10 h-10 text-pink-500 mb-8"></i>
                    <h3 class="text-3xl font-bold mb-4 uppercase">Reports</h3>
                    <p class="opacity-30 text-sm mb-10">Generate complex analytic documents with a single click.</p>
                    <a href="Report/login.php" class="w-12 h-12 rounded-full border border-gray-500/10 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                        <i data-lucide="arrow-up-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:col-span-3">
                    <a href="Academic/academic_login.php" class="hologram-card rounded-[2rem] p-8 flex flex-col items-center text-center gap-4 group" data-aos="fade-up">
                        <i data-lucide="graduation-cap" class="text-indigo-400 group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Academic Logs</span>
                    </a>
                    <a href="Leave/leave_login.php" class="hologram-card rounded-[2rem] p-8 flex flex-col items-center text-center gap-4 group" data-aos="fade-up" data-aos-delay="50">
                        <i data-lucide="calendar-off" class="text-emerald-400 group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Leave Desk</span>
                    </a>
                    <a href="Notice/notice_login.php" class="hologram-card rounded-[2rem] p-8 flex flex-col items-center text-center gap-4 group" data-aos="fade-up" data-aos-delay="100">
                        <i data-lucide="bell" class="text-orange-400 group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Global Notice</span>
                    </a>
                    <a href="Achieve/login.php" class="hologram-card rounded-[2rem] p-8 flex flex-col items-center text-center gap-4 group" data-aos="fade-up" data-aos-delay="150">
                        <i data-lucide="award" class="text-yellow-400 group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Vault of Fame</span>
                    </a>
                    <a href="Descipline/discipline_login.php" class="hologram-card rounded-[2rem] p-8 flex flex-col items-center text-center gap-4 group" data-aos="fade-up" data-aos-delay="200">
                        <i data-lucide="library" class="text-blue-400 group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Descipline</span>
                    </a>
                    <a href="Monthly/login.php" class="hologram-card rounded-[2rem] p-8 flex flex-col items-center text-center gap-4 group" data-aos="fade-up" data-aos-delay="250">
                        <i data-lucide="user-cog" class="text-rose-400 group-hover:scale-110 transition-transform"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Monthly Reports</span>
                    </a>
                </div>
            </div>
        </section>

        <div id="events" class="hologram-card rounded-[2.5rem] p-12 mb-8" data-aos="fade-up">
             <div class="flex items-center justify-between mb-12">
                <h3 class="text-3xl font-bold uppercase italic tracking-tighter">Sync Timeline</h3>
                <button class="text-[10px] font-bold uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">View Calendar</button>
             </div>
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="border-l border-indigo-500/20 pl-6 relative">
                    <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-indigo-500"></div>
                    <span class="text-[10px] font-mono text-indigo-500">09:30 AM</span>
                    <h4 class="font-bold text-sm mt-2">Faculty Neural Sync</h4>
                    <p class="text-xs opacity-30 mt-1">Room 402 - Main Wing</p>
                </div>
            </div>
        </div>

        <footer class="py-20 border-t border-gray-500/10 flex flex-col md:flex-row justify-between items-center gap-10">
            <div>
                <div class="text-2xl font-black italic mb-2 tracking-tighter">SMARTCLASS.OS</div>
                <p class="text-[10px] font-bold opacity-20 uppercase tracking-[0.5em]">Sacred Heart College Proprietary Interface</p>
            </div>
            
            <div class="text-center md:text-right">
                <p class="text-[10px] opacity-30 mb-2 uppercase tracking-widest">Designed & Developed with Passion</p>
                <div class="text-xl font-bold italic tracking-tighter">AFSAR A <span class="opacity-20 ml-2">© 2026</span></div>
            </div>
        </footer>
    </main>

    <script>
        // Init Icons
        lucide.createIcons();
        AOS.init({ duration: 1000, once: true, easing: 'ease-out-quint' });

        // Theme Toggle Logic
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            lucide.createIcons(); 
        }

        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
        }

        // Clock Update
        function updateClock() {
            const now = new Date();
            const time = now.getHours().toString().padStart(2, '0') + ':' + 
                         now.getMinutes().toString().padStart(2, '0') + ':' + 
                         now.getSeconds().toString().padStart(2, '0');
            document.getElementById('clock').textContent = time;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Cursor Glow & Parallax Effect
        document.addEventListener('mousemove', (e) => {
            const aurora = document.querySelector('.aurora');
            const geometricStage = document.querySelector('.geometric-stage');
            
            const x = (e.clientX / window.innerWidth) * 100;
            const y = (e.clientY / window.innerHeight) * 100;
            
            if (aurora) {
                aurora.style.left = `${-50 + (x - 50) * 0.05}%`;
                aurora.style.top = `${-50 + (y - 50) * 0.05}%`;
            }

            if (geometricStage) {
                const moveX = (e.clientX - window.innerWidth / 2) * 0.02;
                const moveY = (e.clientY - window.innerHeight / 2) * 0.02;
                geometricStage.style.transform = `translate(${moveX}px, ${moveY}px)`;
            }
        });
        
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.querySelector('input').focus();
            }
        });
    </script>
</body>
</html>