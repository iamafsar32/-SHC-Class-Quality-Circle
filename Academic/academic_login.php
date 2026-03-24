<!DOCTYPE html>
<html lang="en" class="dark" id="html-tag">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AMS Terminal | Neural Link</title>
    
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
            --bg-body: #f1f5f9;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --border: rgba(0, 0, 0, 0.08);
        }

        .dark {
            --bg-body: #020617;
            --glass-bg: rgba(15, 23, 42, 0.7);
            --border: rgba(255, 255, 255, 0.08);
        }

        body { 
            background-color: var(--bg-body);
            transition: background-color 0.4s ease;
            overflow: hidden;
            position: relative;
        }

        /* --- NEXT LEVEL BACKGROUND ORBS --- */
        .orb-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            overflow: hidden;
            filter: blur(80px);
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
            animation: float 20s infinite alternate ease-in-out;
        }
        .orb-1 { width: 500px; height: 500px; background: linear-gradient(to right, #a855f7, #6366f1); top: -10%; left: -5%; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: linear-gradient(to right, #ec4899, #f43f5e); bottom: -10%; right: -5%; animation-delay: -5s; }
        .orb-3 { width: 300px; height: 300px; background: #22d3ee; top: 40%; left: 30%; opacity: 0.2; animation-delay: -10s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 100px) scale(1.1); }
        }

        .logic-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            z-index: 10;
        }

        .input-box {
            position: relative;
            margin-top: 1.5rem;
        }

        .custom-field {
            width: 100%;
            padding: 1rem 1rem 1rem 3.2rem;
            background: transparent !important;
            border: 1.5px solid var(--border);
            border-radius: 1.25rem;
            outline: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* --- NO BACKGROUND LABEL --- */
        .floating-label {
            position: absolute;
            left: 3.2rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent !important; /* Removed Background */
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 700;
            pointer-events: none;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Labels floats ABOVE the border to avoid clash without needing a background */
        .custom-field:focus ~ .floating-label,
        .custom-field:not(:placeholder-shown) ~ .floating-label {
            top: -12px; /* Moves label above the border line */
            left: 0.5rem;
            font-size: 0.75rem;
            color: #a855f7;
            opacity: 1;
        }

        .custom-field:focus {
            border-color: #a855f7;
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px -10px rgba(168, 85, 247, 0.5);
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -10px rgba(168, 85, 247, 0.6);
        }

        .spinner {
            display: none; width: 22px; height: 22px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px transparent inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 antialiased text-slate-900 dark:text-slate-100 font-sans">
    
    <div class="orb-container">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <main class="w-full max-w-4xl logic-card rounded-[3rem] overflow-hidden flex flex-col md:flex-row min-h-[550px]">
        
        <div class="md:w-5/12 relative bg-primary/5 flex flex-col p-12 justify-between hidden md:flex border-r border-white/5">
            <div class="z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-primary/20 flex items-center justify-center backdrop-blur-md">
                    <i data-lucide="shield-check" class="text-primary w-6 h-6"></i>
                </div>
                <span class="font-mono text-[10px] font-black tracking-[0.3em] uppercase opacity-60">Auth System</span>
            </div>

            <div class="z-10 space-y-5">
                <h2 class="text-4xl font-extrabold tracking-tight leading-tight">Neural<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Terminal.</span></h2>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">Secure gateway to SmartClass management and administrative protocols.</p>
            </div>

            <div class="z-10 flex items-center gap-2 text-emerald-500">
                <div class="w-2 h-2 rounded-full bg-current animate-pulse"></div>
                <span class="text-[9px] font-black uppercase tracking-widest">System Online</span>
            </div>
        </div>

        <div class="md:w-7/12 p-10 lg:p-16 flex flex-col justify-center relative">
            
            <button onclick="toggleTheme()" class="absolute top-10 right-10 p-3 rounded-2xl hover:bg-slate-500/10 transition-colors">
                <i data-lucide="moon" class="dark:hidden w-5 h-5"></i>
                <i data-lucide="sun" class="hidden dark:block w-5 h-5 text-yellow-500"></i>
            </button>

            <header class="mb-10">
                <h1 class="text-3xl font-extrabold tracking-tight">Welcome</h1>
                <p class="text-slate-500 text-sm mt-2">Identify yourself to access the console.</p>
            </header>

            <form onsubmit="return handleSubmit()" action="academic_auth.php" method="POST" class="space-y-6">
                
                <div class="input-box">
                    <i data-lucide="user" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 z-10"></i>
                    <input type="text" name="username" required placeholder=" " class="custom-field">
                    <label class="floating-label">Admin Identity</label>
                </div>

                <div class="input-box">
                    <i data-lucide="lock" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 z-10"></i>
                    <input type="password" id="password" name="password" required placeholder=" " class="custom-field">
                    <label class="floating-label">Security Key</label>
                    <button type="button" onclick="togglePassword()" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors">
                        <i id="toggleIcon" data-lucide="eye" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 transition-all bg-transparent">
                        <span class="text-[11px] font-bold text-slate-400 group-hover:text-primary transition-colors uppercase tracking-widest">Remember Me</span>
                    </label>
                    <a href="#" class="text-[11px] font-black text-primary hover:opacity-70 transition-opacity uppercase tracking-widest">Reset Key</a>
                </div>

                <button type="submit" id="submitBtn" 
                    class="w-full relative flex items-center justify-center py-4 mt-4 btn-gradient text-white rounded-2xl font-bold text-xs uppercase tracking-[0.2em]">
                    <span id="btnText" class="z-10">Authorize Access</span>
                    <div id="btnSpinner" class="spinner absolute z-10"></div>
                </button>
            </form>

            <footer class="mt-12 flex items-center justify-between border-t border-slate-500/10 pt-6 opacity-40">
                <span class="text-[9px] font-mono font-bold uppercase tracking-tighter text-primary">v4.0.5 Optimized</span>
                <div class="flex gap-4">
                    <i data-lucide="fingerprint" class="w-4 h-4"></i>
                    <i data-lucide="zap" class="w-4 h-4"></i>
                </div>
            </footer>
        </div>
    </main>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const passInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                passInput.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        function handleSubmit() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            text.classList.add('invisible');
            spinner.style.display = 'block';
            return true; 
        }

        function toggleTheme() {
            document.getElementById('html-tag').classList.toggle('dark');
        }
    </script>
</body>
</html>