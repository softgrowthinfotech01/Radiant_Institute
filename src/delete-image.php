<?php

session_start();

if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

include('delete-image-function.php');

$id = $_GET['id'];

$table = $_GET['table'];

$folder = $_GET['folder'];

$redirect = $_GET['redirect'];

$foreign_key = $_GET['foreign_key'];

/*
|--------------------------------------------------------------------------
| GET IMAGE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM {$table}
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$image = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE
|--------------------------------------------------------------------------
*/

deleteImage(
    $folder,
    $image['image']
);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE RECORD
|--------------------------------------------------------------------------
*/

deleteImageRecord(
    $conn,
    $table,
    $id
);

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

header(
    'location:'.$redirect.'?id='.
    $image[$foreign_key]
);

exit;

?>