<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Project ID
// --------------------------------------------------

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: list_projects.php');
    exit;
}


// --------------------------------------------------
// Check Project Exists
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT id
    FROM projects
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $id
]);

$project = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$project) {
    header('Location: list_projects.php');
    exit;
}


// --------------------------------------------------
// Delete Project
// --------------------------------------------------

try {

    $stmt = $conn->prepare("
        DELETE FROM projects
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);


    header('Location: list_projects.php');
    exit;


} catch (PDOException $e) {

    /*
     * If the project has related records and your
     * database foreign-key rules prevent deletion,
     * redirect back to the project list.
     *
     * We will handle related task/document deletion
     * properly when we build those modules.
     */

    header('Location: list_projects.php?error=delete_failed');
    exit;

}