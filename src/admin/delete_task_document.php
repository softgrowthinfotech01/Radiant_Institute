<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Document ID
// --------------------------------------------------

$document_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($document_id <= 0) {
    header('Location: list_tasks.php');
    exit;
}


// --------------------------------------------------
// Logged-in User
// --------------------------------------------------

$logged_in_user =
    $_SESSION['user_id']
    ?? $_SESSION['id']
    ?? null;


if (!$logged_in_user) {
    header('Location: ../login.php');
    exit;
}


// --------------------------------------------------
// Get Task Document
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        td.id,
        td.task_id,
        td.title,
        td.description,
        t.title AS task_title

    FROM task_documents td

    INNER JOIN tasks t
        ON t.id = td.task_id

    WHERE td.id = :id

    LIMIT 1
");

$stmt->execute([
    ':id' => $document_id
]);

$document = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$document) {
    header('Location: list_tasks.php');
    exit;
}


$task_id =
    (int) $document['task_id'];


// --------------------------------------------------
// Get Attached Files
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        id,
        file_name,
        original_name
    FROM task_document_files
    WHERE task_document_id = :document_id
");

$stmt->execute([
    ':document_id' => $document_id
]);

$files =
    $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Upload Directory
// --------------------------------------------------

$upload_directory =
    __DIR__ . '/../../uploads/task_documents/';


// --------------------------------------------------
// Delete
// --------------------------------------------------

try {

    // --------------------------------------------------
    // Start Transaction
    // --------------------------------------------------

    $conn->beginTransaction();


    // --------------------------------------------------
    // Delete File Database Records
    // --------------------------------------------------

    $stmt = $conn->prepare("
        DELETE FROM task_document_files
        WHERE task_document_id = :document_id
    ");

    $stmt->execute([
        ':document_id' => $document_id
    ]);


    // --------------------------------------------------
    // Delete Document
    // --------------------------------------------------

    $stmt = $conn->prepare("
        DELETE FROM task_documents
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $document_id
    ]);


    if ($stmt->rowCount() !== 1) {

        throw new Exception(
            'Unable to delete task document.'
        );

    }


    // --------------------------------------------------
    // Activity
    // --------------------------------------------------

    $activity_description =
        'Task document "' .
        $document['title'] .
        '" was deleted.';


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
            :task_id,
            :user_id,
            :activity_type,
            :description
        )
    ");

    $stmt->execute([
        ':task_id'
            => $task_id,

        ':user_id'
            => $logged_in_user,

        ':activity_type'
            => 'document_deleted',

        ':description'
            => $activity_description
    ]);


    // --------------------------------------------------
    // Commit Database Changes
    // --------------------------------------------------

    $conn->commit();


    // --------------------------------------------------
    // Delete Physical Files
    // --------------------------------------------------

    if (
        is_dir($upload_directory)
    ) {

        foreach ($files as $file) {

            $stored_file_name =
                basename(
                    $file['file_name']
                );


            $file_path =
                $upload_directory .
                $stored_file_name;


            $real_upload_directory =
                realpath(
                    $upload_directory
                );


            $real_file_path =
                realpath(
                    $file_path
                );


            // --------------------------------------------------
            // Security Check
            // --------------------------------------------------

            if (
                $real_upload_directory === false ||
                $real_file_path === false
            ) {
                continue;
            }


            $allowed_prefix =
                rtrim(
                    $real_upload_directory,
                    DIRECTORY_SEPARATOR
                )
                . DIRECTORY_SEPARATOR;


            if (
                strpos(
                    $real_file_path,
                    $allowed_prefix
                ) !== 0
            ) {
                continue;
            }


            // --------------------------------------------------
            // Delete Physical File
            // --------------------------------------------------

            if (
                is_file($real_file_path)
            ) {

                @unlink(
                    $real_file_path
                );

            }

        }

    }


    // --------------------------------------------------
    // Redirect
    // --------------------------------------------------

    header(
        'Location: view_task.php?id=' .
        $task_id
    );

    exit;


} catch (Throwable $e) {

    // --------------------------------------------------
    // Rollback
    // --------------------------------------------------

    if (
        $conn->inTransaction()
    ) {

        $conn->rollBack();

    }


    http_response_code(500);

    exit(
        'Unable to delete the task document. Please try again.'
    );

}