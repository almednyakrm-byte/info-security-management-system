**create_مراقبة-الوصول.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (empty($name) || empty($description) || empty($date) || empty($time)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مراقبة_الوصول (name, description, date, time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $description, $date, $time);
        if ($stmt->execute()) {
            // Redirect back to list page
            header('Location: list_مراقبة-الوصول.php');
            exit;
        } else {
            $error = 'Failed to create record';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<!-- Create record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create مراقبة الوصول Record</h2>
    <form action="" method="post" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>
        <div class="flex justify-between">
            <div>
                <label for="date" class="block text-sm font-medium text-slate-900">Date:</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <div>
                <label for="time" class="block text-sm font-medium text-slate-900">Time:</label>
                <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
        </div>
        <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Record</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_مراقبة-الوصول.js**
javascript
// Get form elements
const form = document.querySelector('form');
const submitButton = document.querySelector('button[type="submit"]');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();
    // Get form data
    const formData = new FormData(form);
    // Send AJAX request to server
    fetch('../backend/مراقبة-الوصول.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_مراقبة-الوصول.php';
        } else {
            // Display error message
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('bg-red-100', 'border', 'border-red-400', 'text-red-700', 'px-4', 'py-3', 'rounded-lg');
            errorMessage.textContent = data.message;
            form.appendChild(errorMessage);
        }
    })
    .catch((error) => console.error(error));
});


**مراقبة-الوصول.php (backend)**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (empty($name) || empty($description) || empty($date) || empty($time)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
        exit;
    } else {
        // Insert data into database
        $sql = "INSERT INTO مراقبة_الوصول (name, description, date, time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $description, $date, $time);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create record']);
            exit;
        }
    }
}