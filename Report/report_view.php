<?php
include 'auth.php';
include 'db.php';

if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM reports WHERE id=$id"));
$s = mysqli_query($conn, "SELECT * FROM report_students WHERE report_id=$id");

$report_type = strtolower($r['report_type']);
$display_title = ($report_type == 'workshop') ? '"SAIT 26" Workshop' : '"SAIT 26" Competition';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permission Letter | Nexus</title>
    
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
                        darkSurface: "#020617"
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
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.4s ease;
        }

        /* --- UI Elements (Screen Only) --- */
        .neural-bg {
            position: fixed; inset: 0; z-index: -2;
            background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.1) 1px, transparent 0);
            background-size: 32px 32px;
        }
        .orb { position: fixed; border-radius: 50%; opacity: 0.15; filter: blur(100px); z-index: -3; }
        .orb-1 { width: 600px; height: 600px; background: #a855f7; top: -10%; right: -5%; }
        .orb-2 { width: 500px; height: 500px; background: #ec4899; bottom: -10%; left: -5%; }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
        }

        .action-gradient { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }

        /* --- Paper Formatting --- */
        .paper {
            background: white;
            width: 210mm;
            min-height: 297mm;
            padding: 20mm; 
            margin: 0 auto 40px auto;
            box-shadow: 0 40px 100px -20px rgba(0,0,0,0.3);
            color: #000;
            position: relative;
            box-sizing: border-box;
            line-height: 1.5;
        }

        .header-container {
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;
        }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #000; padding: 10px; font-size: 0.9rem; }
        table th { background-color: #f2f2f2; text-transform: uppercase; }

        .signature-section {
            margin-top: 60px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: center;
        }
        .sig-box { font-weight: 700; font-size: 0.75rem; border-top: 1px dashed #000; padding-top: 8px; }

        /* --- STRICT PRINT LOGIC --- */
        @media print {
            @page { 
                margin: 0; /* Strips browser headers/footers */
                size: A4;
            }
            body { background: white !important; margin: 0 !important; padding: 0 !important; }
            .no-print, .neural-bg, .orb, aside, header { display: none !important; }
            main { margin-left: 0 !important; padding: 0 !important; }
            .paper { 
                margin: 0 !important; 
                box-shadow: none !important; 
                width: 210mm !important;
                height: 297mm !important;
                page-break-after: always;
            }
            .paper:last-child { page-break-after: auto; }
        }
    </style>
</head>
<body class="antialiased">

<div class="neural-bg no-print"></div>
<div class="orb orb-1 no-print"></div>
<div class="orb orb-2 no-print"></div>

<aside class="fixed left-0 top-0 h-[calc(100vh-2rem)] w-72 glass-panel z-50 hidden lg:flex flex-col p-8 m-4 rounded-[2.5rem] no-print">
    <div class="mb-12 flex items-center gap-4">
        <div class="w-12 h-12 action-gradient rounded-2xl flex items-center justify-center shadow-lg">
            <i data-lucide="file-check" class="w-6 h-6 text-white"></i>
        </div>
        <div>
            <span class="text-xl font-black tracking-tighter block uppercase">NEXUS</span>
            <span class="text-[10px] font-bold opacity-40 uppercase tracking-[0.2em]">Letter Portal</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-5 py-4 rounded-2xl hover:bg-white/5 transition-all font-semibold opacity-70 hover:opacity-100">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
        </a>
        <button onclick="window.print()" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl bg-primary/10 border-l-4 border-primary font-bold text-primary transition-all">
            <i data-lucide="printer" class="w-5 h-5"></i> Print Document
        </button>
    </nav>

    <div class="mt-auto space-y-4">
        <button onclick="toggleTheme()" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-2xl glass-panel border-primary/20 hover:bg-primary/10 transition-all font-bold text-xs uppercase tracking-widest">
            <i data-lucide="sun" class="w-4 h-4 hidden dark:block text-yellow-400"></i>
            <i data-lucide="moon" class="w-4 h-4 block dark:hidden text-indigo-600"></i>
            Theme Toggle
        </button>
        <a href="logout.php" class="flex items-center justify-center gap-2 px-4 py-4 rounded-2xl bg-red-500/10 text-red-500 font-bold border border-red-500/20 hover:bg-red-500/20 transition-all text-sm uppercase tracking-widest">
            <i data-lucide="power" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>

<main class="lg:ml-80 p-4 lg:p-10">
    <header class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6 no-print">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tighter uppercase">Letter <span class="text-transparent bg-clip-text action-gradient">Preview</span></h1>
            <p class="opacity-50 font-bold text-xs uppercase tracking-[0.3em] mt-2">Professional Document Node</p>
        </div>
        
        <div class="flex items-center gap-4 glass-panel p-3 pr-8 rounded-2xl border-primary/20">
            <div class="w-12 h-12 rounded-xl action-gradient flex items-center justify-center text-white">
                <i data-lucide="user-check" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Admin Node</p>
                <span class="text-sm font-black italic uppercase">BCA DEPT</span>
            </div>
        </div>
    </header>

    <div class="content-wrapper">
        <div class="paper">
            <div class="header-container">
                <div class="logo-box">
                    <img src="BCA.png" alt="BCA Logo" style="height: 75px;">
                </div>
                <div style="text-align: right;">
                    <strong style="font-size: 1.1rem;">Department of Computer Applications (UG)</strong><br>
                    Sacred Heart College (Autonomous)<br>
                    Tirupathur – 635601
                </div>
            </div>
            
            <div style="text-align: right; margin-bottom: 30px; font-weight: 700;">
                Date: <?php echo date('d-M-Y'); ?>
            </div>

            <div style="margin-bottom: 25px;">
                <strong>From,</strong><br>
                The Head of the Department / Association President,<br>
                Department of Computer Applications (UG),<br>
                Sacred Heart College (Autonomous),<br>
                Tirupathur District – 635601.
            </div>

            <div style="margin-bottom: 25px;">
                <strong>To,</strong><br>
                The Additional Principal,<br>
                Sacred Heart College (Autonomous),<br>
                Tirupathur District – 635601.
            </div>

            <div style="font-weight: 700; text-transform: uppercase; margin: 25px 0;">
                Subject: Request for permission to participate in - "<?php echo htmlspecialchars($r['event_name']); ?>"
            </div>

            <div style="margin-bottom: 20px; text-align: justify;">
                Respected Father,<br><br>
                I hope this message finds you well. I am writing to seek your kind permission for our students to participate
                in the upcoming <strong>"<?php echo htmlspecialchars($r['event_name']); ?>"</strong>
                organized by <strong><?php echo htmlspecialchars($r['college_name']); ?></strong>, scheduled on
                <strong><?php echo date('d-M-Y', strtotime($r['event_date'])); ?></strong>.
            </div>

            <div style="margin-bottom: 20px; text-align: justify;">
                The event provides an excellent platform for students to enhance their knowledge,
                skills, and exposure in the field of Computer Applications. We kindly request your approval and support to depute our students for this event. The list of participants is attached herewith for your reference.
            </div>

            <div style="margin-top: 40px;" >
                Thanking you.
            </div>

            <div class="signature-section" style="margin-top: 100px;">
                <div class="sig-box">Head of the Department</div>
                <div class="sig-box">Association President</div>
                <div class="sig-box">Additional Principal</div>
            </div>
        </div>

        <div class="paper">
            <h3 style="text-align: center; text-transform: uppercase; text-decoration: underline; font-weight: 800; margin-bottom: 30px;">Participants List</h3>
            
            <table>
                <thead>
                    <tr>
                        <th width="60">S.No</th>
                        <th width="150">Reg No</th>
                        <th>Student Name</th>
                        <th width="180">Signature</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1; 
                    mysqli_data_seek($s, 0); 
                    while($st = mysqli_fetch_assoc($s)){
                        echo "<tr>
                            <td align='center'>$i</td>
                            <td style='font-weight:700;'>{$st['regno']}</td>
                            <td>{$st['name']}</td>
                            <td></td>
                        </tr>";
                        $i++; 
                    } 
                    ?>
                </tbody>
            </table>

            <div class="signature-section" style="margin-top: 150px;">
                <div class="sig-box">Head of the Department</div>
                <div class="sig-box">Association President</div>
                <div class="sig-box">Additional Principal</div>
            </div>
        </div>
    </div>
</main>

<script>
    lucide.createIcons();

    function toggleTheme() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('nexus_theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('nexus_theme', 'dark');
        }
    }

    if (localStorage.getItem('nexus_theme') === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
    }
</script>

</body>
</html>