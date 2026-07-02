**edit_permissions.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/permissions.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set page title and mod slug
$page_title = 'Edit Permission';
$mod_slug = 'permissions';

// Include header and navigation
include_once 'header.php';
include_once 'nav.php';
?>

<!-- Main content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-permission-form" class="bg-white rounded shadow-md p-6">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"><?= $data['description'] ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Permission</button>
    </form>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Populate form fields
        $.ajax({
            type: 'GET',
            url: '../backend/permissions.php?id=<?= $id ?>',
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#description').val(data.description);
            }
        });

        // Submit form via AJAX
        $('#edit-permission-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/permissions.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'list_<?= $mod_slug ?>.php';
                    } else {
                        alert('Error updating permission');
                    }
                }
            });
        });
    });
</script>

<!-- Footer -->
<?php include_once 'footer.php'; ?>


**permissions.php (backend)**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM permissions WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Update record via PUT
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $query = "UPDATE permissions SET name = '$name', description = '$description' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    // Fetch existing record details
    echo json_encode($data);
}
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include_once 'nav.php'; ?>


**nav.php**

<nav class="bg-white shadow-md p-4">
    <ul class="flex justify-between items-center">
        <li><a href="list_<?= $mod_slug ?>.php" class="text-sm font-medium text-slate-700 hover:text-indigo-500">Back to List</a></li>
        <li><a href="create_<?= $mod_slug ?>.php" class="text-sm font-medium text-slate-700 hover:text-indigo-500">Create New <?= $mod_slug ?></a></li>
    </ul>
</nav>


**footer.php**

<footer class="bg-white shadow-md p-4">
    <p class="text-sm text-slate-700">&copy; <?= date('Y') ?></p>
</footer>