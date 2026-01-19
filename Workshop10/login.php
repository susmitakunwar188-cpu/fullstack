<?php
require 'session.php';
require 'db.php';

$error = "";
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
//csrf token validation:
    if (
        !isset($_POST['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ) {
        die("Invalid request");
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Ensure fields are not empty
    if (empty($email) || empty($password)) {
        $error = "Invalid email or password";
    } else {
        //prepared statement:

        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {

            if (password_verify($password, $row['password'])) {

                // Prevent session fixation
                session_regenerate_id(true);

                // Store only user ID
                $_SESSION['user_id'] = $row['id'];

                header("Location: dashboard.php");
                exit;
            }
        }

        //prevents account enumeration:
        $error = "Invalid email or password";
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<?php if ($error) echo "<p>$error</p>"; ?>

<form method="post">
    <!-- CSRF token -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br>

    <button type="submit">Login</button>
</form>

</body>
</html>
