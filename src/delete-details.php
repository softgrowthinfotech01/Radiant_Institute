<?php

session_start();

if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| GET DETAIL IMAGE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT highlight_image
    FROM course_details
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$detail = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM FOLDER
|--------------------------------------------------------------------------
*/

if(
    $detail['highlight_image'] &&
    file_exists(
        '../uploads/course-details/'.$detail['highlight_image']
    )
){

    unlink(
        '../uploads/course-details/'.$detail['highlight_image']
    );
}

/*
|--------------------------------------------------------------------------
| DELETE DETAILS
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM course_details
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:details.php');

exit;

?>