<?php
// Include database connection
include 'db.php';

// Get user ID from URL
$id = $_GET['id'] ?? '';

if (!$id) {
    echo "Invalid user ID.";
    exit;
}

// Fetch existing user data
$sql = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $country = $_POST['country'] ?? '';
    $hobbies = $_POST['hobbies'] ?? '';
    $linkedin = $_POST['Linkedin link'] ?? '';
    $github = $_POST['Github link'] ?? '';

    // Upload directory
    $uploadDir = 'uploads/';

    // Handle profile image
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = $_FILES['profile_image']['name'];
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $profile_image);
    } else {
        $profile_image = $user['profile_image']; // keep existing if no new upload
    }

    // Handle resume file
    if (!empty($_FILES['resume_file']['name'])) {
        $resume_file = $_FILES['resume_file']['name'];
        move_uploaded_file($_FILES['resume_file']['tmp_name'], $uploadDir . $resume_file);
    } else {
        $resume_file = $user['resume_file']; // keep existing if no new upload
    }

    // Update user in database
    $update_sql = "UPDATE users SET 
        full_name='$full_name', email='$email', password='$password', phone='$phone',
        dob='$dob', gender='$gender', address='$address', city='$city', state='$state', 
        country='$country', hobbies='$hobbies', linkedin='$linkedin', github='$github',
        profile_image='$profile_image', resume_file='$resume_file' 
        WHERE id=$id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<div style='text-align:center; margin-top:50px;'>
                <h3>User Updated Successfully!</h3>
                <a href='users.php' class='btn btn-success' style='font-size:18px;'>Back to Users</a>
              </div>";
        exit;
    } else {
        echo "<div style='text-align:center; margin-top:50px; color:red;'>
                <h4>Error: " . $conn->error . "</h4>
              </div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit User</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control" value="<?= $user['dob'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Select</option>
                <option value="Male" <?= $user['gender']=='Male'?'selected':'' ?>>Male</option>
                <option value="Female" <?= $user['gender']=='Female'?'selected':'' ?>>Female</option>
                <option value="Other" <?= $user['gender']=='Other'?'selected':'' ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['city']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($user['state']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['country']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Hobbies</label>
            <input type="text" name="hobbies" class="form-control" value="<?= htmlspecialchars($user['hobbies']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Image</label>
            <input type="file" name="profile_image" class="form-control">
            <?php if($user['profile_image']) echo "<p>Current: {$user['profile_image']}</p>"; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Resume / File (Image, PDF, DOCX)</label>
            <input type="file" name="resume_file" class="form-control">
            <?php if($user['resume_file']) echo "<p>Current: {$user['resume_file']}</p>"; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Linkedin link</label>
            <input type="url" name="Linkedin link" class="form-control" value="<?= htmlspecialchars($user['linkedin']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Github link</label>
            <input type="url" name="Github link" class="form-control" value="<?= htmlspecialchars($user['github']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
