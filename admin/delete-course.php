<?php

include('../conn.php');

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM courses WHERE id = :id");

$stmt->execute([
    ':id' => $id
]);

header('location:courses.php');

?>