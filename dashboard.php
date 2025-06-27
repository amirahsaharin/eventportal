<?php
session_start();

// Prevent browser back after logout
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            color: #333;
            padding: 40px;
        }
        .dashboard {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #007acc;
        }
        .btn {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #007acc;
            color: white;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #005b99;
        }
        .logout {
            margin-top: 20px;
            background-color: #cc0000;
        }
        .logout:hover {
            background-color: #990000;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($user['full_name']) ?> <br> (<?= htmlspecialchars($user['role']) ?>)</h2>

        <?php if ($user['role'] === 'organizer'): ?>
            <a href="add_event.php" class="btn">➕ Add Event</a>
            <a href="view_events.php" class="btn">🗂 Manage Events</a>
            <a href="view_attendees.php" class="btn">👥 View Attendees</a>
        <?php else: ?>
            <a href="view_events.php" class="btn">📅 Browse Events</a>
            <a href="my_registrations.php" class="btn">✅ My Registrations</a>
        <?php endif; ?>

        <br>
        <a href="logout.php" class="btn logout">🚪 Logout</a>
    </div>
</body>
</html>
