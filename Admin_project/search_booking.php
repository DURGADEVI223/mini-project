<?php
include 'conn.php';

$name = $_GET['name'] ?? '';
$date = $_GET['date'] ?? '';
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "
SELECT bookings.*, user.fullname
FROM bookings
JOIN user ON bookings.user_id = user.id
WHERE 1
";

$params = [];
$types = "";

if(!empty($name)){
    $sql .= " AND user.fullname LIKE ?";
    $params[] = "%$name%";
    $types .= "s";
}

if(!empty($date)){
    $sql .= " AND booking_date = ?";
    $params[] = $date;
    $types .= "s";
}

if(!empty($category)){
    $sql .= " AND ticket_category = ?";
    $params[] = $category;
    $types .= "s";
}

if(!empty($status)){
    $sql .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}

$stmt = $conn->prepare($sql);

if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table table-bordered'>";
echo "<tr>
<th>Name</th>
<th>Date</th>
<th>Category</th>
<th>Status</th>
</tr>";

while($row = $result->fetch_assoc()){
    echo "<tr>
    <td>".htmlspecialchars($row['fullname'])."</td>
    <td>".$row['booking_date']."</td>
    <td>".$row['ticket_category']."</td>
    <td>".$row['status']."</td>
    </tr>";
}

echo "</table>";