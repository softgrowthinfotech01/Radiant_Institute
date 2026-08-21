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
    http_response_code(400);
    exit('Invalid file ID.');
}


// --------------------------------------------------
// Logged-in User
// --------------------------------------------------

$logged_in_user =
    $_SESSION['user_id']
    ?? $_SESSION['id']
    ?? null;


if (!$logged_in_user) {
    http_response_code(403);
    exit('Unauthorized.');
}


// --------------------------------------------------
// Get File + Document + Task
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        f.id,
        f.task_document_id,
        f.file_name,
        f.original_name,
        f.file_type,
        f.file_size,

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
    http_response_code(404);
    exit('File not found.');
}


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


if (
    $real_upload_directory === false ||
    $real_file_path === false
) {

    http_response_code(404);
    exit('Physical file not found.');

}


// Make sure the file is actually inside
// the task_documents upload directory.

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
    exit('Invalid file path.');

}


// --------------------------------------------------
// Check File
// --------------------------------------------------

if (!is_file($real_file_path)) {

    http_response_code(404);
    exit('File not found.');

}


if (!is_readable($real_file_path)) {

    http_response_code(403);
    exit('File cannot be read.');

}


// --------------------------------------------------
// Get File Size
// --------------------------------------------------

$file_size =
    filesize(
        $real_file_path
    );


if ($file_size === false) {

    http_response_code(500);
    exit('Unable to determine file size.');

}


// --------------------------------------------------
// Original File Name
// --------------------------------------------------

$original_name =
    $file['original_name'];


if (
    !$original_name ||
    trim($original_name) === ''
) {

    $original_name =
        $file['file_name'];

}


// --------------------------------------------------
// Clean Download Filename
// --------------------------------------------------

$download_name =
    basename(
        $original_name
    );


// Remove characters that can cause
// problems in HTTP headers.

$download_name =
    preg_replace(
        '/[\r\n"]+/',
        '',
        $download_name
    );


if (
    !$download_name ||
    trim($download_name) === ''
) {

    $download_name =
        'download';
}


// --------------------------------------------------
// Determine MIME Type
// --------------------------------------------------

$mime_type =
    $file['file_type'];


if (
    !$mime_type ||
    trim($mime_type) === ''
) {

    $mime_type =
        'application/octet-stream';

}


// --------------------------------------------------
// Clear Existing Output
// --------------------------------------------------

while (
    ob_get_level() > 0
) {

    ob_end_clean();

}


// --------------------------------------------------
// Send Download Headers
// --------------------------------------------------

header(
    'Content-Description: File Transfer'
);

header(
    'Content-Type: ' . $mime_type
);

header(
    'Content-Disposition: attachment; filename="' .
    $download_name .
    '"; filename*=UTF-8\'\'' .
    rawurlencode($download_name)
);

header(
    'Content-Transfer-Encoding: binary'
);

header(
    'Content-Length: ' . $file_size
);

header(
    'Cache-Control: private, no-store, no-cache, must-revalidate'
);

header(
    'Pragma: no-cache'
);

header(
    'Expires: 0'
);


// --------------------------------------------------
// Send File
// --------------------------------------------------

$handle =
    fopen(
        $real_file_path,
        'rb'
    );


if ($handle === false) {

    http_response_code(500);
    exit('Unable to open file.');

}


while (!feof($handle)) {

    echo fread(
        $handle,
        1024 * 1024
    );

    flush();

}


fclose($handle);

exit;