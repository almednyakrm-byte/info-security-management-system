**list_ثوابت-الأمان.php**

<?php
// Session validation
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
    <title>ثوابت الأمان</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #f7f7f7;
            padding: 1rem;
            text-align: center;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1f2937;
            color: #f7f7f7;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold text-slate-900">الرئيسية</a>
            <div class="flex items-center">
                <p class="text-lg font-bold text-slate-900"><?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-lg font-bold text-indigo-500 ml-4">تسجيل الخروج</a>
            </div>
        </nav>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">ثوابت الأمان</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_ثوابت-الأمان.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وصف</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/ثوابت-الأمان.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.name}</td>
                        <td>${record.description}</td>
                        <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                        <td><a href="edit_ثوابت-الأمان.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                const records = document.getElementById('records');
                records.innerHTML = '';
                fetchRecords(searchValue);
            } else {
                fetchRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/ثوابت-الأمان.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        fetchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Fetch records on page load
        fetchRecords();
    </script>
</body>
</html>

**backend/ثوابت-الأمان.php**

<?php
// Get records from database
$records = array(
    array('id' => 1, 'name' => 'سجل 1', 'description' => 'وصف سجل 1'),
    array('id' => 2, 'name' => 'سجل 2', 'description' => 'وصف سجل 2'),
    array('id' => 3, 'name' => 'سجل 3', 'description' => 'وصف سجل 3')
);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo 'Record deleted successfully';
    exit;
}
?>