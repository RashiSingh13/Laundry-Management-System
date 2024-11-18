<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT o.order_id, s.service_name, o.quantity, o.order_date, o.status, (s.price * o.quantity) AS total_price 
          FROM orders o 
          JOIN services s ON o.service_id = s.service_id 
          WHERE o.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="cd.css">
</head>
<body>
    <div class="container">
    <h1>Customer Dashboard</h1>

    <h2>Your Orders</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Order ID</th>
            <th>Service Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">You have no orders.</td>
            </tr>
        <?php endif; ?>
    </table>
    <a href="place_order.php">Place a New Order</a> <br> 

    <a href="logout.php">Logout</a>
    </div>
</body>
</html>
