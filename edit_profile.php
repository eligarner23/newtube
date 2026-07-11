<?php
session_start();
include("db.php");

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to edit your profile.";
    exit;
}

$username = $_SESSION['username'];

// Get user info
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = $_POST['description'];

    // Handle profile picture upload
    if (!empty($_FILES['pfp']['name'])) {
        $pfp_name = time() . "_" . basename($_FILES['pfp']['name']);
        $target = "pfp/" . $pfp_name;
        move_uploaded_file($_FILES['pfp']['tmp_name'], $target);

        // Update DB with new pfp
        $stmt2 = $conn->prepare("UPDATE users SET pfp = ?, description = ? WHERE username = ?");
        $stmt2->bind_param("sss", $pfp_name, $description, $username);
    } else {
        // Update only description
        $stmt2 = $conn->prepare("UPDATE users SET description = ? WHERE username = ?");
        $stmt2->bind_param("ss", $description, $username);
    }

    $stmt2->execute();

    header("Location: channel.php?user=" . $username);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - NewTube</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Edit Your Channel</h1>

<form method="POST" enctype="multipart/form-data">

    <label>Profile Picture:</label><br>
    <img src="pfp/<?php echo $user['pfp']; ?>" width="120"><br><br>
    <input type="file" name="pfp"><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" cols="50"><?php echo $user['description']; ?></textarea><br><br>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
