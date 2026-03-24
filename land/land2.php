<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartAttend Pro | Enterprise Academic ERP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: "#0f172a",
                        accent: "#3b82f6",
                        success: "#10b981",
                        surface: "#f8fafc",
                        darkSurface: "#020617"
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { transition: background-color 0.3s, color 0.3s; }
        
        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dark .glass-header {
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .text-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dark .text-gradient {
            background: linear-gradient(135deg, #f8fafc 0%, #60a5fa 100%);
            -webkit-background-clip: text;
        }

        .card-neo {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: block; /* Ensures the anchor fills the space */
        }
        
        .dark .card-neo {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .card-neo:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.15);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#3b82f6 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
        
        .dark .hero-pattern {
            background-color: #020617;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
        }

        .nav-link {
            position: relative;
            transition: color 0.3s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #3b82f6;
            transition: width 0.3s;
        }

        .nav-link:hover::after { width: 100%; }

        @keyframes subtle-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .float-anim { animation: subtle-float 5s ease-in-out infinite; }

        .full-section {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body class="bg-surface dark:bg-darkSurface text-primary dark:text-slate-200 antialiased">

    <nav class="fixed top-0 w-full z-50 glass-header">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3 group cursor-pointer" onclick="window.scrollTo(0,0)">
                    <div class="bg-primary dark:bg-blue-600 p-2 rounded-xl group-hover:bg-accent transition-colors shadow-lg">
                        <i data-lucide="layers" class="text-white w-6 h-6"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight dark:text-white">SmartAttend<span class="text-accent">.</span></span>
                </div>
                
                <div class="hidden md:flex space-x-10 items-center">
                    <button id="theme-toggle" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:ring-2 ring-blue-400 transition-all">
                        <i data-lucide="sun" id="theme-toggle-light-icon" class="hidden w-5 h-5"></i>
                        <i data-lucide="moon" id="theme-toggle-dark-icon" class="hidden w-5 h-5"></i>
                    </button>

                    <a href="#about" class="nav-link text-sm font-semibold opacity-70 hover:opacity-100 dark:text-slate-300">Architecture</a>
                    <a href="#modules" class="nav-link text-sm font-semibold opacity-70 hover:opacity-100 dark:text-slate-300">Modules</a>
                    <a href="#tech" class="nav-link text-sm font-semibold opacity-70 hover:opacity-100 dark:text-slate-300">Infrastructure</a>
                    <a href="#" class="bg-primary dark:bg-blue-600 text-white px-7 py-2.5 rounded-full text-sm font-bold hover:bg-accent transition-all shadow-xl shadow-blue-100 dark:shadow-none">Launch Portal</a>
                </div>

                <div class="md:hidden flex items-center gap-4">
                    <button id="mobile-theme-toggle" class="p-2">
                        <i data-lucide="moon" class="dark:hidden w-6 h-6"></i>
                        <i data-lucide="sun" class="hidden dark:block w-6 h-6"></i>
                    </button>
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </nav>

    <header class="full-section hero-pattern relative overflow-hidden pt-20">
        <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-8 text-xs font-bold tracking-widest text-accent uppercase bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 rounded-full">
                        <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                        Standardized Academic Protocol 2026
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight mb-8">
                        The Next Era of <br>
                        <span class="text-gradient">Student Insights.</span>
                    </h1>
                    <p class="text-lg text-slate-500 dark:text-slate-400 mb-10 max-w-xl leading-relaxed">
                        A centralized Management Information System (MIS) bridging communication gaps between institutions and parents through automated micro-triggers.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-5">
                        <a href="#modules" class="flex items-center justify-center gap-3 bg-primary dark:bg-blue-600 text-white px-10 py-5 rounded-2xl font-bold hover:bg-accent transition-all shadow-2xl shadow-blue-200 dark:shadow-none group">
                            Explore Modules <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <a href="#tech" class="flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-10 py-5 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all dark:text-white">
                            Review Technical Specs
                        </a>
                    </div>
                </div>

                <div class="relative hidden lg:block float-anim" data-aos="zoom-in" data-aos-duration="1200">
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl p-8 border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-10 border-b dark:border-slate-800 pb-6">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                                <div class="w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                                <div class="w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                            </div>
                            <div class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Production Environment</div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-6 rounded-3xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800">
                                <i data-lucide="activity" class="text-accent w-6 h-6 mb-4"></i>
                                <div class="text-2xl font-black text-primary dark:text-white">94.8%</div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">System Uptime</div>
                            </div>
                            <div class="p-6 rounded-3xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800">
                                <i data-lucide="send" class="text-success w-6 h-6 mb-4"></i>
                                <div class="text-2xl font-black text-primary dark:text-white">12k+</div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Notifications Sent</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="about" class="full-section bg-white dark:bg-darkSurface">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="order-2 lg:order-1" data-aos="fade-up">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80" alt="Tech" class="rounded-[3rem] shadow-2xl mx-auto max-h-[70vh] object-cover">
                        </div>
                </div>
                <div class="order-1 lg:order-2" data-aos="fade-left">
                    <h2 class="text-xs font-black text-accent uppercase tracking-[0.3em] mb-4">System Vision</h2>
                    <h3 class="text-4xl font-bold mb-8 leading-tight dark:text-white">Solving The Communication Deadlock.</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-10 leading-relaxed font-medium">
                        Our architecture is built on the principle of **Automated Transparency**. By digitizing the attendance register, we eliminate manual error and provide parents with instant, non-refutable verification of their child's classroom presence.
                    </p>
                    
                    <div class="grid gap-6">
                        <div class="flex gap-5 items-start p-6 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-900 transition-colors">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 text-accent rounded-xl flex-shrink-0 flex items-center justify-center">
                                <i data-lucide="zap" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg dark:text-white">Instant Trigger Latency</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Notifications are dispatched within &lt; 2 seconds of the attendance marking event.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="modules" class="full-section bg-slate-50 dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-6 w-full py-20">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-xs font-black text-accent uppercase tracking-[0.3em] mb-4">Core Ecosystem</h2>
                <h3 class="text-4xl font-bold text-primary dark:text-white italic">9 Functional Micro-Modules</h3>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <a href="Attendace/attendance_login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-slate-900 dark:bg-slate-700 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Class Attendance</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Central orchestration for multi-department management and faculty role assignment.</p>
                </a>

                <a href="Academic/academic_login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-100 dark:shadow-none">
                        <i data-lucide="briefcase" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Faculty Registry</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Staff management module with secure credentialing and workload tracking features.</p>
                </a>

                <a href="Leave/leave_login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-100 dark:shadow-none">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Student Ledger</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Universal student database containing enrollment data and parent contact mapping.</p>
                </a>

                <a href="Green/green_login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-orange-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="check-square" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Attendance Engine</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Period-wise marking system with bulk-processing and legacy record editing.</p>
                </a>

                <a href="Notice/notice_login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Dynamic Timetable</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Algorithmic scheduling logic to prevent faculty overlap and resource conflicts.</p>
                </a>

                <a href="Descipline/discipline_login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="smartphone" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">SMS Gateway</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Direct Twilio integration for automated real-time absence alerts to guardians.</p>
                </a>

                <a href="Monthly/login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-yellow-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="mail" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Mail Automator</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Scheduled SMTP services for monthly attendance summaries and PDF delivery.</p>
                </a>

                <a href="Course/login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-cyan-500 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Reporting Suite</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">Generate cumulative analytics for institutional audit and academic review.</p>
                </a>

                <a href="Admin/login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-slate-400 dark:bg-slate-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Security Layer</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">End-to-end encryption for student data and multi-level session auth.</p>
                </a>
                <a href="Admin/login.php" class="card-neo p-8 rounded-[2rem]" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 bg-slate-400 dark:bg-slate-600 text-white rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i data-lucide="lock" class="w-5 h-5"></i>
                    </div>
                    <h4 class="text-lg font-bold mb-3 dark:text-white">Security Layer</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed">End-to-end encryption for student data and multi-level session auth.</p>
                </a>
            </div>
        </div>
    </section>

    <section id="techh" class="full-section bg-primary text-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="text-center mb-24">
                <h3 class="text-4xl font-black mb-4 tracking-tighter italic">Enterprise Infrastructure</h3>
                <p class="text-slate-400 font-medium">Built for scale, stability, and speed.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-slate-800 border border-slate-800 rounded-[2rem] overflow-hidden">
                <div class="p-16 bg-primary flex flex-col items-center hover:bg-slate-900 transition-colors group">
                    <div class="text-accent text-3xl font-black mb-4 group-hover:scale-110 transition-transform">UI/UX</div>
                    <div class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-40">Tailwind + JS</div>
                </div>
                <div class="p-16 bg-primary flex flex-col items-center hover:bg-slate-900 transition-colors group">
                    <div class="text-accent text-3xl font-black mb-4 group-hover:scale-110 transition-transform">CORE</div>
                    <div class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-40">PHP 8.2 Engine</div>
                </div>
                <div class="p-16 bg-primary flex flex-col items-center hover:bg-slate-900 transition-colors group">
                    <div class="text-accent text-3xl font-black mb-4 group-hover:scale-110 transition-transform">DATA</div>
                    <div class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-40">MySQL Relational</div>
                </div>
                <div class="p-16 bg-primary flex flex-col items-center hover:bg-slate-900 transition-colors group">
                    <div class="text-accent text-3xl font-black mb-4 group-hover:scale-110 transition-transform">API</div>
                    <div class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-40">Twilio Gateway</div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white dark:bg-darkSurface pt-32 pb-12 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                <div class="col-span-1 lg:col-span-2">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-primary dark:bg-blue-600 p-2 rounded-lg">
                            <i data-lucide="layers" class="text-white w-5 h-5"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tight italic dark:text-white">SmartAttend.</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium max-w-sm mb-8 leading-relaxed">
                        An advanced BCA final year project demonstration showcasing enterprise-level system design and real-world utility.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8">Lead Engineer</h4>
                    <ul class="space-y-4 text-sm font-bold text-primary dark:text-white">
                        <li>AFSAR A</li>
                        <li class="opacity-50 font-medium">BU231038</li>
                        <li class="opacity-50 font-medium">BCA Final Year</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8">Institutional</h4>
                    <ul class="space-y-4 text-sm font-bold text-primary dark:text-white">
                        <li>SACRED HEART COLLEGE (AUTONOMOUS)</li>
                        <li class="opacity-50 font-medium">Guide: S. SWATHI</li>
                        <li class="opacity-50 font-medium">Academic Year 2023-26</li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">© 2026 Smart Attendance System Pro</p>
                <div class="flex gap-6">
                    <i data-lucide="github" class="w-4 h-4 text-slate-300 cursor-pointer hover:text-accent transition-colors"></i>
                    <i data-lucide="linkedin" class="w-4 h-4 text-slate-300 cursor-pointer hover:text-accent transition-colors"></i>
                    <i data-lucide="external-link" class="w-4 h-4 text-slate-300 cursor-pointer hover:text-accent transition-colors"></i>
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        AOS.init({ once: true, offset: 120 });

        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        function updateIcons() {
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                themeToggleLightIcon.classList.add('hidden');
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }
        
        updateIcons();

        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
            updateIcons();
        });

        document.getElementById('mobile-theme-toggle').addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            updateIcons();
        });
    </script>
</body>
</html>