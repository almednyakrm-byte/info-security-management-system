**edit_incident-management.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get incident ID from URL
$id = $_GET['id'];

// Fetch incident data from backend
$data = json_decode(file_get_contents('../backend/incident-management.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Incident not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Incident Management';
$mod_slug = 'incident-management';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <form id="edit-incident-management" class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="incident_name" class="block text-sm font-medium text-slate-900">Incident Name</label>
                <input type="text" id="incident_name" name="incident_name" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['incident_name'] ?>">
            </div>
            <div>
                <label for="incident_description" class="block text-sm font-medium text-slate-900">Incident Description</label>
                <textarea id="incident_description" name="incident_description" class="block w-full p-2 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"><?= $data['incident_description'] ?></textarea>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update Incident Management</button>
    </form>
</div>

<script>
    // Fetch incident data via GET
    fetch('../backend/incident-management.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('incident_name').value = data.incident_name;
            document.getElementById('incident_description').value = data.incident_description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-incident-management').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send PUT request to backend
        fetch('../backend/incident-management.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_' + '<?= $mod_slug ?>' + '.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/incident-management.php**

<?php
// Check if incident ID is set
if (!isset($_GET['id'])) {
    echo 'Error: Incident ID not set';
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    echo 'Error: Unable to connect to database';
    exit;
}

// Get incident data from database
$query = "SELECT * FROM incident_management WHERE id = " . $_GET['id'];
$result = mysqli_query($conn, $query);

// Check if incident exists
if (mysqli_num_rows($result) == 0) {
    echo 'Error: Incident not found';
    exit;
}

// Fetch incident data
$data = mysqli_fetch_assoc($result);

// Close database connection
mysqli_close($conn);

// Output incident data as JSON
echo json_encode($data);
?>