**edit_مراقبة-الوصول.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/مراقبة-الوصول.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مراقبة الوصول</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-indigo-500 mb-4">تعديل مراقبة الوصول</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المراقب</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['phone'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['email'] ?>">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مراقبة-الوصول.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مراقبة-الوصول.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'name' => 'اسم المراقب',
    'phone' => 'رقم الهاتف',
    'email' => 'البريد الإلكتروني'
);

// Return JSON response
echo json_encode($existingRecord);


Note: This code assumes that you have a `backend/مراقبة-الوصول.php` file that handles the PUT request and updates the existing record. You will need to modify this file to suit your needs.