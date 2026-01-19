<?php

session_start();
require 'db.php';


// Check authentication
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_unset();      // Remove session variables
    session_destroy();    // Destroy session
    header("Location: login.php");
    exit;
}
//fetch user details:
$sql = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<body>

<h1>
    Welcome,
    <?php 
    //to prevent XSS
    echo htmlspecialchars($user['email']); 
    ?>!
</h1>

<form method="post">
    <button type="submit" name="logout">Logout</button>
</form>

</body>
</html>
