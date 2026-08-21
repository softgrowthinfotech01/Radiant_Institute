<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$document_id = isset($_GET['document_id']) ? (int) $_GET['document_id'] : 0;

if ($document_id <= 0) {
    header("Location: documents.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Task Document + Check User Access
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        td.id,
        td.task_id,
        td.title,

        t.title AS task_title,
        p.name AS project_name

    FROM task_documents td

    INNER JOIN tasks t
        ON td.task_id = t.id

    INNER JOIN projects p
        ON t.project_id = p.id

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE td.id = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $document_id,
    $user_id
]);

$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    header("Location: documents.php");
    exit;
}


$error = '';

/*
|--------------------------------------------------------------------------
| Upload File
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_FILES['file']) ||
        $_FILES['file']['error'] === UPLOAD_ERR_NO_FILE
    ) {

        $error = "Please select a file.";

    } elseif ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {

        $error = "There was a problem uploading the file.";

    } else {

        $file = $_FILES['file'];

        $originalName = $file['name'];
        $tmpName      = $file['tmp_name'];
        $fileSize     = (int) $file['size'];

        /*
        |--------------------------------------------------------------------------
        | File Size
        |--------------------------------------------------------------------------
        | 20 MB maximum
        |--------------------------------------------------------------------------
        */

        $maxFileSize = 20 * 1024 * 1024;

        if ($fileSize > $maxFileSize) {

            $error = "File size cannot exceed 20 MB.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | Extension
            |--------------------------------------------------------------------------
            */

            $extension = strtolower(
                pathinfo($originalName, PATHINFO_EXTENSION)
            );


            /*
            |--------------------------------------------------------------------------
            | Allowed Extensions
            |--------------------------------------------------------------------------
            */

            $allowedExtensions = [
                'pdf',
                'doc',
                'docx',
                'xls',
                'xlsx',
                'ppt',
                'pptx',
                'txt',
                'csv',
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp',
                'zip'
            ];


            if (!in_array($extension, $allowedExtensions, true)) {

                $error = "This file type is not allowed.";

            } else {

                /*
                |--------------------------------------------------------------------------
                | Upload Directory
                |--------------------------------------------------------------------------
                */

                $uploadDir = __DIR__ . '/../uploads/task_documents/';

                if (!is_dir($uploadDir)) {

                    if (!mkdir($uploadDir, 0755, true)) {
                        $error = "Unable to create upload directory.";
                    }
                }


                if ($error === '') {

                    /*
                    |--------------------------------------------------------------------------
                    | Generate Safe File Name
                    |--------------------------------------------------------------------------
                    */

                    $safeFileName =
                        bin2hex(random_bytes(16)) .
                        '_' .
                        time() .
                        '.' .
                        $extension;

                    $destination = $uploadDir . $safeFileName;


                    /*
                    |--------------------------------------------------------------------------
                    | Move Uploaded File
                    |--------------------------------------------------------------------------
                    */

                    if (!move_uploaded_file($tmpName, $destination)) {

                        $error = "Unable to save the uploaded file.";

                    } else {

                        try {

                            $conn->beginTransaction();


                            /*
                            |--------------------------------------------------------------------------
                            | MIME Type
                            |--------------------------------------------------------------------------
                            */

                            $fileType = '';

                            if (function_exists('mime_content_type')) {

                                $fileType = mime_content_type($tmpName);

                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Insert File Record
                            |--------------------------------------------------------------------------
                            */

                            $stmt = $conn->prepare("
                                INSERT INTO task_document_files
                                (
                                    task_document_id,
                                    file_name,
                                    original_name,
                                    file_type,
                                    file_size
                                )
                                VALUES
                                (
                                    ?,
                                    ?,
                                    ?,
                                    ?,
                                    ?
                                )
                            ");

                            $stmt->execute([
                                $document_id,
                                $safeFileName,
                                $originalName,
                                $fileType,
                                $fileSize
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
                                'document_file_uploaded',
                                'Uploaded file "' . $originalName .
                                '" to task document "' .
                                $document['title'] .
                                '".'
                            ]);


                            $conn->commit();


                            /*
                            |--------------------------------------------------------------------------
                            | Redirect
                            |--------------------------------------------------------------------------
                            */

                            header(
                                "Location: view_task_document.php?id=" .
                                $document_id .
                                "&success=file_uploaded"
                            );

                            exit;


                        } catch (Exception $e) {

                            if ($conn->inTransaction()) {
                                $conn->rollBack();
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | Remove Physical File If DB Insert Failed
                            |--------------------------------------------------------------------------
                            */

                            if (file_exists($destination)) {
                                unlink($destination);
                            }

                            $error = "Unable to save file information.";
                        }
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Upload Document File — User</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../../dist/css/output.css"
    >

</head>


<body
    class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
>


<div
    id="mobile-backdrop"
    class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"
></div>


<div class="flex min-h-full">


    <?php include('sidebar.php'); ?>


    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


        <?php include('header.php'); ?>


        <main class="flex-1 overflow-auto p-4 lg:p-8">

            <div class="container max-w-2xl">


                <!-- Header -->

                <div class="mb-8">

                    <p
                        class="text-sm font-medium text-brand-600 dark:text-brand-400"
                    >
                        Task Document
                    </p>

                    <h2
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Upload File
                    </h2>

                    <p
                        class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                    >
                        Upload a file to this task document.
                    </p>

                </div>


                <!-- Document Information -->

                <div
                    class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <p
                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                    >
                        Task Document
                    </p>

                    <h3
                        class="mt-1 text-lg font-semibold text-slate-900 dark:text-white"
                    >
                        <?= htmlspecialchars($document['title']) ?>
                    </h3>

                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Task:
                        <?= htmlspecialchars($document['task_title']) ?>
                    </p>

                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Project:
                        <?= htmlspecialchars($document['project_name']) ?>
                    </p>

                </div>


                <!-- Error -->

                <?php if (!empty($error)): ?>

                    <div
                        class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400"
                    >
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endif; ?>


                <!-- Upload Form -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <form
                        method="POST"
                        enctype="multipart/form-data"
                        class="p-5"
                    >

                        <div>

                            <label
                                for="file"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Select File
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="file"
                                name="file"
                                id="file"
                                required
                                class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 dark:file:bg-slate-800 dark:file:text-slate-200"
                            >

                            <p
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Maximum file size: 20 MB.
                            </p>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Allowed: PDF, Word, Excel, PowerPoint,
                                TXT, CSV, images and ZIP.
                            </p>

                        </div>


                        <!-- Buttons -->

                        <div
                            class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                        >

                            <button
                                type="button"
                                onclick="window.location.href='view_task_document.php?id=<?= (int) $document_id ?>'"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Upload File
                            </button>

                        </div>

                    </form>

                </div>


            </div>

        </main>

    </div>

</div>


<script src="../../dist/js/app.js"></script>

</body>

</html>