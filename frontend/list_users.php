**list_users.php**

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
    <title>Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #c5cae9;
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
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500 font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500 font-bold">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Users Management</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_users.php'">Add New Item</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" id="search-input" placeholder="Search...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchUsers()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-table">
                <?php
                // Fetch users from backend
                $response = file_get_contents('../backend/users.php');
                $users = json_decode($response, true);
                foreach ($users as $user) {
                    ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <a href="edit_users.php?id=<?php echo $user['id']; ?>" class="text-indigo-500 font-bold">Edit</a>
                            <button class="text-red-500 font-bold" onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchUsers() {
            const searchInput = document.getElementById('search-input').value;
            const usersTable = document.getElementById('users-table');
            const users = JSON.parse('<?php echo json_encode($users); ?>');
            usersTable.innerHTML = '';
            users.forEach(user => {
                if (user.name.includes(searchInput) || user.email.includes(searchInput)) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <a href="edit_users.php?id=${user.id}" class="text-indigo-500 font-bold">Edit</a>
                            <button class="text-red-500 font-bold" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    `;
                    usersTable.appendChild(row);
                }
            });
        }

        function deleteUser(id) {
            fetch('../backend/users.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting user!');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>


**users.php (backend)**

<?php
// Fetch users from database
$users = array(
    array('id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'),
    array('id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'),
    // Add more users here...
);

// Return users as JSON
header('Content-Type: application/json');
echo json_encode($users);