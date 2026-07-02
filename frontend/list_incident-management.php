<!-- list_incident-management.php -->

<?php
session_start();

// Redirect to login.php if not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .table td {
            color: #333;
        }
        .table tr:hover {
            background-color: #f5f5f5;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">Incident Management</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_incident-management.php'">Add New Item</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" placeholder="Search..." id="search-input">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchIncident()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Incident Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="incident-list">
                <!-- List of incidents will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch incident list from backend
        async function fetchIncidentList() {
            try {
                const response = await fetch('../backend/incident-management.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                const incidentList = document.getElementById('incident-list');
                data.forEach((incident) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${incident.id}</td>
                        <td>${incident.incident_name}</td>
                        <td>
                            <a href="edit_incident-management.php?id=${incident.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteIncident(${incident.id})">Delete</button>
                        </td>
                    `;
                    incidentList.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search incident list
        function searchIncident() {
            const searchInput = document.getElementById('search-input').value;
            const incidentList = document.getElementById('incident-list');
            const rows = incidentList.children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const incidentName = row.children[1].textContent;
                if (incidentName.toLowerCase().includes(searchInput.toLowerCase())) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Delete incident
        async function deleteIncident(id) {
            try {
                const response = await fetch('../backend/incident-management.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    const incidentList = document.getElementById('incident-list');
                    const rows = incidentList.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const incidentId = row.children[0].textContent;
                        if (incidentId === id.toString()) {
                            incidentList.removeChild(row);
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting incident');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Fetch incident list on page load
        fetchIncidentList();
    </script>
</body>
</html>



// backend/incident-management.php

<?php
// Database connection settings
$host = 'localhost';
$dbname = 'incident_management';
$username = 'your_username';
$password = 'your_password';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch incident list
$query = "SELECT * FROM incidents";
$result = $conn->query($query);

// Convert result to JSON
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($data);

// Delete incident
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    $query = "DELETE FROM incidents WHERE id = $id";
    $conn->query($query);
    http_response_code(200);
}

// Close connection
$conn->close();
?>