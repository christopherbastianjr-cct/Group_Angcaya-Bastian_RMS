<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "gms");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No such user!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background-image: linear-gradient(to right,rgb(255, 255, 255), rgb(168, 227, 255));
        }
        .logoFrame{
            padding-right: 0px;
            margin-right:0px;
            width: 25%;
            font-size: 20px;
        }
        .logo {
            width: 45%;
            height: 45%;
            position: relative;
            left: 28%;
        }
        .vl {
            border-left: 2px solid;
            height: 400px;
            margin-left: 1%;
            margin-right: 5%;
            color:rgb(6, 67, 102);
        }
        form {
            padding-left: 3%;   
        }
        button {
            margin-left:3%;
            background-color:rgb(28, 131, 190);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="logoFrame">
        <img src="logo.png" alt="company logo" class="logo">
        <h2>Local Doctors Hospital</h2>
        <span align="center"><h4>Patient Records System</h4></span>    
    </div>

    <div class="vl"></div>    

    <div class="formFrame">
        <form method="post" action="">
        
            <label>Username</label><br>
            <input type="text" name="username" required><br>

            <label>Password</label><br>
            <input type="password" name="password" required>
        
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <button type="submit">Login</button>
            
        </form>
    </div>
</body>
</html>
