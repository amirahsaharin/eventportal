<?php
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-box {
            width: 400px;
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
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #007acc;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005b99;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Register</h2>

    <form method="POST">
        <label>Name:</label>
        <input name="full_name" required>

        <label>Email:</label>
        <input name="email" type="email" required>

        <label>Password:</label>
        <input type="password" name="password" minlength="8" required>

        <label>Role:</label>
        <select name="role" required>
            <option value="organizer">Organizer</option>
            <option value="attendee">Attendee</option>
        </select>

        <input type="submit" value="Register">
    </form>

    <div class="link">
        Already have an account? <a href="login.php">Login here</a>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            echo "<p class='success'>✅ Registered successfully!</p>";
        } else {
            echo "<p class='error'>❌ Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>

</body>
</html>
