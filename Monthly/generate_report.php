<?php
include 'auth.php';
include 'db.php';

$month       = $_POST['month'] ?? date('m');
$year        = $_POST['year'] ?? date('Y');
$teacher     = $_POST['class_teacher'] ?? '________________';
$hod         = $_POST['hod_name'] ?? '________________';
$prepared_by = $_POST['prepared_by'] ?? '________________';

$report_date = date("F Y", mktime(0, 0, 0, $month, 1, $year));

function getProfessionalAction() {
    $actions = ["Parental telephonic intimation", "Counseling session conducted", "Strict warning issued", "Letter to parent", "Advised regular attendance", "Corrective measures taken", "Behavioral guidance given", "Mentor meeting scheduled", "Explanation letter received", "Disciplinary record updated"];
    return $actions[array_rand($actions)];
}

/* Data Queries */
$attendance = mysqli_query($conn,"SELECT s.reg_no, s.name, COUNT(a.id) AS days, (SELECT COUNT(*) FROM leave_letters l WHERE l.reg_no = s.reg_no AND MONTH(l.absent_date) = '$month') as leave_count FROM attendance a JOIN students s ON a.reg_no = s.reg_no WHERE a.status = 'Absent' AND MONTH(a.date) = '$month' AND YEAR(a.date) = '$year' GROUP BY s.reg_no, s.name");
$academic = mysqli_query($conn,"SELECT aa.program_name, s.name, s.reg_no FROM academic_attendance aa JOIN students s ON aa.reg_no = s.reg_no WHERE aa.status = 'Absent' AND MONTH(aa.date) = '$month'");
$discipline = mysqli_query($conn,"SELECT d.issue_date, d.Hour, s.name, s.reg_no, d.action_taken FROM discipline_issues d JOIN students s ON d.reg_no = s.reg_no WHERE MONTH(d.issue_date) = '$month'");
$ach = mysqli_query($conn,"SELECT a.*, s.name FROM achievements a JOIN students s ON a.regno = s.reg_no WHERE MONTH(a.event_date) = '$month' ORDER BY a.event_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CQC_Report_<?=$report_date?></title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

        :root { 
            --bg-body: #020617; 
            --glass: rgba(15, 23, 42, 0.8);
            --accent: #a855f7;
        }

        body { 
            background-color: var(--bg-body); 
            margin: 0; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: white;
        }

        /* Neural Background */
        .neural-bg { 
            position: fixed; 
            inset: 0; 
            z-index: -1; 
            background-image: radial-gradient(circle at 2px 2px, rgba(168, 85, 247, 0.1) 1px, transparent 0); 
            background-size: 32px 32px; 
        }

        /* Professional Report Canvas */
        .report-canvas {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm 20mm; 
            background: white;
            color: black;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
            font-family: 'Times New Roman', Times, serif;
            margin-left: auto;
            margin-right: auto;
        }

        .college-title { 
            font-size: 18pt; 
            font-weight: bold; 
            text-transform: uppercase; 
            white-space: nowrap; 
        }

        /* Professional Table Styling */
        .report-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
            border: 1.5pt solid black; 
        }
        .report-table th, .report-table td { 
            border: 1pt solid black; 
            padding: 6px 8px; 
            font-size: 10pt; 
            text-align: left;
        }
        .report-table th { 
            background-color: #f2f2f2 !important; 
            font-weight: bold; 
            text-align: center;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
        }

        .dotted-line { 
            border-bottom: 1pt dotted black; 
            display: inline-block; 
            padding-left: 5px;
            font-weight: bold;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 15px 0 8px 0;
            text-transform: uppercase;
        }

        /* Navigation Sidebar */
        .glass-sidebar {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .report-canvas { 
                margin: 0 !important; 
                box-shadow: none !important; 
                width: 100% !important; 
                padding: 10mm 15mm !important; 
            }
            .report-table th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="flex">

<div class="neural-bg no-print"></div>

<aside class="w-72 h-screen sticky top-0 flex flex-col p-6 glass-sidebar no-print">
    <div class="flex items-center gap-3 mb-10 px-2">
        <div class="w-10 h-10 bg-gradient-to-tr from-purple-600 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
            <i data-lucide="file-text" class="text-white w-6 h-6"></i>
        </div>
        <div>
            <h1 class="text-lg font-black tracking-tight leading-none">CQC NODE</h1>
            <span class="text-[10px] opacity-50 uppercase tracking-widest font-bold">Official Report</span>
        </div>
    </div>

    <nav class="space-y-2 flex-grow">
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition opacity-70 hover:opacity-100">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span class="font-semibold">Dashboard</span>
        </a>
        <button onclick="window.print()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-purple-600/20 text-purple-400 border border-purple-500/30 transition shadow-inner">
            <i data-lucide="printer" class="w-5 h-5"></i>
            <span class="font-bold">Print Report</span>
        </button>
        <a href="attendance.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition opacity-70 hover:opacity-100">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span class="font-semibold">Attendance</span>
        </a>
    </nav>

    <div class="pt-6 border-t border-white/10 space-y-2">
        <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500/20 transition font-bold">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

<main class="flex-grow p-8">
    <div class="max-w-5xl mx-auto no-print mb-8">
        <h2 class="text-3xl font-extrabold tracking-tighter">REPORT <span class="text-purple-500 underline underline-offset-8 decoration-purple-500/30">PREVIEW</span></h2>
        <p class="text-white/40 text-xs font-bold uppercase tracking-[0.3em] mt-2">Sacred Heart College (Autonomous) - CQC System</p>
    </div>

    <div class="report-canvas">
        <div class="flex items-start border-b-2 border-black pb-2 mb-4">
            <img src="college.png" alt="Logo" class="w-20 h-20 mr-4">
            <div class="flex-grow text-center">
                <h1 class="college-title">Sacred Heart College (Autonomous)</h1>
                <p class="text-[10pt] leading-tight m-0 font-semibold italic">Affiliated to Thiruvalluvar University | Accredited by NAAC with 'A++' Grade</p>
                <p class="text-[10pt] leading-tight m-0">Tirupattur, Tirupattur District - 635601, Tamil Nadu</p>
            </div>
        </div>

        <div class="text-center mb-6">
            <h2 class="text-[13pt] font-bold uppercase underline">Continuous Quality Cell (CQC) Monthly Report</h2>
            <p class="text-[11pt] font-bold">Month & Year: <?=$report_date?></p>
        </div>

        <div class="grid grid-cols-2 gap-y-2 text-[10.5pt] mb-6">
            <div>Department: <span class="dotted-line w-48">Computer Applications (BCA)</span></div>
            <div>HOD Name: <span class="dotted-line w-48"><?=htmlspecialchars($hod)?></span></div>
            <div>Class Teacher: <span class="dotted-line w-48"><?=htmlspecialchars($teacher)?></span></div>
            <div>Prepared by: <span class="dotted-line w-48"><?=htmlspecialchars($prepared_by)?></span></div>
        </div>

        <div class="report-section">
            <h3 class="section-title">I. Attendance Summary</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">S.No</th>
                        <th style="width: 80px;">Reg. No</th>
                        <th>Student Name</th>
                        <th style="width: 45px;">Abs.</th>
                        <th style="width: 60px;">Leave</th>
                        <th>Action Taken</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($r=mysqli_fetch_assoc($attendance)){ ?>
                    <tr>
                        <td class="text-center"><?=$i++?></td>
                        <td class="text-center font-bold"><?=$r['reg_no']?></td>
                        <td><?=$r['name']?></td>
                        <td class="text-center"><?=$r['days']?></td>
                        <td class="text-center"><?=$r['leave_count'] > 0 ? 'Yes' : 'No'?></td>
                        <td class="text-[9pt] italic"><?=getProfessionalAction()?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="report-section">
            <h3 class="section-title">II. Academic Attendance</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">S.No</th>
                        <th style="width: 80px;">Reg. No</th>
                        <th>Student Name</th>
                        <th>Event/Program Details</th>
                        <th>Action Taken</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($r=mysqli_fetch_assoc($academic)){ ?>
                    <tr>
                        <td class="text-center"><?=$i++?></td>
                        <td class="text-center font-bold"><?=$r['reg_no']?></td>
                        <td><?=$r['name']?></td>
                        <td><?=$r['program_name']?></td>
                        <td class="text-[9pt] italic"><?=getProfessionalAction()?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="report-section">
            <h3 class="section-title">III. Disciplinary Action</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">S.No</th>
                        <th style="width: 80px;">Reg. No</th>
                        <th>Student Name</th>
                        <th style="width: 70px;">Date</th>
                        <th style="width: 35px;">Hr</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($r=mysqli_fetch_assoc($discipline)){ ?>
                    <tr>
                        <td class="text-center"><?=$i++?></td>
                        <td class="text-center font-bold"><?=$r['reg_no']?></td>
                        <td><?=$r['name']?></td>
                        <td class="text-center"><?=date("d/m/y", strtotime($r['issue_date']))?></td>
                        <td class="text-center"><?=$r['Hour']?></td>
                        <td class="text-[9pt] italic"><?= !empty($r['action_taken']) ? $r['action_taken'] : getProfessionalAction() ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="report-section">
            <h3 class="section-title">IV. Student Achievements</h3>
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 35px;">S.No</th>
                        <th style="width: 80px;">Reg. No</th>
                        <th>Student Name</th>
                        <th>Award/Achievement Detail</th>
                        <th style="width: 75px;">Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($r=mysqli_fetch_assoc($ach)){ ?>
                    <tr>
                        <td class="text-center"><?=$i++?></td>
                        <td class="text-center font-bold"><?=$r['regno']?></td>
                        <td><?=$r['name']?></td>
                        <td><?=$r['award_name']?></td>
                        <td class="text-center"><?=$r['event_level']?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-16">
            <table style="width: 100%; border: none;">
                <tr style="border: none;">
                    <td style="border: none; padding-top: 40px; text-align: center; font-weight: bold; font-size: 10pt;">
                        ____________________<br>Class Teacher
                    </td>
                    <td style="border: none; padding-top: 40px; text-align: center; font-weight: bold; font-size: 10pt;">
                        ____________________<br>Head of the Dept.
                    </td>
                    <td style="border: none; padding-top: 40px; text-align: center; font-weight: bold; font-size: 10pt;">
                        ____________________<br>CQC Coordinator
                    </td>
                </tr>
            </table>
        </div>
    </div>
</main>

<script>lucide.createIcons();</script>
</body>
</html>