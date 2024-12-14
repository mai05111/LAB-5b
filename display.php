<?php


if (isset($_GET['success'])) {
    echo "<p style='color: green; text-align: center;'>" . htmlspecialchars($_GET['success']) . "</p>";
}


// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "lab_5b"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all users
$sql = "SELECT matric, name, accessLevel FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Table</title>
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: blue;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Users Table</h2>

    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Access Level</th>
            <th>Action</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['matric']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['accessLevel']) . "</td>";
                echo "<td>";
                echo "<a href='update_form.php?matric=" . urlencode($row['matric']) . "'>Update</a>";
                echo " | ";
                echo "<a href='delete.php?matric=" . urlencode($row['matric']) . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
