<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = trim($_POST['service_name']);
    $price = trim($_POST['price']);

    if (empty($service_name) || empty($price)) {
        $error = "Both fields are required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Please enter a valid price.";
    } else {
        $stmt = $conn->prepare("INSERT INTO services (service_name, price) VALUES (?, ?)");
        $stmt->bind_param("sd", $service_name, $price);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php"); 
            exit();
        } else {
            $error = "Error adding service. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Service</title>
    <link rel="stylesheet" href="service.css">
</head>
<body>
    <div class="container">
    <h1>Add New Service</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="add_service.php" method="POST">
        <label for="service_name">Service Name:</label>
        <input type="text" id="service_name" name="service_name" required>
        
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required>

        <button type="submit">Add Service</button>
    </form>
    <footer>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    </footer>    
</div>
</body>
</html>
