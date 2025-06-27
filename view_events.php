<?php
include 'db.php';
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$message = "";

// ✅ Inline registration handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_event_id'])) {
    $event_id = intval($_POST['register_event_id']);
    $user_id = $user['id'];

    $check_stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "<p style='color:orange;'>⚠️ You have already registered for this event.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $event_id);
        if ($stmt->execute()) {
            $message = "<p style='color:green;'>✅ Successfully registered!</p>";
        } else {
            $message = "<p style='color:red;'>❌ Registration failed: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

    $check_stmt->close();
}

// ✅ Fetch all events
$result = $conn->query("SELECT * FROM events");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event List</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f8ff;
            padding: 40px;
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
        .success {
            color: green;
            font-weight: bold;
        }
        .warning {
            color: orange;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
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
    </style>
</head>
<body>

<h2>Event List</h2>

<?= $message ?>

<table>
    <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Location</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['event_date']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td>
                <?php if ($user['role'] == 'organizer'): ?>
                    <a href="edit_event.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to edit this event?');">Edit</a> |
                    <a href="delete_event.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                <?php else: ?>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to register for this event?');">
                        <input type="hidden" name="register_event_id" value="<?= $row['id'] ?>">
                        <button type="submit">Register</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

</body>
</html>

<?php $conn->close(); ?>
