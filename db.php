<?php
require_once 'config.php'; 

$createUsersTable = "
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    role ENUM('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB;
";

if ($conn->query($createUsersTable) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating 'users' table: " . $conn->error . "<br>";
}

$createServicesTable = "
CREATE TABLE IF NOT EXISTS services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
) ENGINE=InnoDB;
";

if ($conn->query($createServicesTable) === TRUE) {
    echo "Table 'services' created successfully.<br>";
} else {
    echo "Error creating 'services' table: " . $conn->error . "<br>";
}

$createOrdersTable = "
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    quantity INT NOT NULL,
    order_date DATE NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
) ENGINE=InnoDB;
";

if ($conn->query($createOrdersTable) === TRUE) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating 'orders' table: " . $conn->error . "<br>";
}

$conn->close();
?>
