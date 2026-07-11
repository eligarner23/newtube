<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NewTube – Settings</title>

    <link rel="icon" type="image/png" href="favicon.png">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body.light { background-color: #ececec; color: black; }
        .topbar.light { background-color: #cc0000; color: white; }
        .settings-box.light { background-color: white; color: black; }

        body.dark { background-color: #1a1a1a; color: white; }
        .topbar.dark { background-color: #111; color: white; }
        .settings-box.dark { background-color: #2a2a2a; color: white; }

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

        .nav-btn {
            margin-left: 20px;
            background-color: white;
            color: #cc0000;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
        }

        .settings-box {
            width: 60%;
            margin: 30px auto;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 4px rgba(0,0,0,0.2);
        }

        .section-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .input-group { margin-bottom: 20px; }
        .input-label { font-weight: bold; margin-bottom: 6px; display: block; }

        .save-btn {
            background-color: #cc0000;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .save-btn:hover { background-color: #b30000; }

        .layout-btn {
            background-color: #cc0000;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .layout-btn:hover { background-color: #b30000; }
    </style>
</head>

<body>

    <div class="topbar">
        <div class="logo" onclick="location.href='index.php'">NewTube</div>

        <div id="authButtons" style="display:flex; gap:10px; margin-left:auto;"></div>
        <div id="guestLabel" style="margin-left:10px; font-weight:bold;"></div>
    </div>

    <div class="settings-box" id="settingsBox">

        <div class="section-title">User Settings</div>

        <div class="input-group">
            <label class="input-label">Theme:</label>
            <select id="theme">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
            </select>
        </div>

        <div class="input-group">
            <label class="input-label">Autoplay:</label>
            <input type="checkbox" id="autoplay">
        </div>

        <div class="input-group">
            <label class="input-label">Default Quality:</label>
            <select id="quality">
                <option value="auto">Auto</option>
                <option value="1080p">1080p</option>
                <option value="720p">720p</option>
                <option value="480p">480p</option>
            </select>
        </div>

        <div class="input-group">
            <label class="input-label">Enable Comments:</label>
            <input type="checkbox" id="comments">
        </div>

        <div class="input-group">
            <label class="input-label">Layout:</label>
            <button class="layout-btn" onclick="resetLayout()">Change Layout</button>
        </div>

        <button class="save-btn" onclick="saveSettings()">Save Settings</button>

        <p id="status"></p>

    </div>

<script>
// THEME SYSTEM
function applyTheme() {
    let theme = "light";
    const token = localStorage.getItem("token");

    theme = !token
        ? localStorage.getItem("g_theme") || "light"
        : localStorage.getItem("user_theme") || "light";

    document.body.classList.remove("light", "dark");
    document.body.classList.add(theme);

    document.querySelector(".topbar").classList.remove("light", "dark");
    document.querySelector(".topbar").classList.add(theme);

    document.getElementById("settingsBox").classList.remove("light", "dark");
    document.getElementById("settingsBox").classList.add(theme);
}

applyTheme();

// RESET LAYOUT
function resetLayout() {
    localStorage.removeItem("g_layout");
    localStorage.removeItem("user_layout");
    location.href = "layouts.php";
}

// AUTH BUTTONS
function updateAuthButtons() {
    const token = localStorage.getItem("token");
    const box = document.getElementById("authButtons");

    if (token) {
        box.innerHTML = `
            <div class="nav-btn" onclick="location.href='upload.php'">Upload</div>
            <div class="nav-btn" onclick="location.href='settings.php'">Settings</div>
            <div class="nav-btn" onclick="location.href='logout.php'">Logout</div>
        `;
        showGuestLabel(false);
    } else {
        box.innerHTML = `
            <div class="nav-btn" onclick="location.href='settings.php'">Settings</div>
            <div class="nav-btn" onclick="location.href='login.php'">Login</div>
            <div class="nav-btn" onclick="location.href='register.php'">Register</div>
        `;
        showGuestLabel(true);
    }
}

function showGuestLabel(isGuest) {
    const label = document.getElementById("guestLabel");
    label.style.color = "white";
    label.textContent = isGuest ? "Guest Mode" : "";
}

updateAuthButtons();

// LOAD SETTINGS
const token = localStorage.getItem("token");

async function loadSettings() {
    if (!token) {
        document.getElementById("theme").value = localStorage.getItem("g_theme") || "light";
        document.getElementById("autoplay").checked = localStorage.getItem("g_autoplay") === "true";
        document.getElementById("quality").value = localStorage.getItem("g_quality") || "auto";
        document.getElementById("comments").checked = localStorage.getItem("g_comments") === "true";
        document.getElementById("status").innerText = "Guest Mode: Settings saved locally.";
        return;
    }

    const res = await fetch(`backend/api.php?route=getSettings&token=${token}`);
    const s = await res.json();

    document.getElementById("theme").value = s.theme;
    document.getElementById("autoplay").checked = s.autoplay;
    document.getElementById("quality").value = s.quality;
    document.getElementById("comments").checked = s.comments;

    localStorage.setItem("user_theme", s.theme);
    applyTheme();
}

// SAVE SETTINGS
async function saveSettings() {
    const theme = document.getElementById("theme").value;

    if (!token) {
        localStorage.setItem("g_theme", theme);
        localStorage.setItem("g_autoplay", document.getElementById("autoplay").checked);
        localStorage.setItem("g_quality", document.getElementById("quality").value);
        localStorage.setItem("g_comments", document.getElementById("comments").checked);

        applyTheme();
        document.getElementById("status").innerText = "Guest settings saved locally!";
        return;
    }

    const data = {
        token,
        theme,
        autoplay: document.getElementById("autoplay").checked,
        quality: document.getElementById("quality").value,
        comments: document.getElementById("comments").checked
    };

    const res = await fetch("backend/api.php?route=saveSettings", {
        method: "POST",
        body: JSON.stringify(data)
    });

    const json = await res.json();

    if (json.success) {
        localStorage.setItem("user_theme", theme);
        applyTheme();
        document.getElementById("status").innerText = "Settings saved!";
    } else {
        document.getElementById("status").innerText = "Failed to save settings.";
    }
}

loadSettings();
</script>

</body>
</html>
