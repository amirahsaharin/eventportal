<?php
include 'db.php';
session_start();

// Prevent browser back after logout
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// ✅ Inline registration handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_event_id'])) {
    $event_id = intval($_POST['register_event_id']);
    $user_id = $user['id'];

    // Check if already registered
    $check_stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<p style='color:orange;'>⚠️ You have already registered for this event.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $event_id);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>✅ Successfully registered!</p>";
        } else {
            echo "<p style='color:red;'>❌ Registration failed: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

    $check_stmt->close();
}

// ✅ Fetch all events
$result = $conn->query("SELECT * FROM events");

echo "<h2>Event List</h2>";
echo "<table border='1' cellpadding='8'>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['event_date']) . "</td>
            <td>" . htmlspecialchars($row['location']) . "</td>
            <td>";

    if ($user['role'] == 'organizer') {
        echo "<a href='edit_event.php?id={$row['id']}'>Edit</a> | 
              <a href='delete_event.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to delete this event?');\">Delete</a>";
    } else {
        // ✅ Inline form with confirmation popup
        echo "<form method='POST' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to register for this event?');\">
                <input type='hidden' name='register_event_id' value='{$row['id']}'>
                <button type='submit'>Register</button>
              </form>";
    }

    echo "</td></tr>";
}

echo "</table>";

$conn->close();
?>

<!-- Back to dashboard -->
<p><a href="dashboard.php" style="text-decoration:none; color:white; background-color:#007bff; padding:8px 12px; border-radius:4px;">⬅ Back to Dashboard</a></p>
