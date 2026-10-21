<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$document_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($document_id <= 0) {
    header("Location: documents.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Task Document
|--------------------------------------------------------------------------
| User must be assigned to the task.
| Only the user who uploaded the document can delete it.
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        td.id,
        td.task_id,
        td.title,
        td.uploaded_by,

        t.title AS task_title

    FROM task_documents td

    INNER JOIN tasks t
        ON td.task_id = t.id

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE td.id = ?
      AND td.uploaded_by = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $document_id,
    $user_id,
    $user_id
]);

$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    header("Location: documents.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Delete Document
|--------------------------------------------------------------------------
*/

try {

    $conn->beginTransaction();


    /*
    |--------------------------------------------------------------------------
    | Delete Associated Files
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        SELECT id
        FROM task_document_files
        WHERE task_document_id = ?
    ");

    $stmt->execute([
        $document_id
    ]);

    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | Delete File Records
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM task_document_files
        WHERE task_document_id = ?
    ");

    $stmt->execute([
        $document_id
    ]);


    /*
    |--------------------------------------------------------------------------
    | Delete Document
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        DELETE FROM task_documents

        WHERE id = ?
          AND uploaded_by = ?
    ");

    $stmt->execute([
        $document_id,
        $user_id
    ]);


    /*
    |--------------------------------------------------------------------------
    | Activity
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        INSERT INTO task_activities
        (
            task_id,
            user_id,
            activity_type,
            description
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $document['task_id'],
        $user_id,
        'document_deleted',
        'Deleted task document: "' . $document['title'] . '".'
    ]);


    $conn->commit();


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    header(
        "Location: view_task.php?id=" .
        (int) $document['task_id'] .
        "&success=document_deleted"
    );

    exit;


} catch (Exception $e) {

    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    header(
        "Location: view_task.php?id=" .
        (int) $document['task_id'] .
        "&error=document_delete_failed"
    );

    exit;
}