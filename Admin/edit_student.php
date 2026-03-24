<?php 
include 'auth.php'; 
include 'db.php'; 

$reg = mysqli_real_escape_string($conn, $_GET['reg']);

// --- UPDATE LOGIC ---
if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']); 
    $email = mysqli_real_escape_string($conn, $_POST['email']);   

    mysqli_query($conn, "UPDATE students SET 
        name='$name', 
        year='$year',
        mobile='$mobile',
        email='$email' 
        WHERE reg_no='$reg'");
    
    header("Location: manage_students.php?view_year=".$year);
    exit;
}

// Fetch existing data
$res = mysqli_query($conn, "SELECT * FROM students WHERE reg_no='$reg'");
$s = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Node | SmartClass</title>
    
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .neural-bg {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.15) 1px, transparent 0);
            background-size: 32px 32px;
        }

        .orb-container { position: fixed; inset: 0; z-index: -3; filter: blur(100px); }
        .orb { position: absolute; border-radius: 50%; opacity: 0.15; animation: float 25s infinite alternate ease-in-out; }
        .orb-1 { width: 400px; height: 400px; background: #a855f7; top: 10%; right: 10%; }
        .orb-2 { width: 350px; height: 350px; background: #ec4899; bottom: 10%; left: 10%; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-50px, -30px) scale(1.1); }
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
            background: rgba(168, 85, 247, 0.05);
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
<body class="antialiased p-6">

<div class="neural-bg"></div>
<div class="orb-container">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
</div>

<button onclick="toggleTheme()" class="fixed top-8 right-8 p-4 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all z-50">
    <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
    <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
</button>

<div class="w-full max-w-xl">
    <div class="glass-panel rounded-[3rem] p-10 lg:p-14 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 action-gradient opacity-10 blur-3xl rounded-full -mr-16 -mt-16"></div>

        <header class="text-center mb-12">
            <div class="inline-flex w-20 h-20 action-gradient rounded-[2rem] items-center justify-center text-white shadow-lg shadow-purple-500/30 mb-6">
                <i data-lucide="user-cog" class="w-10 h-10"></i>
            </div>
            <h1 class="text-4xl font-black tracking-tighter uppercase mb-2">Update <span class="text-transparent bg-clip-text action-gradient">Node</span></h1>
            <p class="font-mono text-[10px] opacity-40 uppercase tracking-[0.3em]">Accessing Record: <?=$s['reg_no']?></p>
        </header>

        <form method="post" class="space-y-8">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label>Full Identity</label>
                    <input name="name" value="<?=htmlspecialchars($s['name'])?>" class="input-nexus font-bold" placeholder="e.g. Alex Mercer" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label>Comm Link (Mobile)</label>
                        <input name="mobile" value="<?=htmlspecialchars($s['mobile'])?>" class="input-nexus font-bold" required>
                    </div>
                    <div>
                        <label>Phase (Year)</label>
                        <select name="year" class="input-nexus font-black uppercase cursor-pointer">
                            <option value="1" <?=$s['year']=='1'?"selected":""?>>Phase 01</option>
                            <option value="2" <?=$s['year']=='2'?"selected":""?>>Phase 02</option>
                            <option value="3" <?=$s['year']=='3'?"selected":""?>>Phase 03</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label>Digital Address (Email)</label>
                    <input type="email" name="email" value="<?=htmlspecialchars($s['email'])?>" class="input-nexus font-bold" required>
                </div>
            </div>

            <div class="pt-6 space-y-4">
                <button name="update" class="w-full action-gradient text-white py-5 rounded-[1.5rem] font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-purple-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Synchronize Changes
                </button>
                
                <a href="manage_students.php?view_year=<?=$s['year']?>" class="flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest opacity-40 hover:opacity-100 transition-opacity">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i> Abort & Return to Nexus
                </a>
            </div>
        </form>
    </div>

    <footer class="mt-12 text-center">
        <p class="text-[8px] font-black opacity-20 uppercase tracking-[1em]">Secure Environment • SHC Admin • Neural v2.0</p>
    </footer>
</div>

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