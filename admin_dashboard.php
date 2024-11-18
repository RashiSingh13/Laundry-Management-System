<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM services";
$services_result = $conn->query($query);

$orders_query = "SELECT o.order_id, u.username, s.service_name, o.quantity, s.price, o.order_date, o.status, 
                        (s.price * o.quantity) AS total_price
                 FROM orders o 
                 JOIN services s ON o.service_id = s.service_id
                 JOIN users u ON o.user_id = u.user_id";
$orders_result = $conn->query($orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="ad.css">
</head> 
<body>
    <div class="container">
<h1>Admin Dashboard</h1>


<h3>Existing Services</h3>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Service ID</th>
        <th>Service Name</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php if ($services_result->num_rows > 0): ?>
        <?php while ($service = $services_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($service['service_id']); ?></td>
                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                <td>₹<?php echo htmlspecialchars($service['price']); ?></td>
                <td>
                    <div class="actions">
                    <a href="edit_service.php?id=<?php echo $service['service_id']; ?>">Edit</a> 
                    <a href="delete_service.php?id=<?php echo $service['service_id']; ?>" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No services available.</td>
        </tr>
    <?php endif; ?>
</table>

<h3>View Orders</h3>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Service Name</th>
        <th>Quantity</th>
        <th>Price per Service</th>
        <th>Total Price</th>
        <th>Order Date</th>
        <th>Status</th>
        <th>Update Status</th>
    </tr>
    <?php if ($orders_result->num_rows > 0): ?>
        <?php while ($order = $orders_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td><?php echo htmlspecialchars($order['service_name']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td>₹<?php echo htmlspecialchars($order['price']); ?></td>
                <td>₹<?php echo htmlspecialchars($order['total_price']); ?></td>
                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td><a href="update_order_status.php?order_id=<?php echo $order['order_id']; ?>">Update Status</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="9">No orders found.</td>
        </tr>
    <?php endif; ?>
</table>
<footer>
<a href="add_service.php">Add New Service</a> 
<a href="logout.php">Logout</a>
</footer>
</div>
</body>
</html>
