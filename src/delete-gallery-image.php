<?php

session_start();

if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

include('delete-image-function.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| GET IMAGE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM gallery_images
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$image = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM FOLDER
|--------------------------------------------------------------------------
*/

deleteImage(
    '../uploads/gallery',
    $image['image']
);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM DATABASE
|--------------------------------------------------------------------------
*/

deleteImageRecord(
    $conn,
    'gallery_images',
    $id
);

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

header(
    'location:edit-gallery.php?id='.
    $image['gallery_id']
);

exit;

?>