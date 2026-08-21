<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get File ID
// --------------------------------------------------

$file_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($file_id <= 0) {
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
// Get File Information
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        f.id,
        f.task_document_id,
        f.file_name,
        f.original_name,

        td.task_id,
        td.title AS document_title,

        t.title AS task_title

    FROM task_document_files f

    INNER JOIN task_documents td
        ON td.id = f.task_document_id

    INNER JOIN tasks t
        ON t.id = td.task_id

    WHERE f.id = :id

    LIMIT 1
");

$stmt->execute([
    ':id' => $file_id
]);

$file = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$file) {
    header('Location: list_tasks.php');
    exit;
}


$document_id =
    (int) $file['task_document_id'];

$task_id =
    (int) $file['task_id'];


// --------------------------------------------------
// Physical Upload Directory
// --------------------------------------------------

$upload_directory =
    __DIR__ . '/../../uploads/task_documents/';


// --------------------------------------------------
// Build Physical File Path
// --------------------------------------------------

$stored_file_name =
    basename(
        $file['file_name']
    );


$file_path =
    $upload_directory .
    $stored_file_name;


// --------------------------------------------------
// Security Check
// --------------------------------------------------

$real_upload_directory =
    realpath(
        $upload_directory
    );


$real_file_path =
    realpath(
        $file_path
    );


// The upload directory must exist.

if ($real_upload_directory === false) {

    header(
        'Location: view_task_document.php?id=' .
        $document_id
    );

    exit;
}


// If the physical file exists,
// make sure it is actually inside
// the task_documents directory.

if ($real_file_path !== false) {

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

        http_response_code(403);

        exit(
            'Invalid file path.'
        );
    }
}


// --------------------------------------------------
// Delete File
// --------------------------------------------------

try {

    // --------------------------------------------------
    // Start Transaction
    // --------------------------------------------------

    $conn->beginTransaction();


    // --------------------------------------------------
    // Delete Database Record
    // --------------------------------------------------

    $stmt = $conn->prepare("
        DELETE FROM task_document_files
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        ':id' => $file_id
    ]);


    if ($stmt->rowCount() !== 1) {

        throw new Exception(
            'Unable to delete file record.'
        );

    }


    // --------------------------------------------------
    // Activity
    // --------------------------------------------------

    $activity_description =
        'File "' .
        $file['original_name'] .
        '" was deleted from document "' .
        $file['document_title'] .
        '".';


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
            => 'document_file_deleted',

        ':description'
            => $activity_description
    ]);


    // --------------------------------------------------
    // Commit Database Changes
    // --------------------------------------------------

    $conn->commit();


    // --------------------------------------------------
    // Delete Physical File
    // --------------------------------------------------

    if (
        $real_file_path !== false &&
        is_file($real_file_path)
    ) {

        if (
            !unlink($real_file_path)
        ) {

            /*
             * The database record has already been removed.
             *
             * We don't roll back here because the database
             * deletion and activity have already been committed.
             *
             * The orphaned physical file can be cleaned later.
             */

        }

    }


    // --------------------------------------------------
    // Redirect
    // --------------------------------------------------

    header(
        'Location: view_task_document.php?id=' .
        $document_id
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
        'Unable to delete the file. Please try again.'
    );

}