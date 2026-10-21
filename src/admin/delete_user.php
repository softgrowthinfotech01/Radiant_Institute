<?php

include('../admin_check.php');
include('../conn.php'); // should create $pdo

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: list_user.php?msg=deleted");
    exit();

} else {
    echo "Invalid Request";
}
?>