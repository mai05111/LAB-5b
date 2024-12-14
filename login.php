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

// Initialize error message
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Check for empty inputs
    if (empty($matric) || empty($password)) {
        $error_message = "Please enter both Matric and Password.";
    } else {
        // Query to check the credentials
        $sql = "SELECT * FROM users WHERE matric = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $matric, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Successful login
            $_SESSION['matric'] = $matric;
            header("Location: display.php"); // Redirect to display page
            exit();
        } else {
            // Invalid credentials
            $error_message = "Invalid username or password, try <a href='login.php'>login</a> again.";
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
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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
            text-align: center;
        }
        input[type="text"], input[type="password"] {
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
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            color: purple;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="matric">Matric:</label><br>
            <input type="text" name="matric" id="matric" required><br>

            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br>

            <input type="submit" value="Login">
        </form>
        <p> <a href="register.php">Register</a> here if you have not.</p>
    </div>
</body>
</html>
