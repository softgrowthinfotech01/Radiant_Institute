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
| GET TOPPER IMAGE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT image
    FROM monthly_toppers
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$topper = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM FOLDER
|--------------------------------------------------------------------------
*/

if(
    $topper['image'] &&
    file_exists(
        'uploads/monthly-toppers/'.$topper['image']
    )
){

    unlink(
        'uploads/monthly-toppers/'.$topper['image']
    );
}

/*
|--------------------------------------------------------------------------
| DELETE TOPPER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM monthly_toppers
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:monthly-topper.php');

exit;

?>