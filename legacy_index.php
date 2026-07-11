<?php
session_start();
include("db.php");

// --------------------------------------
// AUTO-GUEST ACCOUNT SYSTEM (PHP version)
// --------------------------------------
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = "Guest_" . rand(10000, 99999);
    $_SESSION['guest'] = true;
} else {
    $_SESSION['guest'] = false;
}

// --------------------------------------
// LOAD VIDEOS FROM DATABASE
// --------------------------------------
$videos = $conn->query("SELECT * FROM videos ORDER BY id DESC LIMIT 30");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NewTube Classic – Home</title>

    <link rel="icon" type="image/png" href="favicon.png">

    <style>
        body { margin: 0; font-family: Arial, sans-serif; }

        body.light { background-color: #e2e2e2; color: black; }
        .topbar.light { background-color: #cc0000; color: white; }
        .video-box.light { background-color: white; color: black; }

        body.dark { background-color: #1a1a1a; color: white; }
        .topbar.dark { background-color: #111; color: white; }
        .video-box.dark { background-color: #2a2a2a; color: white; }

        .topbar {
            padding: 6px 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #000;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-right: 30px;
            cursor: pointer;
        }

        .tabs { margin-left: 20px; font-size: 14px; }
        .tabs span { margin-right: 15px; cursor: pointer; color: white; }
        .tabs span:hover { text-decoration: underline; }

        .search-box { display: flex; flex: 1; margin-left: 40px; }
        .search-box input { width: 100%; padding: 5px; border: 1px solid #888; border-right: none; }
        .search-box button { padding: 6px 12px; border: 1px solid #888; background-color: #f2f2f2; cursor: pointer; }

        .nav-btn {
            margin-left: 15px;
            background-color: white;
            color: #cc0000;
            padding: 5px 10px;
            border: 1px solid #cc0000;
            cursor: pointer;
            font-weight: bold;
        }

        .container { width: 960px; margin: 20px auto; }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            border-bottom: 1px solid #aaa;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .video-box {
            padding: 10px;
            border: 1px solid #aaa;
            cursor: pointer;
        }

        .video-thumb {
            width: 100%;
            height: 150px;
            background-color: #ccc;
            background-size: cover;
            background-position: center;
            border: 1px solid #888;
        }

        .video-title { font-size: 14px; font-weight: bold; margin-top: 8px; }
        .video-meta { font-size: 12px; color: #555; }
    </style>
</head>

<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo" onclick="location.href='legacy_index.php'">NewTube</div>

    <div class="tabs">
        <span onclick="location.href='legacy_search.php?q=all'">Videos</span>
        <span onclick="location.href='legacy_channels.php'">Channels</span>
        <span onclick="location.href='legacy_community.php'">Community</span>
        <span onclick="location.href='legacy_inbox.php'">Inbox</span>
    </div>

    <div class="search-box">
        <input id="searchInput" type="text" placeholder="Search videos…">
        <button onclick="doSearch()">Search</button>
    </div>

    <div id="authButtons" style="display:flex; gap:10px; margin-left:20px;"></div>
    <div id="guestLabel" style="margin-left:10px; font-weight:bold;"></div>
</div>

<!-- Main Content -->
<div class="container">
    <div class="section-title">Featured Videos</div>

    <div class="video-grid">
        <?php while ($v = $videos->fetch_assoc()): ?>
            <div class="video-box <?php echo $_SESSION['theme'] ?? 'light'; ?>"
                 onclick="location.href='legacy_watch.php?id=<?php echo $v['id']; ?>'">

                <div class="video-thumb"
                     style="background-image:url('thumbs/<?php echo $v['thumbnail']; ?>')"></div>

                <div class="video-title"><?php echo $v['title']; ?></div>

                <div class="video-meta">
                    <a href="channel.php?u=<?php echo $v['uploader']; ?>"
                       style="color:inherit; text-decoration:none;">
                        <?php echo $v['uploader']; ?>
                    </a>
                    • <?php echo $v['views']; ?> views
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
// -------------------------------
// SEARCH
// -------------------------------
function doSearch() {
    const q = document.getElementById("searchInput").value.trim();
    if (q) location.href = `legacy_search.php?q=${encodeURIComponent(q)}`;
}

// -------------------------------
// AUTH BUTTONS
// -------------------------------
function updateAuthButtons() {
    const username = "<?php echo $_SESSION['username']; ?>";
    const isGuest = <?php echo $_SESSION['guest'] ? 'true' : 'false'; ?>;
    const box = document.getElementById("authButtons");

    box.innerHTML = `
        <div class="nav-btn" onclick="location.href='legacy_upload.php'">Upload</div>
        <div class="nav-btn" onclick="location.href='channel.php?u=${username}'">My Channel</div>
        <div class="nav-btn" onclick="location.href='legacy_settings.php'">Settings</div>
        <div class="nav-btn" onclick="location.href='logout.php'">Logout</div>
    `;

    document.getElementById("guestLabel").textContent = isGuest ? "Guest Mode" : "";
}

updateAuthButtons();
</script>

</body>
</html>
