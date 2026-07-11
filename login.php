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

    if ($username === "" || $password === "") {
        $error = "All fields are required.";
    } else {
        // Fetch user
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hashed);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed)) {
                // Login success
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;

                header("Location: channel.php?u=" . urlencode($username));
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - NewTube</title>
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
    <h2>Login</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" maxlength="50">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

</body>
</html>
