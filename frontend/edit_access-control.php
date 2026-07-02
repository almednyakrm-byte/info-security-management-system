**edit_access-control.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/access-control.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit Access Control';
$modSlug = 'access-control';

// Include header and navigation
include '../includes/header.php';
include '../includes/navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-access-control-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $existingRecord['description'] ?></textarea>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Save Changes</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/access-control.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-access-control-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/access-control.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': <?= csrf_token() ?>,
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_' + <?= $modSlug ?> + '.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });

    // CSRF token function
    function csrf_token() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        return token;
    }
</script>

<?php
// Include footer
include '../includes/footer.php';
?>


**access-control.php (backend)**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    exit;
}

// Fetch existing record details from database
$existingRecord = get_record($id);

// Return existing record details as JSON
echo json_encode($existingRecord);

// Function to get record from database
function get_record($id) {
    // Database connection code here
    // ...
    // Return record details
    return array(
        'id' => $id,
        'name' => 'Existing Record Name',
        'description' => 'Existing Record Description',
    );
}
?>


**list_access-control.php (example)**

<?php
// Include header and navigation
include '../includes/header.php';
include '../includes/navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Access Control List</h1>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Description</th>
                <th scope="col" class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Table rows here -->
        </tbody>
    </table>
</div>

<?php
// Include footer
include '../includes/footer.php';
?>


Note: This code assumes you have a `csrf_token()` function in your backend to generate a CSRF token, and a `get_record()` function to fetch the existing record details from the database. You should replace these with your actual implementation.