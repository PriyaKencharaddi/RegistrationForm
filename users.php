<?php
// Include database connection
include 'db.php';

// Handle deletion if ?delete_id is set
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    // Get file names to delete from server
    $result = $conn->query("SELECT profile_image, resume_file FROM users WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['profile_image'] && file_exists('uploads/' . $row['profile_image'])) {
            unlink('uploads/' . $row['profile_image']);
        }
        if ($row['resume_file'] && file_exists('uploads/' . $row['resume_file'])) {
            unlink('uploads/' . $row['resume_file']);
        }
    }

    // Delete from database
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Registered Users</h2>
    <a href="index.html" class="btn btn-primary mb-3">Add New User</a>
   


    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>City</th>
            <th>Country</th>
            <th>Hobbies</th>
            <th>LinkedIn</th>
            <th>GitHub</th>
            <th>Profile Image</th>
            <th>Resume/File</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($user = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$user['id']}</td>
                    <td>{$user['full_name']}</td>
                    <td>{$user['email']}</td>
                    <td>{$user['phone']}</td>
                    <td>{$user['dob']}</td>
                    <td>{$user['gender']}</td>
                    <td>{$user['city']}</td>
                    <td>{$user['country']}</td>
                    <td>{$user['hobbies']}</td>
                    <td><a href='{$user['linkedin']}' target='_blank'>LinkedIn</a></td>
                    <td><a href='{$user['github']}' target='_blank'>GitHub</a></td>
                    <td>";
                if ($user['profile_image'] && file_exists('uploads/' . $user['profile_image'])) {
                    echo "<img src='uploads/{$user['profile_image']}' width='60' height='60' style='object-fit:cover;'>";
                } else {
                    echo "N/A";
                }
                echo "</td>
                    <td>";
                if ($user['resume_file'] && file_exists('uploads/' . $user['resume_file'])) {
                    echo "<a href='uploads/{$user['resume_file']}' target='_blank'>Download</a>";
                } else {
                    echo "N/A";
                }
                echo "</td>
                    <td>
                        <a href='edit_user.php?id={$user['id']}' class='btn btn-sm btn-warning mb-1'>Edit</a>
                        <a href='users.php?delete_id={$user['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='14' class='text-center'>No users found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
