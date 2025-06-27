<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'organizer') {
    die("Access denied.");
}

$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    die("Invalid event ID.");
}

// STEP 1: FETCH CURRENT EVENT DATA
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND organizer_id = ?");
$stmt->bind_param("ii", $event_id, $_SESSION['user']['id']);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found or you are not authorized to edit it.");
}

// STEP 2: HANDLE FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $location = $_POST['location'];
    $date = $_POST['event_date'];
    $capacity = $_POST['capacity'];

    $update = $conn->prepare("UPDATE events SET name = ?, description = ?, location = ?, event_date = ?, capacity = ? WHERE id = ?");
    $update->bind_param("ssssii", $name, $desc, $location, $date, $capacity, $event_id);

    if ($update->execute()) {
        echo "<p style='color:green;'>✅ Event updated successfully!</p>";
        // Optionally redirect to view_events.php
        // header("Location: view_events.php");
        // exit();
    } else {
        echo "<p style='color:red;'>❌ Update failed: " . $update->error . "</p>";
    }

    $update->close();
}
?>

<h2>Edit Event</h2>
<form method="POST">
    Name: <input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required><br>
    Description: <textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea><br>
    Location: <input name="location" value="<?= htmlspecialchars($event['location']) ?>" required><br>
    Date: <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required><br>
    Capacity: <input type="number" name="capacity" value="<?= $event['capacity'] ?>" required><br>
    <input type="submit" value="Update Event">
</form>
