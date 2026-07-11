<?php
session_start();

// CONNECT TO DATABASE
$conn = new mysqli("localhost", "newtube", "newtube123", "newtube");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// If form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // Basic validation
    if ($username === "" || $password === "" || $confirm === "") {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed);
            $stmt->execute();

            // Auto-login
            $_SESSION["user_id"] = $stmt->insert_id;
            $_SESSION["username"] = $username;

            // Redirect to channel
            header("Location: channel.php?u=" . urlencode($username));
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - NewTube</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 40px; }
        .box {
            width: 350px; margin: auto; background: white; padding: 20px;
            border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input { width: 100%; padding: 10px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: red; color: white; border: none; cursor: pointer; }
        button:hover { background: darkred; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Create Account</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" maxlength="50">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm" placeholder="Confirm Password">
        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>
