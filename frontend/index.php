<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة أمان المعلومات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900 text-white">
        <h1 class="text-3xl font-bold">نظام إدارة أمان المعلومات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <h1 class="text-2xl font-bold">مرحباً <?= $_SESSION['username'] ?></h1>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold">إحصائيات النظام</h2>
                <div id="stats-grid"></div>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold">إدارة ثوابت الأمان</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='security-policies.php'">إدارة ثوابت الأمان</button>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold">مراقبة الوصول</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='access-control.php'">مراقبة الوصول</button>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold">تحليل التهديدات</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='threat-analysis.php'">تحليل التهديدات</button>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-lg font-bold">تقارير الأمنية</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='security-reports.php'">تقارير الأمنية</button>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statElement = document.createElement('div');
                    statElement.innerHTML = `
                        <h3 class="text-lg font-bold">${stat.title}</h3>
                        <p class="text-lg">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statElement);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes that you have a PHP backend that handles the API calls and returns the stats data in JSON format. You will need to create the API endpoints and the backend logic to fetch the stats data.

Also, this code uses the `fetch` API to make a GET request to the `/api/stats` endpoint to fetch the stats data. You can replace this with your own API call method if needed.

Make sure to update the `logout.php` and other link URLs to match your actual file paths.