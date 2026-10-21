<?php

session_start();

if(!isset($_SESSION['admin'])){
    header('location:login');
    exit;
}

include('../conn.php');

$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT highlight_image
    FROM course_details
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row){

    $file =
    '../uploads/course-details/' .
    $row['highlight_image'];

    if(
        !empty($row['highlight_image']) &&
        file_exists($file)
    ){
        unlink($file);
    }

    $stmt = $conn->prepare("
        UPDATE course_details
        SET highlight_image = NULL
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);
}

header(
    'location:edit-details.php?id=' . $id
);

exit;