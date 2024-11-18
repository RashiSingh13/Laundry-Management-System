<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $query = "SELECT status FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $current_status = $order['status'];
    } else {
        echo "Order not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_status = $_POST['status'];

        $update_query = "UPDATE orders SET status = ? WHERE order_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_status, $order_id);

        if ($update_stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error updating the status.";
        }
    }
} else {
    echo "No order ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Order Status</title>
    <link rel="stylesheet" href="update.css">
</head>
<body>
    <div class="container">
<h2>Update Order Status</h2>

<form method="POST" action="update_order_status.php?order_id=<?php echo $order_id; ?>">
    <label for="status">Current Status: </label>
    <input type="text" name="current_status" value="<?php echo htmlspecialchars($current_status); ?>" readonly><br><br>

    <label for="status">New Status: </label>
    <select name="status" id="status" required>
    <option value="Pending" <?php echo ($current_status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
    <option value="Completed" <?php echo ($current_status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
    <option value="Cancelled" <?php echo ($current_status == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
</select>
<br><br>

    <button type="submit">Update Status</button>
</form>
</div>
</body>
</html>
