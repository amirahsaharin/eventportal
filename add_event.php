<?php
include 'db.php';
session_start();

// Prevent browser back after logout
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'organizer') {
    die("Access denied. <a href='login.php'>Login</a>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Event</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            padding: 40px;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007acc;
            margin-bottom: 25px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: #007acc;
            color: white;
            border: none;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005b99;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007acc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: #005b99;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
    <h2>Add New Event</h2>

    <form method="POST">
        <label>Event Name:</label>
        <input name="name" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Location:</label>
        <input name="location" required>

        <label>Date:</label>
        <input type="date" name="event_date" required>

        <label>Capacity:</label>
        <input type="number" name="capacity" min="1" required>

        <input type="submit" value="Add Event">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $date = $_POST['event_date'];
        $capacity = $_POST['capacity'];
        $organizer_id = $_SESSION['user']['id'];

        $stmt = $conn->prepare("INSERT INTO events (name, description, location, event_date, capacity, organizer_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $name, $description, $location, $date, $capacity, $organizer_id);

        if ($stmt->execute()) {
            echo "<p class='message success'>✅ Event added successfully!</p>";
        } else {
            echo "<p class='message error'>❌ Failed to add event: " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</div>

</body>
</html>
