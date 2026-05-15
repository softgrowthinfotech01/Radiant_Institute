<?php

include('../conn.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| GET SLIDER IMAGE
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT image
    FROM sliders
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$slider = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| DELETE IMAGE FROM FOLDER
|--------------------------------------------------------------------------
*/

if ($slider && !empty($slider['image'])) {

    $file_path =
        'uploads/sliders/' . $slider['image'];

    if (file_exists($file_path)) {

        unlink($file_path);
    }
}

/*
|--------------------------------------------------------------------------
| DELETE SLIDER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM sliders
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:slider.php');

exit;

?>