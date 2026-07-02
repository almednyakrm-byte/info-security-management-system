**edit_التقارير-الأمنية.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/التقارير-الأمنية.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    $title = $data['title'];
    $description = $data['description'];
    $status = $data['status'];
} else {
    header('Location: list_التقارير-الأمنية.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل تقرير أمني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">تعديل تقرير أمني</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-900">عنوان التقرير</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $title ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">وصف التقرير</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?= $description ?></textarea>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-900">حالة التقرير</label>
                <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>نشط</option>
                    <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/التقارير-الأمنية.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_التقارير-الأمنية.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/التقارير-الأمنية.php**

<?php
// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'title' => 'تقرير أمني',
    'description' => 'وصف التقرير',
    'status' => 'active'
);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);