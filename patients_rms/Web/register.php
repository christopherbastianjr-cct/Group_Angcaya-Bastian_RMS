<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        echo "<p>✅ Registration successful. <a href='login.php'>Login here</a>.</p>";
    } else {
        echo "<p>❌ Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: linear-gradient(to right,rgb(255, 255, 255), rgb(168, 227, 255));
        }
        button {
            background-color: rgb(28, 131, 190);
            font-weight: bold;
        }
        form {
            padding-right: 3%;   
        } 
    </style>
</head>
<body>
    <form method="post">
        <h2>Register</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email (optional)">
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <a href="login.php">Already have an account? Login</a>
    </form>
</body>
</html>
