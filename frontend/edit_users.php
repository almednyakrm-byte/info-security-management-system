**edit_users.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get user ID from URL
$id = $_GET['id'];

// Validate user ID
if (empty($id) || !is_numeric($id)) {
    header('Location: users.php');
    exit;
}

// Fetch existing record details via AJAX
$js = '
<script>
    $(document).ready(function() {
        $.get("../backend/users.php?id=' . $id . '")
            .done(function(data) {
                var user = JSON.parse(data);
                $("#username").val(user.username);
                $("#email").val(user.email);
                $("#role").val(user.role);
            })
            .fail(function() {
                alert("Error fetching user details");
            });
    });
</script>
';

// Include JavaScript library
echo $js;

?>

<!-- Edit User Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit User</h2>
    <form id="edit-user-form" class="space-y-4">
        <div>
            <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
            <input type="text" id="username" name="username" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-slate-900">Role</label>
            <select id="role" name="role" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
                <option value="user">User</option>
            </select>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700">Update User</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#edit-user-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: '../backend/users.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_users.php';
                },
                error: function(xhr, status, error) {
                    alert("Error updating user");
                }
            });
        });
    });
</script>

**Note:** This code assumes you have jQuery and a JavaScript library like jQuery UI or Tailwind CSS installed. You'll need to include the necessary CSS and JavaScript files in your HTML file for this code to work.