<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Document ID
// --------------------------------------------------

$document_id = isset($_GET['document_id'])
    ? (int) $_GET['document_id']
    : (int) ($_POST['document_id'] ?? 0);


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


$task_id = (int) $document['task_id'];


// --------------------------------------------------
// Upload Settings
// --------------------------------------------------

// Physical upload directory.
// Change this if your project uses another upload location.

$upload_directory =
    __DIR__ . '/../../uploads/task_documents/';


// Maximum file size: 20 MB

$max_file_size =
    20 * 1024 * 1024;


// Allowed extensions

$allowed_extensions = [
    'pdf',
    'doc',
    'docx',
    'xls',
    'xlsx',
    'csv',
    'ppt',
    'pptx',
    'txt',
    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp',
    'zip',
    'rar',
    '7z'
];


// --------------------------------------------------
// Variables
// --------------------------------------------------

$error = '';


// --------------------------------------------------
// Upload File
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_FILES['document_file']) ||
        !is_array($_FILES['document_file'])
    ) {

        $error = 'Please select a file.';

    } else {

        $file = $_FILES['document_file'];


        // --------------------------------------------------
        // Upload Error
        // --------------------------------------------------

        if ($file['error'] !== UPLOAD_ERR_OK) {

            switch ($file['error']) {

                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:

                    $error =
                        'The uploaded file is too large.';

                    break;

                case UPLOAD_ERR_NO_FILE:

                    $error =
                        'Please select a file.';

                    break;

                default:

                    $error =
                        'There was a problem uploading the file.';

                    break;
            }


        } elseif ($file['size'] <= 0) {

            $error =
                'The selected file is empty.';


        } elseif ($file['size'] > $max_file_size) {

            $error =
                'File size cannot exceed 20 MB.';


        } else {

            // --------------------------------------------------
            // Original File Name
            // --------------------------------------------------

            $original_name =
                basename(
                    $file['name']
                );


            // Prevent excessively long names

            if (
                mb_strlen($original_name) > 255
            ) {

                $error =
                    'File name cannot exceed 255 characters.';

            } else {

                // --------------------------------------------------
                // Extension
                // --------------------------------------------------

                $extension = strtolower(
                    pathinfo(
                        $original_name,
                        PATHINFO_EXTENSION
                    )
                );


                if (
                    $extension === '' ||
                    !in_array(
                        $extension,
                        $allowed_extensions,
                        true
                    )
                ) {

                    $error =
                        'This file type is not allowed.';

                } else {

                    // --------------------------------------------------
                    // Create Upload Directory
                    // --------------------------------------------------

                    if (
                        !is_dir($upload_directory)
                    ) {

                        if (
                            !mkdir(
                                $upload_directory,
                                0755,
                                true
                            )
                        ) {

                            $error =
                                'Unable to create upload directory.';

                        }

                    }


                    if ($error === '') {

                        // --------------------------------------------------
                        // Generate Safe Stored File Name
                        // --------------------------------------------------

                        $stored_file_name =
                            bin2hex(
                                random_bytes(16)
                            )
                            . '_'
                            . time()
                            . '.'
                            . $extension;


                        $destination =
                            $upload_directory .
                            $stored_file_name;


                        // --------------------------------------------------
                        // Move Uploaded File
                        // --------------------------------------------------

                        if (
                            !move_uploaded_file(
                                $file['tmp_name'],
                                $destination
                            )
                        ) {

                            $error =
                                'Unable to save the uploaded file.';

                        } else {

                            try {

                                // --------------------------------------------------
                                // Start Transaction
                                // --------------------------------------------------

                                $conn->beginTransaction();


                                // --------------------------------------------------
                                // File Type
                                // --------------------------------------------------

                                $file_type =
                                    $file['type']
                                    ?? null;


                                // --------------------------------------------------
                                // Insert File Record
                                // --------------------------------------------------

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
                                        :task_document_id,
                                        :file_name,
                                        :original_name,
                                        :file_type,
                                        :file_size
                                    )
                                ");


                                $stmt->execute([
                                    ':task_document_id'
                                        => $document_id,

                                    ':file_name'
                                        => $stored_file_name,

                                    ':original_name'
                                        => $original_name,

                                    ':file_type'
                                        => $file_type,

                                    ':file_size'
                                        => (int) $file['size']
                                ]);


                                // --------------------------------------------------
                                // Activity
                                // --------------------------------------------------

                                $activity_description =
                                    'File "' .
                                    $original_name .
                                    '" was uploaded to document "' .
                                    $document['title'] .
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
                                        => 'document_file_added',

                                    ':description'
                                        => $activity_description
                                ]);


                                // --------------------------------------------------
                                // Commit
                                // --------------------------------------------------

                                $conn->commit();


                                // --------------------------------------------------
                                // Redirect
                                // --------------------------------------------------

                                header(
                                    'Location: view_task_document.php?id=' .
                                    $document_id
                                );

                                exit;


                            } catch (PDOException $e) {

                                if (
                                    $conn->inTransaction()
                                ) {

                                    $conn->rollBack();

                                }


                                // Remove physical file if
                                // database operation failed

                                if (
                                    file_exists(
                                        $destination
                                    )
                                ) {

                                    unlink(
                                        $destination
                                    );

                                }


                                $error =
                                    'The file could not be saved. Please try again.';

                            }

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

    <title>
        Upload File — Admin
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
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


<div class="flex min-h-full">


    <?php include('sidebar.php'); ?>


    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


        <?php include('header.php'); ?>


        <main class="flex flex-1 items-center justify-center p-4 lg:p-8">


            <div class="w-full max-w-2xl">


                <!-- ==================================================
                     PAGE HEADER
                =================================================== -->

                <div class="mb-6">

                    <p
                        class="text-sm font-medium text-brand-600 dark:text-brand-400"
                    >
                        Task Document
                    </p>


                    <h1
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Upload File
                    </h1>


                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Add a file to this task document.
                    </p>

                </div>


                <!-- ==================================================
                     CARD
                =================================================== -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <!-- Document Info -->

                    <div
                        class="border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                    >

                        <p
                            class="text-xs font-medium uppercase tracking-wide text-slate-400"
                        >
                            Document
                        </p>


                        <h2
                            class="mt-1 text-base font-semibold text-slate-900 dark:text-white"
                        >

                            <?= htmlspecialchars(
                                $document['title']
                            ) ?>

                        </h2>


                        <p
                            class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                        >

                            Task:
                            <?= htmlspecialchars(
                                $document['task_title']
                            ) ?>

                        </p>

                    </div>


                    <!-- ==================================================
                         ERROR
                    =================================================== -->

                    <?php if ($error !== ''): ?>

                        <div class="px-6 pt-5">

                            <div
                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                            >

                                <?= htmlspecialchars($error) ?>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- ==================================================
                         FORM
                    =================================================== -->

                    <form
                        method="POST"
                        action="upload_task_document_file.php?document_id=<?= $document_id ?>"
                        enctype="multipart/form-data"
                        class="p-6"
                    >

                        <input
                            type="hidden"
                            name="document_id"
                            value="<?= $document_id ?>"
                        >


                        <!-- Upload Area -->

                        <div>

                            <label
                                for="document_file"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >

                                Select File

                                <span class="text-red-500">*</span>

                            </label>


                            <label
                                for="document_file"
                                class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 px-6 py-12 text-center transition hover:border-brand-400 hover:bg-slate-50 dark:border-slate-700 dark:hover:border-brand-500 dark:hover:bg-slate-950"
                            >

                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-600 dark:bg-brand-950/40 dark:text-brand-400"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-7 w-7"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 16V4m0 0l-4 4m4-4l4 4M5 20h14"
                                        />

                                    </svg>

                                </div>


                                <p
                                    id="file-name"
                                    class="mt-4 text-sm font-semibold text-slate-700 dark:text-slate-300"
                                >
                                    Click to select a file
                                </p>


                                <p
                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                >
                                    Maximum file size: 20 MB
                                </p>


                                <p
                                    class="mt-1 text-xs text-slate-400"
                                >
                                    PDF, Word, Excel, PowerPoint, images,
                                    ZIP and other supported files
                                </p>


                                <input
                                    type="file"
                                    id="document_file"
                                    name="document_file"
                                    required
                                    class="hidden"
                                >

                            </label>

                        </div>


                        <!-- ==================================================
                             BUTTONS
                        =================================================== -->

                        <div
                            class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-800"
                        >

                            <button
                                type="button"
                                onclick="window.location.href='view_task_document.php?id=<?= $document_id ?>'"
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


        <?php include('footer.php'); ?>


    </div>

</div>


<script src="../dist/js/app.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const input =
        document.getElementById('document_file');

    const fileName =
        document.getElementById('file-name');


    if (!input || !fileName) {
        return;
    }


    input.addEventListener('change', function () {

        if (
            this.files &&
            this.files.length > 0
        ) {

            fileName.textContent =
                this.files[0].name;

        } else {

            fileName.textContent =
                'Click to select a file';

        }

    });

});

</script>


</body>

</html>