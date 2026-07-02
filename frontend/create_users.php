**create_users.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include_once 'header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include_once 'navigation.php';
?>

<div class="container mx-auto p-4 mt-12">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create User</h2>
        <form id="create-user-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-slate-900 text-sm font-bold mb-2">Role</label>
                <select id="role" name="role" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="moderator">Moderator</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create User</button>
        </form>
    </div>
</div>

<?php
// Include footer
include_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-user-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/users.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_users.php';
                    } else {
                        alert('Error creating user');
                    }
                }
            });
        });
    });
</script>


**users.php (backend)**

<?php
// Include database connection
include_once 'db.php';

// Check if form data is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashedPassword', '$role')";
    $result = mysqli_query($conn, $query);

    // Check if user is created successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating user';
    }
}
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
</head>
<body>
    <!-- Navigation and content will be included here -->
</body>
</html>


**footer.php**

</body>
</html>


**navigation.php**

<nav class="bg-white shadow-md p-4">
    <!-- Navigation links will be included here -->
</nav>