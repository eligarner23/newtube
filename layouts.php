<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NewTube – Layout Selector</title>

    <link rel="icon" type="image/png" href="favicon.png">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body.light { background-color: #ececec; color: black; }
        .topbar.light { background-color: #cc0000; color: white; }
        .layout-card.light { background-color: white; color: black; }

        body.dark { background-color: #1a1a1a; color: white; }
        .topbar.dark { background-color: #111; color: white; }
        .layout-card.dark { background-color: #2a2a2a; color: white; }

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

        .container {
            width: 80%;
            margin: 30px auto;
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .layout-card {
            flex: 1;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 0 4px rgba(0,0,0,0.2);
            cursor: pointer;
            text-align: center;
            transition: 0.2s;
        }

        .layout-card:hover {
            transform: scale(1.03);
        }

        .layout-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .layout-desc {
            font-size: 14px;
            opacity: 0.8;
        }
    </style>
</head>

<body>

<div class="topbar">
    <div class="logo" onclick="location.href='index.php'">NewTube</div>

    <div id="authButtons" style="display:flex; gap:10px; margin-left:auto;"></div>
    <div id="guestLabel" style="margin-left:10px; font-weight:bold;"></div>
</div>

<h2 style="text-align:center; margin-top:20px;">Choose Your Layout</h2>

<div class="container">
    <div class="layout-card" id="modernCard" onclick="selectLayout('modern')">
        <div class="layout-title">Modern Layout</div>
        <div class="layout-desc">Clean, rounded, 2020s-style NewTube interface.</div>
    </div>

    <div class="layout-card" id="legacyCard" onclick="selectLayout('legacy')">
        <div class="layout-title">Legacy Layout</div>
        <div class="layout-desc">Classic 2000s/2010s YouTube-style layout everyone remembers.</div>
    </div>
</div>

<script>
// -------------------------------
// THEME SYSTEM
// -------------------------------
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

    document.querySelectorAll(".layout-card").forEach(el => {
        el.classList.remove("light", "dark");
        el.classList.add(theme);
    });
}

applyTheme();

// -------------------------------
// Topbar Buttons + Guest Mode
// -------------------------------
function updateAuthButtons() {
    const token = localStorage.getItem("token");
    const box = document.getElementById("authButtons");

    if (token) {
        box.innerHTML = `
            <div class="nav-btn" onclick="location.href='upload.php'">Upload</div>
            <div class="nav-btn" onclick="location.href='settings.php'">Settings</div>
            <div class="nav-btn" onclick="logout()">Logout</div>
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

function logout() {
    localStorage.removeItem("token");
    location.href = "index.php";
}

function showGuestLabel(isGuest) {
    const label = document.getElementById("guestLabel");
    label.style.color = "white";
    label.textContent = isGuest ? "Guest Mode" : "";
}

updateAuthButtons();

// -------------------------------
// AUTO-REDIRECT IF LAYOUT ALREADY CHOSEN
// -------------------------------
(function autoRedirect() {
    const token = localStorage.getItem("token");

    let layout = !token
        ? localStorage.getItem("g_layout")
        : localStorage.getItem("user_layout");

    if (!layout) return;

    if (layout === "modern") location.href = "index.php";
    if (layout === "legacy") location.href = "legacy_index.php";
})();

// -------------------------------
// SELECT LAYOUT (FIXED VERSION)
// -------------------------------
async function selectLayout(layout) {
    const token = localStorage.getItem("token");

    // Guest mode
    if (!token) {
        localStorage.setItem("g_layout", layout);
        redirect(layout);
        return;
    }

    // Logged-in user (session backend)
    const res = await fetch("backend/api.php?route=saveLayout", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ layout })
    });

    const json = await res.json();

    if (json.success) {
        localStorage.setItem("user_layout", layout);
        redirect(layout);
    } else {
        alert("Failed to save layout: " + (json.error || "Unknown error"));
    }
}

function redirect(layout) {
    if (layout === "modern") location.href = "index.php";
    if (layout === "legacy") location.href = "legacy_index.php";
}
</script>

</body>
</html>
