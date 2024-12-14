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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $matric = $_POST['matric'];
    $password = $_POST['password'];
    $accessLevel = $_POST['accessLevel']; // student or lecturer
    
    // Check for empty inputs
    if (empty($name) || empty($matric) || empty($password) || empty($accessLevel)) {
        $message = "All fields are required!";
    } else {
        // Check if username already exists
        $check_sql = "SELECT * FROM users WHERE matric = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("s", $matric);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $message = "Username already exists!";
        } else {
            // Insert new user into the database
            $insert_sql = "INSERT INTO users (name, matric, password, accessLevel) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ssss", $name, $matric, $password, $accessLevel);

            if ($stmt->execute()) {
                $message = "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                $message = "Registration failed. Please try again.";
            }
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
    <title>Register</title>
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
        <h2>Register</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="text" name="matric" placeholder="Matric ID" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="accessLevel" required>
                <option value="">Select Access Level</option>
                <option value="student">Student</option>
                <option value="lecturer">Lecturer</option>
            </select><br>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
