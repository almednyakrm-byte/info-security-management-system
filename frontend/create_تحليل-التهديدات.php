<?php
// create_تحليل-التهديدات.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../backend/connection.php';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة تحليل التهديدات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 lg:p-10 mt-10 bg-slate-100 rounded-xl shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">إضافة تحليل التهديدات</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="threat_name" class="block text-sm font-medium text-slate-900">اسم التهديد</label>
                <input type="text" id="threat_name" name="threat_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="threat_description" class="block text-sm font-medium text-slate-900">وصف التهديد</label>
                <textarea id="threat_description" name="threat_description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="threat_level" class="block text-sm font-medium text-slate-900">مستوى التهديد</label>
                <select id="threat_level" name="threat_level" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="low">منخفض</option>
                    <option value="medium">متوسط</option>
                    <option value="high">مرتفع</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="threat_source" class="block text-sm font-medium text-slate-900">مصدر التهديد</label>
                <input type="text" id="threat_source" name="threat_source" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/تحليل-التهديدات.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_تحليل-التهديدات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>