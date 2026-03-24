<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Module Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            /* Replace these colors with your specific module colors */
            --primary-bg: #0f172a;
            --accent-color: #38bdf8;
            --card-bg: rgba(255, 255, 255, 0.05);
            --text-main: #f8fafc;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* --- Full Width/Height Hero Section --- */
        .hero {
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(45deg, #0f172a 0%, #1e293b 100%);
            position: relative;
            text-align: center;
            padding: 20px;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 5rem);
            margin-bottom: 20px;
            background: linear-gradient(to right, #fff, var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- Navigation --- */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--accent-color);
        }

        /* --- Module Grid Section --- */
        .modules-container {
            padding: 100px 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .module-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 20px;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .module-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-color);
        }

        .module-card i {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        /* --- Specific Module Colors (Examples) --- */
        .mod-1 { border-left: 5px solid #ff4757; }
        .mod-2 { border-left: 5px solid #2ed573; }
        .mod-3 { border-left: 5px solid #1e90ff; }
        /* Add more up to 9 */

        /* --- Utility Classes --- */
        .full-width-content {
            width: 100%;
            padding: 100px 0;
            background: #1e293b;
        }

        footer {
            padding: 50px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo"><strong>CORE</strong>HUB</div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#modules">Modules</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>

    <section id="home" class="hero">
        <h1>Unified Ecosystem</h1>
        <p>Access all 9 modules from one centralized dashboard.</p>
        <div style="margin-top: 30px;">
            <a href="#modules" style="padding: 15px 35px; background: var(--accent-color); color: #000; border-radius: 50px; text-decoration: none; font-weight: bold;">Explore Modules</a>
        </div>
    </section>

    <section id="modules" class="modules-container">
        <div class="section-title">
            <h2>Our Core Services</h2>
            <p>Integrated solutions for your workflow</p>
        </div>

        <div class="module-grid">
            <div class="module-card mod-1">
                <i class="fas fa-chart-line"></i>
                <h3>Analytics</h3>
                <p>Track real-time data across all platforms effortlessly.</p>
            </div>
            <div class="module-card mod-2">
                <i class="fas fa-shield-alt"></i>
                <h3>Security</h3>
                <p>Advanced encryption for your sensitive information.</p>
            </div>
            <div class="module-card mod-3">
                <i class="fas fa-cloud"></i>
                <h3>Cloud Sync</h3>
                <p>Automatic synchronization across every device.</p>
            </div>
            <div class="module-card"><h3>Module 4</h3></div>
            <div class="module-card"><h3>Module 5</h3></div>
            <div class="module-card"><h3>Module 6</h3></div>
            <div class="module-card"><h3>Module 7</h3></div>
            <div class="module-card"><h3>Module 8</h3></div>
            <div class="module-card"><h3>Module 9</h3></div>
        </div>
    </section>

    <section id="about" class="full-width-content">
        <div style="max-width: 800px; margin: 0 auto; text-align: center; padding: 0 20px;">
            <h2>Seamless Connectivity</h2>
            <p style="margin-top: 20px; line-height: 1.6; opacity: 0.8;">
                Our 9-module system is designed to talk to each other without latency. Whether you are in the Analytics module or the Security module, your data remains consistent and error-free.
            </p>
        </div>
    </section>

    <footer id="contact">
        <p>&copy; 2026 CoreHub Systems. All Modules Connected.</p>
    </footer>

</body>
</html>