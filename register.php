<?php
// Include database connection
include 'db.php';

// Initialize variables
$full_name = $email = $password = $phone = $dob = $gender = $address = "";
$city = $state = $country = $hobbies = $linkedin = $github = "";
$profile_image = $resume_file = "";
$errors = [];

// Upload directory
$uploadDir = 'uploads/';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $country = $_POST['country'] ?? '';
    $hobbies = $_POST['hobbies'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $github = $_POST['github'] ?? '';

    // Validation
    if (empty($full_name)) $errors[] = "Full Name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($password)) $errors[] = "Password is required.";

    // Hash password
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $profile_image = basename($_FILES['profile_image']['name']);
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $profile_image);
    }

    // Handle resume file upload
    if (!empty($_FILES['resume_file']['name'])) {
        $resume_file = basename($_FILES['resume_file']['name']);
        move_uploaded_file($_FILES['resume_file']['tmp_name'], $uploadDir . $resume_file);
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO users (
                    full_name, email, password, phone, dob, gender, address,
                    city, state, country, hobbies, linkedin, github,
                    profile_image, resume_file
                ) VALUES (
                    '$full_name', '$email', '$password_hash', '$phone', '$dob', '$gender', '$address',
                    '$city', '$state', '$country', '$hobbies', '$linkedin', '$github',
                    '$profile_image', '$resume_file'
                )";

        if ($conn->query($sql) === TRUE) {
            echo "<div style='text-align:center; margin-top:50px;'>
                    <h3>User Registered Successfully!</h3>
                    <a href='users.php' class='btn btn-success' style='font-size:18px;'>View Users</a>
                  </div>";
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Register User</h2>

    <?php
    if (!empty($errors)) {
        echo "<div class='alert alert-danger'><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul></div>";
    }
    ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($full_name) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($dob) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Select</option>
                <option value="Male" <?= $gender=='Male'?'selected':'' ?>>Male</option>
                <option value="Female" <?= $gender=='Female'?'selected':'' ?>>Female</option>
                <option value="Other" <?= $gender=='Other'?'selected':'' ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($address) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($city) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($state) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($country) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Hobbies</label>
            <input type="text" name="hobbies" class="form-control" value="<?= htmlspecialchars($hobbies) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Image</label>
            <input type="file" name="profile_image" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Resume / File (Image, PDF, DOCX)</label>
            <input type="file" name="resume_file" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">LinkedIn</label>
            <input type="url" name="linkedin" class="form-control" value="<?= htmlspecialchars($linkedin) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">GitHub</label>
            <input type="url" name="github" class="form-control" value="<?= htmlspecialchars($github) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Register User</button>
        <a href="users.php" class="btn btn-secondary">Back to Users</a>
    </form>
</div>
</body>
</html>
