<?php
session_start();
include("db.php");

// -----------------------------
// 1. Get channel username
// -----------------------------
$username = $_GET['u'] ?? '';

// If no ?u= provided:
if ($username == '') {

    // If logged in → load their channel
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } else {
        // Guest mode → generate or load guest name
        if (!isset($_SESSION['guest_name'])) {
            $_SESSION['guest_name'] = "Guest" . rand(1000, 9999);
        }
        $username = $_SESSION['guest_name'];
    }
}

// -----------------------------
// 2. Detect if this is a guest channel
// -----------------------------
$isGuest = false;

if (str_starts_with($username, "Guest")) {
    $isGuest = true;
}

// -----------------------------
// 3. If NOT a guest → load from database
// -----------------------------
if (!$isGuest) {

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        echo "Channel not found.";
        exit;
    }

    // Load this user's videos
    $stmt2 = $conn->prepare("SELECT * FROM videos WHERE user_id = ?");
    $stmt2->bind_param("i", $user['id']);
    $stmt2->execute();
    $videos = $stmt2->get_result();

} else {

    // -----------------------------
    // 4. Guest channel fallback
    // -----------------------------
    $user = [
        "username" => $username,
        "pfp" => "default_guest.png",
        "description" => "This is a temporary guest channel.",
        "join_date" => date("Y-m-d")
    ];

    // Load guest videos (uploaded with uploader = guest name)
    $stmt2 = $conn->prepare("SELECT * FROM videos WHERE uploader = ?");
    $stmt2->bind_param("s", $username);
    $stmt2->execute();
    $videos = $stmt2->get_result();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $user['username']; ?> - NewTube</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="channel-header">
    <img src="pfp/<?php echo $user['pfp']; ?>" class="pfp">
    <h1><?php echo $user['username']; ?></h1>
    <p class="desc"><?php echo nl2br($user['description']); ?></p>
    <p class="join">Joined: <?php echo $user['join_date']; ?></p>

    <?php if (!$isGuest && isset($_SESSION['username']) && $_SESSION['username'] == $username): ?>
        <a href="edit_profile.php" class="edit-btn">Edit Channel</a>
    <?php endif; ?>
</div>

<hr>

<h2>Uploads</h2>
<div class="video-grid">
<?php while ($v = $videos->fetch_assoc()): ?>
    <div class="video-card">
        <a href="watch.php?id=<?php echo $v['id']; ?>">
            <img src="thumbs/<?php echo $v['thumbnail']; ?>" class="thumb">
            <p class="title"><?php echo $v['title']; ?></p>
        </a>
    </div>
<?php endwhile; ?>
</div>

</body>
</html>
