<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            width: 350px;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007acc;
            margin-bottom: 25px;
        }
        label {
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007acc;
            border: none;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005b99;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST">
        <label>Email:</label>
        <input name="email" type="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>

    <div class="link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $passwordInput = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            die("<p class='error'>Prepare failed: " . $conn->error . "</p>");
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            if (password_verify($passwordInput, $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<p class='error'>❌ Invalid password.</p>";
            }
        } else {
            echo "<p class='error'>❌ No user found with that email.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>

</body>
</html>
