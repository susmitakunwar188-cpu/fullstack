<?php
/*
  signup.php
  -----------
  Handles user registration securely
*/

require 'db.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }
    // Password rules
    elseif (empty($password) || strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    }
    else {
        //password hashing:

        // Hash password before storing (CRITICAL SECURITY STEP)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        //prepared statement:

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<p>Signup successful. <a href='login.php'>Login</a></p>";
        } else {
            echo "<p>Error occurred. Please try again.</p>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<?php
// Display error securely (no system info leaked)
if ($error) {
    echo "<p>$error</p>";
}
?>

<form method="post">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br>

    <button type="submit">Signup</button>
</form>

</body>
</html>
