**create_التقارير-الأمنية.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    // Check if fields are empty
    if (empty($name) || empty($description) || empty($date) || empty($time) || empty($location)) {
        $error = 'Please fill all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO التقارير_الأمنية (name, description, date, time, location) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $description, $date, $time, $location);
        $stmt->execute();

        // Redirect to list page
        header('Location: list_التقارير-الأمنية.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create report form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create Report</h2>
    <form id="create-report-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-slate-900 text-sm font-bold mb-2">Date:</label>
            <input type="date" id="date" name="date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="time" class="block text-slate-900 text-sm font-bold mb-2">Time:</label>
            <input type="time" id="time" name="time" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="location" class="block text-slate-900 text-sm font-bold mb-2">Location:</label>
            <input type="text" id="location" name="location" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Report</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_التقارير-الأمنية.js**
javascript
// Get form element
const form = document.getElementById('create-report-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to server
    fetch('../backend/التقارير-الأمنية.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        // Redirect to list page on success
        window.location.href = 'list_التقارير-الأمنية.php';
    })
    .catch((error) => {
        console.error(error);
    });
});


**../backend/التقارير-الأمنية.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    // Insert data into database
    $sql = "INSERT INTO التقارير_الأمنية (name, description, date, time, location) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $description, $date, $time, $location);
    $stmt->execute();

    // Return success message
    echo json_encode(['success' => true]);
} else {
    // Return error message
    echo json_encode(['error' => 'Invalid request']);
}