<?php
include 'db.php';
session_start();

// Only organizers are allowed to delete events
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'organizer') {
    die("Access denied. Only organizers can delete events.");
}

// Check if event ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Invalid request. Event ID is missing.");
}

$event_id = intval($_GET['id']);
$organizer_id = $_SESSION['user']['id'];

// STEP 1: Verify the event belongs to the logged-in organizer
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ? AND organizer_id = ?");
$stmt->bind_param("ii", $event_id, $organizer_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("❌ Event not found or you're not authorized to delete this event.");
}

// STEP 2: Delete the event
$delete_stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$delete_stmt->bind_param("i", $event_id);

if ($delete_stmt->execute()) {
    // Redirect back to view_events.php after successful deletion
    header("Location: view_events.php");
    exit();
} else {
    echo "<p style='color:red;'>❌ Failed to delete event: " . $delete_stmt->error . "</p>";
}

$delete_stmt->close();
$conn->close();
?>
