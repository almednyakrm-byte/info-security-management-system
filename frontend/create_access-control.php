**create_access-control.php**

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

<div class="container mx-auto p-4 pt-6 mt-6">
    <div class="bg-white rounded shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create Access Control</h2>
        <form id="access-control-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm mb-2">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-slate-900 text-sm mb-2">Status:</label>
                <select id="status" name="status" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Access Control</button>
        </form>
    </div>
</div>

<?php
// Include footer
include_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#access-control-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/access-control.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_access-control.php';
                    } else {
                        alert('Error creating access control');
                    }
                }
            });
        });
    });
</script>


**access-control.php (backend)**

<?php
// Include database connection
include_once 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status'])) {
    // Prepare SQL query
    $sql = "INSERT INTO access_control (name, description, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $_POST['name'], $_POST['description'], $_POST['status']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo 'success';
} else {
    echo 'Error creating access control';
}
?>