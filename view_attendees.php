<?php
include 'db.php';
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'organizer') {
    die("Access denied. Only organizers can view attendees.");
}

$organizer_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("
    SELECT e.name AS event_name, u.full_name AS attendee_name, u.email 
    FROM registrations r
    JOIN events e ON r.event_id = e.id
    JOIN users u ON r.user_id = u.id
    WHERE e.organizer_id = ?
    ORDER BY e.name
");
$stmt->bind_param("i", $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Attendees</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #007acc;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
            color: #666;
            font-weight: bold;
            margin-top: 20px;
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

<h2>Event Attendees</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Event</th>
            <th>Attendee Name</th>
            <th>Email</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['event_name']) ?></td>
                <td><?= htmlspecialchars($row['attendee_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p class="message">⚠️ No attendees registered for your events yet.</p>
<?php endif; ?>

<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
