**create_ثوابت-الأمان.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $value = trim($_POST['value']);

    if (empty($name) || empty($description) || empty($value)) {
        $error = 'Please fill all required fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO ثوابت_الأمان (name, description, value) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $name, $description, $value);
        if ($stmt->execute()) {
            $success = 'ثوابت الأمان added successfully';
            header('Location: list_ثوابت-الأمان.php');
            exit;
        } else {
            $error = 'Failed to add ثوابت الأمان';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Add ثوابت الأمان</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="value" class="block text-slate-900 text-sm font-bold mb-2">Value:</label>
                <input type="text" id="value" name="value" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Add ثوابت الأمان</button>
        </form>
        <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mt-4" role="alert">
                <?= $error ?>
            </div>
        <?php elseif (isset($success)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mt-4" role="alert">
                <?= $success ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_ثوابت-الأمان.js**
javascript
$(document).ready(function() {
    $('#create-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/ثوابت-الأمان.php',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = 'list_ثوابت-الأمان.php';
                } else {
                    $('#error-message').html(response.error).show();
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });
    });
});


**Note:** Make sure to replace `../backend/ثوابت-الأمان.php` with the actual PHP file that handles the form submission and database insertion. Also, ensure that the `list_ثوابت-الأمان.php` file is correctly configured to handle the redirect after a successful submission.