<?php

include('conn.php');

$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT *
    FROM gallery_images
    WHERE gallery_id = :id
");

$stmt->execute([
    ':id' => $id
]);

$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($images as $image){

    $file_path =
    'uploads/gallery/'.$image['image'];

    if(file_exists($file_path)){

        unlink($file_path);
    }
}

$stmt = $conn->prepare("
    DELETE FROM gallery_images
    WHERE gallery_id = :id
");

$stmt->execute([
    ':id' => $id
]);

$stmt = $conn->prepare("
    DELETE FROM galleries
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:gallery');

exit;

?>