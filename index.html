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
$videos = $conn->query("SELECT * FROM videos ORDER BY id DESC LIMIT 40");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NewTube – Broadcast Yourself Again</title>

    <link rel="icon" type="image/png" href="favicon.png">

    <style>
        body { margin: 0; font-family: Arial, sans-serif; }

        body.light { background-color: #ececec; color: black; }
        .topbar.light { background-color: #cc0000; color: white; }
        .video-card.light { background-color: white; color: black; }

        body.dark { background-color: #1a1a1a; color: white; }
        .topbar.dark { background-color: #111; color: white; }
        .video-card.dark { background-color: #2a2a2a; color: white; }

        .topbar {
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }

        .logo {
            font-size: 26px;
            font-weight: bold;
            margin-right: 30px;
            cursor: pointer;
        }

        .search-box {
            flex: 1;
            display: flex;
        }

        .search-box input {
            width: 100%;
            padding: 6px;
            border: none;
            border-radius: 2px 0 0 2px;
        }

        .search-box button {
            padding: 6px 12px;
            border: none;
            background-color: #f2f2f2;
            border-radius: 0 2px 2px 0;
            cursor: pointer;
        }

        .nav-btn {
            margin-left: 20px;
            background-color: white;
            color: #cc0000;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
        }

        .content {
            width: 90%;
            margin: 20px auto;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .video-card {
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 0 3px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .thumbnail {
            width: 100%;
            height: 120px;
            background-color: #d0d0d0;
            border-radius: 3px;
            background-size: cover;
            background-position: center;
        }

        .video-title {
            font-size: 14px;
            margin-top: 8px;
            font-weight: bold;
        }

        .video-meta {
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>

    <!-- Top Navigation -->
    <div class="topbar">
        <div class="logo" onclick="location.href='index.php'">NewTube</div>

        <div class="search-box">
            <input id="searchInput" type="text" placeholder="Search videos…">
            <button onclick="doSearch()">Search</button>
        </div>

        <div id="authButtons" style="display:flex; gap:10px; margin-left:20px;"></div>
        <div id="guestLabel" style="margin-left:10px; font-weight:bold;"></div>
    </div>

    <div class="content">
        <div class="section-title">Featured Videos</div>

        <div class="video-grid">
            <?php while ($v = $videos->fetch_assoc()): ?>
                <div class="video-card <?php echo $_SESSION['theme'] ?? 'light'; ?>"
                     onclick="location.href='watch.php?id=<?php echo $v['id']; ?>'">

                    <div class="thumbnail"
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
// THEME SYSTEM
function applyTheme() {
    let theme = localStorage.getItem("token")
        ? localStorage.getItem("user_theme") || "light"
        : localStorage.getItem("g_theme") || "light";

    document.body.classList.remove("light", "dark");
    document.body.classList.add(theme);

    const topbar = document.querySelector(".topbar");
    topbar.classList.remove("light", "dark");
    topbar.classList.add(theme);

    document.querySelectorAll(".video-card").forEach(el => {
        el.classList.remove("light", "dark");
        el.classList.add(theme);
    });
}

applyTheme();

// AUTH BUTTONS
function updateAuthButtons() {
    const username = "<?php echo $_SESSION['username']; ?>";
    const isGuest = <?php echo $_SESSION['guest'] ? 'true' : 'false'; ?>;
    const box = document.getElementById("authButtons");

    box.innerHTML = `
        <div class="nav-btn" onclick="location.href='upload.php'">Upload</div>
        <div class="nav-btn" onclick="location.href='channel.php?u=${username}'">My Channel</div>
        <div class="nav-btn" onclick="location.href='settings.php'">Settings</div>
        <div class="nav-btn" onclick="location.href='logout.php'">Logout</div>
    `;

    document.getElementById("guestLabel").textContent = isGuest ? "Guest Mode" : "";
}

updateAuthButtons();

// SEARCH
function doSearch() {
    const q = document.getElementById("searchInput").value.trim();
    if (q !== "") {
        location.href = `search.php?q=${encodeURIComponent(q)}`;
    }
}
</script>

</body>
</html>
