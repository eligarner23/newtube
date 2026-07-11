<?php
session_start();

// CONNECT TO DATABASE
$conn = new mysqli("localhost", "root", "newtube123", "newtube");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get video ID
if (!isset($_GET["id"])) {
    die("No video selected.");
}

$video_id = intval($_GET["id"]);

// Fetch video info
$stmt = $conn->prepare("
    SELECT videos.*, users.username, users.pfp 
    FROM videos 
    JOIN users ON videos.user_id = users.id 
    WHERE videos.id = ?
");
$stmt->bind_param("i", $video_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Video not found.");
}

$video = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($video["title"]); ?> - NewTube</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; margin: 0; padding: 0; }
        .container { width: 900px; margin: auto; padding: 20px; }
        .video-player { width: 100%; background: black; }
        .video-info { margin-top: 15px; }
        .uploader-box { display: flex; align-items: center; margin-top: 10px; }
        .uploader-box img {
            width: 48px; height: 48px; border-radius: 50%; margin-right: 10px;
        }
        .related { margin-top: 40px; }
        .related-item {
            display: flex; margin-bottom: 10px; background: white;
            padding: 8px; border-radius: 6px;
        }
        .related-item img {
            width: 140px; height: 80px; object-fit: cover; margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- VIDEO PLAYER -->
    <video class="video-player" controls>
        <source src="uploads/<?php echo $video['filename']; ?>" type="video/mp4">
        Your browser does not support video playback.
    </video>

    <!-- VIDEO TITLE -->
    <div class="video-info">
        <h2><?php echo htmlspecialchars($video["title"]); ?></h2>

        <!-- UPLOADER INFO -->
        <div class="uploader-box">
            <img src="pfp/<?php echo $video["pfp"]; ?>" alt="Profile Picture">
            <a href="channel.php?u=<?php echo urlencode($video['username']); ?>">
                <?php echo htmlspecialchars($video["username"]); ?>
            </a>
        </div>

        <p>Uploaded on <?php echo $video["upload_date"]; ?></p>

        <!-- DESCRIPTION -->
        <p><?php echo nl2br(htmlspecialchars($video["description"] ?? "")); ?></p>
    </div>

    <!-- RELATED VIDEOS -->
    <div class="related">
        <h3>Related Videos</h3>

        <?php
        $related = $conn->query("
            SELECT id, title, thumbnail 
            FROM videos 
            WHERE id != $video_id 
            ORDER BY RAND() 
            LIMIT 5
        ");

        while ($r = $related->fetch_assoc()) {
            echo "
            <a href='watch.php?id={$r['id']}' style='text-decoration:none;color:black;'>
                <div class='related-item'>
                    <img src='thumbnails/{$r['thumbnail']}'>
                    <div>
                        <strong>{$r['title']}</strong>
                    </div>
                </div>
            </a>
            ";
        }
        ?>
    </div>

</div>

</body>
</html>
