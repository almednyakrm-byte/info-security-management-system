**create_incident-management.php**

<?php
// Session validation
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create Incident Management</h2>
        <form id="incident-management-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="incident_title">Title</label>
                    <input class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="incident_title" type="text" placeholder="Incident Title">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="incident_description">Description</label>
                    <textarea class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="incident_description" rows="4" placeholder="Incident Description"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="incident_category">Category</label>
                    <select class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="incident_category">
                        <option value="">Select Category</option>
                        <option value="Network">Network</option>
                        <option value="Server">Server</option>
                        <option value="Database">Database</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="incident_priority">Priority</label>
                    <select class="appearance-none block w-full bg-white text-slate-900 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="incident_priority">
                        <option value="">Select Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Incident Management</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#incident-management-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/incident-management.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_incident-management.php';
                    } else {
                        alert('Error creating incident management');
                    }
                }
            });
        });
    });
</script>


**incident-management.php (backend)**

<?php
// Include database connection
include 'db_connection.php';

// Check if form data is submitted
if (isset($_POST['incident_title']) && isset($_POST['incident_description']) && isset($_POST['incident_category']) && isset($_POST['incident_priority'])) {
    // Prepare SQL query
    $query = "INSERT INTO incident_management (title, description, category, priority) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $_POST['incident_title'], $_POST['incident_description'], $_POST['incident_category'], $_POST['incident_priority']);
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error creating incident management';
    }
    $stmt->close();
}
?>