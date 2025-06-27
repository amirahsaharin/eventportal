<?php
include 'db.php';
session_start();

// Prevent browser back after logout
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'attendee') {
    die("Access denied. Only attendees can view their registrations.");
}

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("
    SELECT e.name, e.location, e.event_date 
    FROM registrations r 
    JOIN events e ON r.event_id = e.id 
    WHERE r.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Registered Events</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #007acc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #007acc;
            color: white;
        }
        tr:hover {
            background-color: #f2f9ff;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #666;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            background-color: #007acc;
            color: white;
            padding: 10px 16px;
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

<h2>My Registered Events</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Location</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['event_date']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p class="message">⚠️ You have not registered for any events yet.</p>
<?php endif; ?>

<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
