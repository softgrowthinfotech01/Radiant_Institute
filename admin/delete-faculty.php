<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');
include('faculty-helpers.php');

$id = (int)($_GET['id'] ?? 0);

/*
|--------------------------------------------------------------------------
| GET FACULTY PHOTO
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT image
    FROM faculty
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$member = $stmt->fetch(PDO::FETCH_ASSOC);

if ($member) {

    /*
    |--------------------------------------------------------------------------
    | DELETE FACULTY
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM faculty
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    /*
    |--------------------------------------------------------------------------
    | DELETE PHOTO FROM FOLDER
    |--------------------------------------------------------------------------
    */

    faculty_delete_photo($member['image']);
}

header('location:faculty.php');

exit;

?>
