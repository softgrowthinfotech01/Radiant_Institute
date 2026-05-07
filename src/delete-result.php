<?php

include('conn.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| DELETE RESULT IMAGES FROM FOLDER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM result_images
    WHERE result_id = :id
");

$stmt->execute([
    ':id' => $id
]);

$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($images as $image){

    $file_path =
    '../uploads/results/'.$image['image'];

    if(file_exists($file_path)){

        unlink($file_path);
    }
}

/*
|--------------------------------------------------------------------------
| DELETE IMAGES FROM DB
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM result_images
    WHERE result_id = :id
");

$stmt->execute([
    ':id' => $id
]);

/*
|--------------------------------------------------------------------------
| DELETE RESULT
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM results
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:results.php');

exit;

?>