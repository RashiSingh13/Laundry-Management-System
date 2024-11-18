<?php
require_once 'config.php';

$success_message = "";

if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    $query = "SELECT * FROM services WHERE service_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $service = $result->fetch_assoc();
        } else {
            echo "Service not found!";
            exit;
        }
    }
}

if (isset($_POST['submit'])) {
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];

    $update_query = "UPDATE services SET service_name = ?, price = ? WHERE service_id = ?";
    if ($stmt = $conn->prepare($update_query)) {
        $stmt->bind_param("sdi", $service_name, $price, $service_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $success_message = "Service updated successfully!";
        } else {
            $success_message = "No changes made.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
  <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="container">
        <?php if (!empty($success_message)): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <h1>Edit Service</h1>
        <form method="post">
            <label for="service_name">Service Name:</label>
            <input type="text" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($service['price']); ?>" step="0.01" required>
            <button type="submit" name="submit">Update Service</button>
        </form>
        <div class="back-button">
            <a href="admin_dashboard.php"><button>Back to Dashboard</button></a>
        </div>
    </div>
</body>
</html>
