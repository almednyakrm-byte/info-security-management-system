**list_مراقبة-الوصول.php**

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
    <title>مراقبة الوصول</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مراقبة الوصول</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مراقبة-الوصول.php'">إضافة عنصر جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>العنصر</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مراقبة-الوصول.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsContainer.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.العنصر}</td>
                                <td>
                                    <a href="edit_مراقبة-الوصول.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                                    <button class="text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsContainer.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/مراقبة-الوصول.php')
                .then(response => response.json())
                .then(data => {
                    recordsContainer.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.العنصر}</td>
                            <td>
                                <a href="edit_مراقبة-الوصول.php?id=${record.id}" class="text-indigo-500">تعديل</a>
                                <button class="text-red-500" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsContainer.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف العنصر؟')) {
                fetch('../backend/مراقبة-الوصول.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        loadRecords();
    </script>
</body>
</html>

**backend/مراقبة-الوصول.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM table_name WHERE العنصر LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM table_name";
}

// Fetch records
$result = $conn->query($query);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// JSON output
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM table_name WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}
?>