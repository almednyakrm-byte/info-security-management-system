**list_access-control.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Control</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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
            background-color: #fff;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table th, .table td {
            text-align: left;
        }
        .table th {
            font-weight: bold;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
            width: 100%;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem #ccc;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500 font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold text-slate-900">Access Control</h2>
            <a href="create_access-control.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search...">
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const tableBody = document.getElementById('table-body');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const tableRows = tableBody.children;
            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const cells = row.children;
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchQuery)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        fetch('../backend/access-control.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>
                            <a href="edit_access-control.php?id=${item.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteItem(${item.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        function deleteItem(id) {
            fetch(`../backend/access-control.php?id=${id}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Item deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting item!');
                    }
                })
                .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**access-control.php (backend)**

<?php
// Database connection code here
// ...

// Retrieve all records from the database
$records = array();
$query = "SELECT * FROM access_control";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// Return the records as JSON
header('Content-Type: application/json');
echo json_encode($records);
?>

Note: This is a basic implementation and you should adapt it to your specific requirements and database schema. Additionally, you should ensure that the backend code is secure and follows best practices for database interactions.