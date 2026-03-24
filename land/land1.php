<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Class Quality Circle | Final Year Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');

        :root {
            --primary: #0f172a;
            --accent: #10b981;
            --secondary: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
            background-color: #f8fafc;
        }

        .full-section {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 2rem;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border-color: var(--accent);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-gradient::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 40%;
            height: 60%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            z-index: 0;
        }

        .step-line::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 100%;
            height: 2px;
            background: #e2e8f0;
            z-index: -1;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .tech-pill {
            background: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body>

    <!-- NAVIGATION -->
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500 rounded flex items-center justify-center text-white">
                    <i class="fas fa-circle-nodes"></i>
                </div>
                <span class="font-bold text-slate-900 text-lg tracking-tight">SCQC</span>
            </div>
            <div class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
                <a href="#about" class="hover:text-emerald-600 transition-colors">About</a>
                <a href="#modules" class="hover:text-emerald-600 transition-colors">Modules</a>
                <a href="#architecture" class="hover:text-emerald-600 transition-colors">Architecture</a>
                <a href="#stack" class="hover:text-emerald-600 transition-colors">Technology</a>
            </div>
            <a href="#about" class="bg-slate-900 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-slate-800 transition-all">
                Project Docs
            </a>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero-gradient full-section relative text-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center relative z-10">
            <div>
                <span class="inline-block px-4 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest mb-6">
                    Final Year Academic Project
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6">
                    Smart Class <br><span class="text-emerald-400">Quality Circle</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-lg leading-relaxed">
                    A Smart Digital Platform designed for efficient Academic Quality Management and institutional process automation.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#modules" class="bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-4 rounded-lg font-bold transition-all flex items-center gap-2">
                        Explore Modules <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#about" class="bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-lg font-bold backdrop-blur-sm transition-all">
                        Project Overview
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="relative">
                    <!-- Abstract Tech Decoration -->
                    <div class="absolute inset-0 bg-emerald-500/20 blur-3xl rounded-full"></div>
                    <div class="relative border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&q=80&w=1000" alt="Tech Dashboard" class="opacity-80">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ABOUT SECTION -->
    <section id="about" class="full-section bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Redefining Classroom Intelligence</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Bridging the gap between manual administration and automated academic excellence.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="space-y-6">
                    <h3 class="text-2xl font-bold text-slate-800">The Problem & Our Solution</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Traditional educational management suffers from data fragmentation and manual delays. <strong>Smart Class Quality Circle (SCQC)</strong> automates the vital loops of classroom monitoring—from attendance tracking to faculty performance assessment.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                            <span><strong class="text-slate-900">Automation:</strong> Reduces administrative overhead for HODs and Faculty.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                            <span><strong class="text-slate-900">Monitoring:</strong> Real-time quality circle feedback loops.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                            <span><strong class="text-slate-900">Communication:</strong> Integrated SMS and Email notification system.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                        <h4 class="font-bold text-slate-900 mb-2">Faculty</h4>
                        <p class="text-xs text-slate-500">Efficient scheduling and performance tracking.</p>
                    </div>
                    <div class="p-6 bg-emerald-50 rounded-xl border border-emerald-100">
                        <h4 class="font-bold text-slate-900 mb-2">Students</h4>
                        <p class="text-xs text-slate-500">Transparent attendance and assessment history.</p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                        <h4 class="font-bold text-slate-900 mb-2">HOD/Admin</h4>
                        <p class="text-xs text-slate-500">Centralized control and department-wide visibility.</p>
                    </div>
                    <div class="p-6 bg-slate-900 rounded-xl text-white">
                        <h4 class="font-bold mb-2">Management</h4>
                        <p class="text-xs text-slate-400">Data-driven reports for institutional growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SYSTEM ARCHITECTURE -->
    <section id="architecture" class="full-section bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">System Architecture</h2>
                <p class="text-slate-500">A robust, scalable workflow ensuring data integrity and real-time alerts.</p>
            </div>
            
            <div class="relative flex flex-col md:flex-row justify-between items-center gap-8 md:gap-0 max-w-5xl mx-auto">
                <!-- User -->
                <div class="relative z-10 flex flex-col items-center group">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-700 text-2xl group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="mt-4 font-semibold text-slate-800">End Users</span>
                    <p class="text-xs text-slate-400 mt-1">Faculty / Students</p>
                </div>

                <div class="hidden md:block h-0.5 w-24 bg-slate-200"></div>

                <!-- Web App -->
                <div class="relative z-10 flex flex-col items-center group">
                    <div class="w-24 h-24 bg-white rounded-2xl shadow-xl flex items-center justify-center text-emerald-500 text-3xl group-hover:scale-110 transition-all border-2 border-emerald-500">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <span class="mt-4 font-semibold text-slate-800">PHP Web App</span>
                    <p class="text-xs text-slate-400 mt-1">Core Logic Layer</p>
                </div>

                <div class="hidden md:block h-0.5 w-24 bg-slate-200"></div>

                <!-- Database -->
                <div class="relative z-10 flex flex-col items-center group">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-700 text-2xl group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <i class="fas fa-database"></i>
                    </div>
                    <span class="mt-4 font-semibold text-slate-800">MySQL DB</span>
                    <p class="text-xs text-slate-400 mt-1">Persistent Storage</p>
                </div>

                <div class="hidden md:block h-0.5 w-24 bg-slate-200"></div>

                <!-- Notifications -->
                <div class="relative z-10 flex flex-col items-center group">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-700 text-2xl group-hover:bg-emerald-500 group-hover:text-white transition-all">
                        <i class="fas fa-bell"></i>
                    </div>
                    <span class="mt-4 font-semibold text-slate-800">Alert System</span>
                    <p class="text-xs text-slate-400 mt-1">SMS & Email</p>
                </div>
            </div>

            <div class="mt-20 p-8 bg-white border border-slate-200 rounded-2xl max-w-4xl mx-auto shadow-sm">
                <p class="text-slate-600 text-center leading-relaxed italic">
                    "The architecture follows a classic Client-Server model where the PHP-driven backend acts as a bridge between the secure MySQL data repository and a responsive user interface, utilizing SMTP and Gateway APIs for instant communication."
                </p>
            </div>
        </div>
    </section>

    <!-- CORE MODULES -->
    <section id="modules" class="full-section bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-slate-900">Core Industry-Level Modules</h2>
                <div class="w-20 h-1 bg-emerald-500 mt-4"></div>
            </div>
            
            <div class="module-grid">
                <!-- 1 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Student Management</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Comprehensive profiles with performance history and academic record tracking.</p>
                </div>
                <!-- 2 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Faculty Management</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Resource allocation and workload balancing for academic staff.</p>
                </div>
                <!-- 3 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Attendance Tracking</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Daily and lecture-wise attendance monitoring with automated shortfall alerts.</p>
                </div>
                <!-- 4 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-table"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Smart Timetable</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Dynamic scheduling engine to prevent resource clashes and optimize hours.</p>
                </div>
                <!-- 5 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-pink-50 text-pink-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Performance & Assessment</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Internal mark entry, assessment analysis, and progress report generation.</p>
                </div>
                <!-- 6 -->
                <div class="glass-card p-8 rounded-2xl border-emerald-200">
                    <div class="w-12 h-12 bg-emerald-500 text-white rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-arrows-to-circle"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Quality Circle Monitoring</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Feedback mechanisms ensuring teaching standards meet institutional benchmarks.</p>
                </div>
                <!-- 7 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-sms"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Notification System</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Instant SMS and Email gateways for urgent notices and attendance alerts.</p>
                </div>
                <!-- 8 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-slate-900 text-white rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Reports & Analytics</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Visual charts and downloadable PDF/Excel reports for management review.</p>
                </div>
                <!-- 9 -->
                <div class="glass-card p-8 rounded-2xl">
                    <div class="w-12 h-12 bg-red-50 text-red-600 rounded-lg flex items-center justify-center mb-6 text-xl">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-3">Admin Control</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Role-based access control (RBAC) and high-level security protocols.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TECHNOLOGY STACK -->
    <section id="stack" class="full-section bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-16">Technology Stack</h2>
            
            <div class="flex flex-wrap justify-center gap-6">
                <div class="tech-pill text-slate-900 font-bold">
                    <i class="fab fa-html5 text-orange-500 text-xl"></i> HTML5 & CSS3
                </div>
                <div class="tech-pill text-slate-900 font-bold">
                    <i class="fab fa-js text-yellow-500 text-xl"></i> JavaScript (ES6+)
                </div>
                <div class="tech-pill text-slate-900 font-bold border-2 border-emerald-500">
                    <i class="fab fa-php text-blue-600 text-xl"></i> PHP 8.x (Backend)
                </div>
                <div class="tech-pill text-slate-900 font-bold">
                    <i class="fas fa-database text-blue-500 text-xl"></i> MySQL
                </div>
                <div class="tech-pill text-slate-900 font-bold">
                    <i class="fas fa-server text-slate-500 text-xl"></i> Apache Server
                </div>
                <div class="tech-pill text-slate-900 font-bold">
                    <i class="fas fa-envelope text-red-500 text-xl"></i> SMS & Email APIs
                </div>
            </div>

            <div class="mt-20 grid md:grid-cols-2 gap-8 text-left max-w-4xl mx-auto">
                <div class="p-8 border border-white/10 rounded-2xl">
                    <h3 class="text-emerald-400 font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-impact-gradient"></i> Real-World Impact
                    </h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        SCQC transforms manual institutions by increasing data accuracy by 95% and saving faculty 10+ hours per week in administrative tasks. Transparency leads to better student performance.
                    </p>
                </div>
                <div class="p-8 border border-white/10 rounded-2xl">
                    <h3 class="text-emerald-400 font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-rocket"></i> Future Enhancements
                    </h3>
                    <ul class="text-slate-400 text-sm space-y-2">
                        <li>• AI-based Performance Prediction</li>
                        <li>• Native Mobile App Integration</li>
                        <li>• Biometric & Face Recognition Attendance</li>
                        <li>• Cloud-based Deployment & Scalability</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="text-center md:text-left">
                    <h4 class="text-xl font-bold text-slate-900 mb-2">Smart Class Quality Circle</h4>
                    <p class="text-slate-500 text-sm uppercase tracking-widest font-semibold">Final Year Project – BCA</p>
                </div>
                <div class="text-center md:text-right text-sm text-slate-500">
                    <p class="mb-1">Department of Computer Applications</p>
                    <p class="mb-1">Academic Year: 2024-2025</p>
                    <p class="text-slate-400 font-mono">SCQC_V1.0_PROD</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-slate-100 text-center text-xs text-slate-400">
                Designed for professional academic evaluation. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Sticky Navbar background change on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md');
            } else {
                nav.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>