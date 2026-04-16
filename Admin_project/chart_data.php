<?php
include 'conn.php';

$data = [];

$q = $conn->query("
SELECT booking_date, SUM(total_price) as total
FROM bookings
GROUP BY booking_date
");

while($row = $q->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);