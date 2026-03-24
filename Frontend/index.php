<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>The Syndicate | Quality Circle</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --netflix-red: #E50914;
            --dark-bg: #000000;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: var(--dark-bg);
            overflow: hidden; /* Keeps it full screen */
        }

        .hero-fullscreen {
            height: 100vh;
            width: 100%;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.9)), 
                        url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-content {
            max-width: 800px;
            padding: 20px;
        }

        .hero-content h1 {
            font-family: 'Oswald', sans-serif;
            font-size: clamp(3rem, 10vw, 6rem); /* Responsive typography */
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 10px;
            text-shadow: 0 0 20px rgba(229, 9, 20, 0.5);
        }

        .hero-content p {
            color: #ccc;
            font-size: 1.2rem;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .btn-syndicate {
            background-color: var(--netflix-red);
            color: white;
            padding: 15px 45px;
            font-size: 1.2rem;
            font-weight: 700;
            border: none;
            border-radius: 4px;
            text-transform: uppercase;
            transition: 0.3s all ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-syndicate:hover {
            background-color: #b20710;
            transform: scale(1.05);
            color: white;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.4);
        }
    </style>
</head>
<body>

    <div class="hero-fullscreen">
        <div class="hero-content">
            <div style="color: var(--netflix-red); font-weight: 700; letter-spacing: 2px;">SMART CLASS</div>
            <h1>QUALITY CIRCLE</h1>
            <p>The Syndicate of Academic Excellence. Manage attendance, discipline, and records with surgical precision.</p>
            <a href="dashboard.php" class="btn-syndicate">▶ Get Started</a>
        </div>
    </div>

</body>
</html>