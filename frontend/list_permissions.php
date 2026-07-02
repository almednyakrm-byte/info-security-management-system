**list_permissions.php**

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
    <title>Permissions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23 !important;
        }
        .text-indigo-500 {
            color: #6B5CF2 !important;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-indigo-500 hover:text-white">Back to Index</a>
            <div class="flex items-center">
                <span class="text-sm font-bold mr-2">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-indigo-500 hover:text-white">Logout</a>
            </div>
        </header>
        <main class="bg-white rounded-lg p-4 shadow-md">
            <h2 class="text-lg font-bold mb-2">Permissions</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_permissions.php'">Add New Item</button>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchPermissions()">Search</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="permissions-list">
                    <?php
                    // Fetch permissions list from backend
                    $response = json_decode(file_get_contents('../backend/permissions.php'), true);
                    foreach ($response as $permission) {
                        echo '<tr>';
                        echo '<td class="px-4 py-2">' . $permission['id'] . '</td>';
                        echo '<td class="px-4 py-2">' . $permission['name'] . '</td>';
                        echo '<td class="px-4 py-2">';
                        echo '<a href="edit_permissions.php?id=' . $permission['id'] . '" class="text-indigo-500 hover:text-white">Edit</a>';
                        echo '<button class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deletePermission(' . $permission['id'] . ')">Delete</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        function searchPermissions() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/permissions.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const permissionsList = document.getElementById('permissions-list');
                        permissionsList.innerHTML = '';
                        data.forEach(permission => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${permission.id}</td>
                                <td class="px-4 py-2">${permission.name}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_permissions.php?id=${permission.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                    <button class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deletePermission(${permission.id})">Delete</button>
                                </td>
                            `;
                            permissionsList.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/permissions.php')
                    .then(response => response.json())
                    .then(data => {
                        const permissionsList = document.getElementById('permissions-list');
                        permissionsList.innerHTML = '';
                        data.forEach(permission => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${permission.id}</td>
                                <td class="px-4 py-2">${permission.name}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_permissions.php?id=${permission.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                    <button class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deletePermission(${permission.id})">Delete</button>
                                </td>
                            `;
                            permissionsList.appendChild(row);
                        });
                    });
            }
        }

        function deletePermission(id) {
            if (confirm('Are you sure you want to delete this permission?')) {
                fetch('../backend/permissions.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Permission deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting permission!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**permissions.php (backend)**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch permissions list
$query = "SELECT * FROM permissions";
$result = mysqli_query($conn, $query);

$permissions = array();
while ($row = mysqli_fetch_assoc($result)) {
    $permissions[] = $row;
}

// Search permissions
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM permissions WHERE name LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $query);
    $permissions = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $permissions[] = $row;
    }
}

// Delete permission
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM permissions WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo json_encode(array('success' => true));
}

// Output permissions list
echo json_encode($permissions);
?>

Note: This code assumes you have a `permissions` table in your database with columns `id` and `name`. You'll need to modify the database connection and query to match your actual database schema. Additionally, this code uses a simple search query that may not be efficient for large datasets. You may want to consider using a more robust search solution.