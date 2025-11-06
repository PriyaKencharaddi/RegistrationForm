<?php
// Include database connection
include 'db.php';

// Check if delete_id is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get file names to delete from server
    $result = $conn->query("SELECT profile_image, resume_file FROM users WHERE id=$id");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Delete profile image if exists
        if ($user['profile_image'] && file_exists('uploads/' . $user['profile_image'])) {
            unlink('uploads/' . $user['profile_image']);
        }

        // Delete resume/file if exists
        if ($user['resume_file'] && file_exists('uploads/' . $user['resume_file'])) {
            unlink('uploads/' . $user['resume_file']);
        }

        // Delete user from database
        $conn->query("DELETE FROM users WHERE id=$id");
    }
}

// Redirect back to users list
header("Location: users.php");
exit;
?>
