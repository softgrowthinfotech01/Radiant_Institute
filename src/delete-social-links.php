<?php

include('conn.php');

$id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT *
    FROM social_link_images
    WHERE social_link_id = :id
");

$stmt->execute([
    ':id' => $id
]);

$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($images as $image){

    $file_path =
    'uploads/social-links/'.$image['image'];

    if(file_exists($file_path)){

        unlink($file_path);
    }
}

$stmt = $conn->prepare("
    DELETE FROM social_link_images
    WHERE social_link_id = :id
");

$stmt->execute([
    ':id' => $id
]);

$stmt = $conn->prepare("
    DELETE FROM social_links
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

header('location:social-links');

exit;

?>