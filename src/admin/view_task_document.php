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
        td.uploaded_by,
        td.created_at,
        t.title AS task_title,
        t.project_id,
        u.name AS uploaded_by_name
    FROM task_documents td

    INNER JOIN tasks t
        ON t.id = td.task_id

    LEFT JOIN users u
        ON u.id = td.uploaded_by

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
// Get Document Files
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        id,
        task_document_id,
        file_name,
        original_name,
        file_type,
        file_size,
        created_at
    FROM task_document_files
    WHERE task_document_id = :document_id
    ORDER BY created_at DESC, id DESC
");

$stmt->execute([
    ':document_id' => $document_id
]);

$files = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Helper - File Size
// --------------------------------------------------

function formatFileSize($bytes)
{
    if ($bytes === null || $bytes <= 0) {
        return 'Unknown size';
    }

    $units = [
        'B',
        'KB',
        'MB',
        'GB',
        'TB'
    ];

    $i = 0;

    while (
        $bytes >= 1024 &&
        $i < count($units) - 1
    ) {
        $bytes /= 1024;
        $i++;
    }

    return number_format($bytes, 2) . ' ' . $units[$i];
}


// --------------------------------------------------
// Helper - File Extension
// --------------------------------------------------

function getFileExtension($filename)
{
    return strtoupper(
        pathinfo(
            $filename,
            PATHINFO_EXTENSION
        )
    );
}


// --------------------------------------------------
// Helper - File Icon Type
// --------------------------------------------------

function getFileIconType($filename)
{
    $extension = strtolower(
        pathinfo(
            $filename,
            PATHINFO_EXTENSION
        )
    );

    if (
        in_array(
            $extension,
            ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']
        )
    ) {
        return 'image';
    }

    if ($extension === 'pdf') {
        return 'pdf';
    }

    if (
        in_array(
            $extension,
            ['doc', 'docx']
        )
    ) {
        return 'word';
    }

    if (
        in_array(
            $extension,
            ['xls', 'xlsx', 'csv']
        )
    ) {
        return 'excel';
    }

    if (
        in_array(
            $extension,
            ['zip', 'rar', '7z']
        )
    ) {
        return 'archive';
    }

    return 'file';
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($document['title']) ?>
        — Task Document
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>


    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="../../dist/css/output.css">

</head>


<body
    class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">


    <div class="flex min-h-full">


        <?php include('sidebar.php'); ?>


        <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


            <?php include('header.php'); ?>


            <main class="flex-1 p-4 lg:p-8">


                <div class="mx-auto max-w-5xl">


                    <!-- ==================================================
                     PAGE HEADER
                =================================================== -->

                    <div
                        class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p
                                class="text-sm font-medium text-brand-600 dark:text-brand-400">
                                Task Document
                            </p>


                            <h1
                                class="mt-1 text-display-sm text-slate-900 dark:text-white">

                                <?= htmlspecialchars(
                                    $document['title']
                                ) ?>

                            </h1>


                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                                Attached to:
                                <?= htmlspecialchars(
                                    $document['task_title']
                                ) ?>

                            </p>

                        </div>


                        <div class="flex flex-wrap gap-2">


                            <a
                                href="view_task.php?id=<?= $task_id ?>"
                                class="btn btn-secondary">
                                Back to Task
                            </a>


                            <a
                                href="edit_task_document.php?id=<?= $document_id ?>"
                                class="btn btn-primary">
                                Edit Document
                            </a>

                        </div>

                    </div>


                    <!-- ==================================================
                     DOCUMENT DETAILS
                =================================================== -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">


                        <div
                            class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">

                            <div class="flex items-center gap-3">


                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-100 text-brand-600 dark:bg-brand-950/40 dark:text-brand-400">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586A2 2 0 0114 3.586L19.414 9A2 2 0 0120 10.414V19a2 2 0 01-2 2z" />

                                    </svg>

                                </div>


                                <div>

                                    <h2
                                        class="text-base font-semibold text-slate-900 dark:text-white">
                                        Document Details
                                    </h2>


                                    <p
                                        class="text-sm text-slate-500 dark:text-slate-400">
                                        Document information and attached files.
                                    </p>

                                </div>

                            </div>

                        </div>


                        <div class="p-6">


                            <!-- Description -->

                            <div>

                                <p
                                    class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    Description
                                </p>


                                <?php if (
                                    !empty($document['description'])
                                ): ?>

                                    <div
                                        class="mt-2 whitespace-pre-wrap text-sm leading-6 text-slate-700 dark:text-slate-300">

                                        <?= htmlspecialchars(
                                            $document['description']
                                        ) ?>

                                    </div>

                                <?php else: ?>

                                    <p
                                        class="mt-2 text-sm italic text-slate-400">
                                        No description provided.
                                    </p>

                                <?php endif; ?>

                            </div>


                            <!-- Meta -->

                            <div
                                class="mt-6 grid gap-4 border-t border-slate-200 pt-5 sm:grid-cols-3 dark:border-slate-800">

                                <div>

                                    <p
                                        class="text-xs text-slate-400">
                                        Document ID
                                    </p>


                                    <p
                                        class="mt-1 text-sm font-medium">
                                        #<?= $document_id ?>
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs text-slate-400">
                                        Uploaded By
                                    </p>


                                    <p
                                        class="mt-1 text-sm font-medium">

                                        <?= htmlspecialchars(
                                            $document['uploaded_by_name']
                                                ?? 'Unknown User'
                                        ) ?>

                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs text-slate-400">
                                        Created
                                    </p>


                                    <p
                                        class="mt-1 text-sm font-medium">

                                        <?= htmlspecialchars(
                                            date(
                                                'd M Y, h:i A',
                                                strtotime(
                                                    $document['created_at']
                                                )
                                            )
                                        ) ?>

                                    </p>

                                </div>

                            </div>


                        </div>

                    </div>


                    <!-- ==================================================
                     FILES
                =================================================== -->

                    <div
                        class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">


                        <!-- Header -->

                        <div
                            class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">

                            <div>

                                <h2
                                    class="text-base font-semibold text-slate-900 dark:text-white">
                                    Attached Files
                                </h2>


                                <p
                                    class="mt-1 text-sm text-slate-500 dark:text-slate-400">

                                    <?= count($files) ?>
                                    file<?= count($files) === 1 ? '' : 's' ?>
                                    attached to this document.

                                </p>

                            </div>


                            <a
                                href="upload_task_document_file.php?document_id=<?= $document_id ?>"
                                class="btn btn-primary">
                                Upload File
                            </a>

                        </div>


                        <!-- Files -->

                        <div class="p-6">


                            <?php if (empty($files)): ?>


                                <div
                                    class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700">

                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2">

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M12 16V4m0 0l-4 4m4-4l4 4M5 20h14" />

                                        </svg>

                                    </div>


                                    <p
                                        class="mt-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                        No files attached
                                    </p>


                                    <p
                                        class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Upload a file to this document.
                                    </p>


                                    <a
                                        href="upload_task_document_file.php?document_id=<?= $document_id ?>"
                                        class="mt-4 inline-flex text-sm font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">
                                        Upload a file
                                    </a>

                                </div>


                            <?php else: ?>


                                <div class="space-y-3">


                                    <?php foreach ($files as $file): ?>


                                        <?php
                                        $extension =
                                            getFileExtension(
                                                $file['original_name']
                                            );

                                        $icon_type =
                                            getFileIconType(
                                                $file['original_name']
                                            );
                                        ?>


                                        <div
                                            class="flex flex-col gap-4 rounded-xl border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800">


                                            <div
                                                class="flex min-w-0 items-center gap-4">


                                                <!-- File Icon -->

                                                <div
                                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">

                                                    <?php if (
                                                        $icon_type === 'image'
                                                    ): ?>

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2">

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M4 16l4-4a1 1 0 011.414 0L14 16l2-2a1 1 0 011.414 0L20 16M5 20h14a1 1 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />

                                                        </svg>


                                                    <?php elseif (
                                                        $icon_type === 'pdf'
                                                    ): ?>

                                                        <span
                                                            class="text-xs font-bold">
                                                            PDF
                                                        </span>


                                                    <?php elseif (
                                                        $icon_type === 'word'
                                                    ): ?>

                                                        <span
                                                            class="text-xs font-bold">
                                                            DOC
                                                        </span>


                                                    <?php elseif (
                                                        $icon_type === 'excel'
                                                    ): ?>

                                                        <span
                                                            class="text-xs font-bold">
                                                            XLS
                                                        </span>


                                                    <?php else: ?>

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-5 w-5"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            stroke-width="2">

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />

                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="M14 3v6h6" />

                                                        </svg>

                                                    <?php endif; ?>

                                                </div>


                                                <!-- File Info -->

                                                <div class="min-w-0">


                                                    <p
                                                        class="truncate text-sm font-semibold text-slate-900 dark:text-white">

                                                        <?= htmlspecialchars(
                                                            $file['original_name']
                                                        ) ?>

                                                    </p>


                                                    <div
                                                        class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-500 dark:text-slate-400">

                                                        <span>
                                                            <?= htmlspecialchars(
                                                                $extension ?: 'FILE'
                                                            ) ?>
                                                        </span>


                                                        <span>
                                                            <?= htmlspecialchars(
                                                                formatFileSize(
                                                                    $file['file_size']
                                                                )
                                                            ) ?>
                                                        </span>


                                                        <span>
                                                            <?= htmlspecialchars(
                                                                date(
                                                                    'd M Y, h:i A',
                                                                    strtotime(
                                                                        $file['created_at']
                                                                    )
                                                                )
                                                            ) ?>
                                                        </span>

                                                    </div>

                                                </div>

                                            </div>


                                            <!-- Actions -->

                                            <div
                                                class="flex shrink-0 items-center gap-2">

                                                <a
                                                    href="download_task_document_file.php?id=<?= (int) $file['id'] ?>"
                                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                                    Download
                                                </a>


                                                <a
                                                    href="delete_task_document_file.php?id=<?= (int) $file['id'] ?>"
                                                    onclick="return confirm('Are you sure you want to delete this file? This action cannot be undone.');"
                                                    class="inline-flex items-center justify-center rounded-xl px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                                                    Delete
                                                </a>

                                            </div>


                                        </div>


                                    <?php endforeach; ?>


                                </div>


                            <?php endif; ?>


                        </div>

                    </div>


                </div>


            </main>


            <?php include('footer.php'); ?>


        </div>

    </div>


    <script src="../dist/js/app.js"></script>


</body>

</html>