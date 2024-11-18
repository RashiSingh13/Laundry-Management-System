<div class="container">
    <div class="content">
<?php


require_once 'config.php';
if (isset($_GET['id'])) { 
    $service_id = $_GET['id'];
    $delete_query = "DELETE FROM services WHERE service_id = ?";
    if ($stmt = $conn->prepare($delete_query)) {
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Service deleted successfully!";
        } else {
            echo "Service could not be deleted.";
        }
    }
}
?>

<br><br>


<link rel="stylesheet" href="delete.css">

<a href="admin_dashboard.php">
    <button>Back to Dashboard</button>
</a>
</div>
</div>
