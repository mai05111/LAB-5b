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
$userData = [];

// Fetch user data if matric ID is provided via GET
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Fetch user details
    $sql = "SELECT name, matric, password, accessLevel FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc(); // Load user details
    } else {
        $message = "User not found!";
    }
}

// Process the form submission to update user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $matric = $_POST['matric'];
    $password = $_POST['password'];
    $accessLevel = $_POST['accessLevel'];

    // Validate input fields
    if (empty($name) || empty($matric) || empty($password) || empty($accessLevel)) {
        $message = "All fields are required!";
    } else {
        // Update user data
        $update_sql = "UPDATE users SET name = ?, password = ?, accessLevel = ? WHERE matric = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssss", $name, $password, $accessLevel, $matric);

        if ($stmt->execute()) {
            $message = "User updated successfully!";
        } else {
            $message = "Failed to update user. Please try again.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 300px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"], select {
            width: 90%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="update.php" method="post">
            <input type="hidden" name="matric" value="<?php echo htmlspecialchars($userData['matric'] ?? ''); ?>">
            <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>" required><br>
            <input type="password" name="password" placeholder="Password" value="<?php echo htmlspecialchars($userData['password'] ?? ''); ?>" required><br>
            <select name="accessLevel" required>
                <option value="">Select Access Level</option>
                <option value="student" <?php echo (isset($userData['accessLevel']) && $userData['accessLevel'] == 'student') ? 'selected' : ''; ?>>Student</option>
                <option value="lecturer" <?php echo (isset($userData['accessLevel']) && $userData['accessLevel'] == 'lecturer') ? 'selected' : ''; ?>>Lecturer</option>
            </select><br>
            <input type="submit" value="Update User">
        </form>
        <p><a href="display.php">Back to Users List</a></p>
    </div>
</body>
</html>
