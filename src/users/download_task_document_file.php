<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$file_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($file_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get File + Check User Access
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        f.id,
        f.task_document_id,
        f.file_name,
        f.original_name,
        f.file_type,
        f.file_size,
        td.task_id,
        t.title AS task_title
    FROM task_document_files f
    INNER JOIN task_documents td
        ON f.task_document_id = td.id
    INNER JOIN tasks t
        ON td.task_id = t.id
    WHERE f.id = ?
      AND EXISTS (
          SELECT 1
          FROM task_assignees ta
          WHERE ta.task_id = t.id
            AND ta.user_id = ?
      )
    LIMIT 1
");

$stmt->execute([
    $file_id,
    $user_id
]);

$file = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$file) {
    header("Location: tasks.php");
    exit;
}

// print_r($file);exit;

/*
|--------------------------------------------------------------------------
| Physical File Location
|--------------------------------------------------------------------------
*/

$filePath = __DIR__ .
    '/../uploads/task_documents/' .
    $file['file_name'];


    
/*
|--------------------------------------------------------------------------
| Check Physical File
|--------------------------------------------------------------------------
*/

if (!is_file($filePath)) {

    header(
        "Location: view_task_document.php?id=" .
        (int) $file['task_document_id'] .
        "&error=file_not_found"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Download
|--------------------------------------------------------------------------
*/

$downloadName = $file['original_name'];


/*
|--------------------------------------------------------------------------
| Clean Output Buffer
|--------------------------------------------------------------------------
*/

while (ob_get_level()) {
    ob_end_clean();
}


/*
|--------------------------------------------------------------------------
| Headers
|--------------------------------------------------------------------------
*/

header('Content-Description: File Transfer');

header(
    'Content-Type: ' .
    (
        !empty($file['file_type'])
            ? $file['file_type']
            : 'application/octet-stream'
    )
);

header(
    'Content-Disposition: attachment; filename="' .
    str_replace('"', '', $downloadName) .
    '"'
);

header(
    'Content-Length: ' .
    filesize($filePath)
);

header('Cache-Control: no-cache, must-revalidate');

header('Pragma: public');


/*
|--------------------------------------------------------------------------
| Send File
|--------------------------------------------------------------------------
*/

readfile($filePath);

exit;