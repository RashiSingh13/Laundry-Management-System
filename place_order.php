<?php
session_start();
require_once 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

// Fetch available services
$services = [];
$result = $conn->query("SELECT * FROM services");

if ($result === false) {
    die("Error fetching services: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $quantity = $_POST['quantity'];
    $order_date = date('Y-m-d');
    $user_id = $_SESSION['user_id'];

    if (empty($service_id) || empty($quantity)) {
        die("Service ID or quantity is missing.");
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, service_id, quantity, order_date, status) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $status = 'Pending'; // Default order status
    $stmt->bind_param("iiiss", $user_id, $service_id, $quantity, $order_date, $status);

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }

    header("Location: customer_dashboard.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Place Order</title>
    <link rel="stylesheet" href="po.css">
</head>
<body>
    <div class="container">
    <h2>Place Order</h2>
    <?php
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
    <form method="POST" action="place_order.php">
        <label for="service_id">Select Service:</label>
        <select name="service_id" id="service_id" required>
            <option value="">--Select Service--</option>
            <?php foreach ($services as $service): ?>
                <option value="<?php echo $service['service_id']; ?>"><?php echo $service['service_name']; ?> - ₹<?php echo $service['price']; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" required><br><br>

        <button type="submit">Place Order</button>
    </form>
    </div>
</body>
</html>


