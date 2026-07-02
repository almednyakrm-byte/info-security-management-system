**edit_ثوابت-الأمان.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/ثوابت-الأمان.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set page title and content
$page_title = 'تعديل ثوابت الأمان';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 font-bold text-lg mb-4"><?= $page_title ?></h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold">اسم الثابت</label>
                <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="value" class="text-slate-900 font-bold">قيمة الثابت</label>
                <input type="text" id="value" name="value" class="w-full p-2 pl-10 text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['value'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/ثوابت-الأمان.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_ثوابت-الأمان.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/ثوابت-الأمان.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get record ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'name' => 'ثابت الأمان',
    'value' => 'قيمة الثابت'
);

// Output record details as JSON
header('Content-Type: application/json');
echo json_encode($data);