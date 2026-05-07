<?php

include('conn.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| DELETE ACTIVITY IMAGES FROM FOLDER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM activity_images
    WHERE activity_id = :id
");

$stmt->execute([
    ':id' => $id
]);

$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($images as $image){

    $file_path =
    'uploads/activities/'.$image['image'];

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
    DELETE FROM activity_images
    WHERE activity_id = :id
");

$stmt->execute([
    ':id' => $id
]);

/*
|--------------------------------------------------------------------------
| DELETE ACTIVITY
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM extra_curricular_activities
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:extra-curricular');

exit;

?>