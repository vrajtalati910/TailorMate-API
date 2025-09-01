<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel App</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8e6cf, #dcedc1); /* 🌿 soft green gradient */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f4f4f;
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.8);
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #2e7d32;
        }
        p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        .btn {
            margin-top: 20px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: #2e7d32;
            color: #fff;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: #1b5e20;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Hurrah! 🎉</h1>
        <p>The Laravel App is up and running 🚀</p>
        <button class="btn" onclick="window.location.reload()">Check Again</button>
    </div>
</body>
</html>
