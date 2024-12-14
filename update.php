<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "lab_5b";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error/success message
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $matric = $_POST['matric'];
    $password = $_POST['password'];
    $accessLevel = $_POST['accessLevel'];

    // Validate input fields
    if (empty($name) || empty($matric) || empty($password) || empty($accessLevel)) {
        $message = "All fields are required!";
    } else {
        // Prepare SQL query to update user data
        $update_sql = "UPDATE users SET name = ?, password = ?, accessLevel = ? WHERE matric = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssss", $name, $password, $accessLevel, $matric);

        // Execute the query and check if successful
        if ($stmt->execute()) {
            // Redirect to display page with a success message
            header("Location: display.php?success=User updated successfully");
            exit;
        } else {
            $message = "Failed to update user. Please try again.";
        }
    }
}

// Close the database connection
$conn->close();
?>
