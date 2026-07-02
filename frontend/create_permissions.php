**create_permissions.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Create Permissions Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create Permissions</h2>
    <form id="create-permissions-form">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
</div>

<!-- Include JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-permissions-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/permissions.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_permissions.php';
                    } else {
                        alert('Error creating permissions');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**permissions.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = "INSERT INTO permissions (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating permissions';
    }
}
?>


**db.php (database connection)**

<?php
// Database connection settings
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>