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
|--------------------------------------------------------------------------
*/

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

        p.name AS project_name,

        u.name AS uploaded_by_name

    FROM task_documents td

    INNER JOIN tasks t
        ON td.task_id = t.id

    INNER JOIN projects p
        ON t.project_id = p.id

    LEFT JOIN users u
        ON td.uploaded_by = u.id

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


/*
|--------------------------------------------------------------------------
| Get Files
|--------------------------------------------------------------------------
*/

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

    WHERE task_document_id = ?

    ORDER BY id DESC
");

$stmt->execute([
    $document_id
]);

$files = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Format File Size
|--------------------------------------------------------------------------
*/

function formatFileSize($bytes)
{
    if ($bytes === null || $bytes <= 0) {
        return '-';
    }

    $units = [
        'B',
        'KB',
        'MB',
        'GB'
    ];

    $i = 0;

    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }

    return round($bytes, 2) . ' ' . $units[$i];
}


/*
|--------------------------------------------------------------------------
| File Icon
|--------------------------------------------------------------------------
*/

function getFileIcon($fileType, $fileName)
{
    $extension = strtolower(
        pathinfo($fileName, PATHINFO_EXTENSION)
    );

    if ($extension === 'pdf') {

        return '
            <svg class="h-6 w-6 text-red-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7 18h10a2 2 0 002-2V8l-5-5H7a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14 3v5h5"/>
            </svg>
        ';
    }

    if (
        in_array(
            $extension,
            ['jpg', 'jpeg', 'png', 'gif', 'webp']
        )
    ) {

        return '
            <svg class="h-6 w-6 text-emerald-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"/>
                <circle cx="8.5" cy="8.5" r="1.5"/>
                <path d="M21 15l-5-5L5 21"/>
            </svg>
        ';
    }

    return '
        <svg class="h-6 w-6 text-brand-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            stroke-width="2">
            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
            <path stroke-linecap="round"
                stroke-linejoin="round"
                d="M15 3v5h5"/>
        </svg>
    ';
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
        <?= htmlspecialchars($document['title']) ?> — Task Document
    </title>

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

            <div class="container max-w-5xl">


                <!-- Header -->

                <div
                    class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <p
                            class="text-sm font-medium text-brand-600 dark:text-brand-400"
                        >
                            Task Document
                        </p>

                        <h2
                            class="mt-1 text-display-sm text-slate-900 dark:text-white"
                        >
                            <?= htmlspecialchars($document['title']) ?>
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                        >
                            Task:
                            <?= htmlspecialchars($document['task_title']) ?>
                        </p>

                    </div>


                    <div class="flex flex-wrap gap-2">

                        <button
                            type="button"
                            onclick="window.location.href='view_task.php?id=<?= (int) $document['task_id'] ?>'"
                            class="btn btn-secondary"
                        >
                            Back to Task
                        </button>

                        <a
                            href="upload_task_document_file.php?document_id=<?= (int) $document['id'] ?>"
                            class="btn btn-primary"
                        >
                            Upload File
                        </a>

                    </div>

                </div>


                <!-- Document Details -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            Document Details
                        </h3>

                    </div>


                    <div class="grid gap-5 p-5 sm:grid-cols-2">


                        <div>

                            <p
                                class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                            >
                                Project
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                            >
                                <?= htmlspecialchars($document['project_name']) ?>
                            </p>

                        </div>


                        <div>

                            <p
                                class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                            >
                                Task
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                            >
                                <?= htmlspecialchars($document['task_title']) ?>
                            </p>

                        </div>


                        <div>

                            <p
                                class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                            >
                                Uploaded By
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                            >
                                <?= htmlspecialchars(
                                    $document['uploaded_by_name'] ?? 'Unknown'
                                ) ?>
                            </p>

                        </div>


                        <div>

                            <p
                                class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                            >
                                Created
                            </p>

                            <p
                                class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                            >
                                <?= date(
                                    'd M Y, h:i A',
                                    strtotime($document['created_at'])
                                ) ?>
                            </p>

                        </div>


                        <?php if (!empty($document['description'])): ?>

                            <div class="sm:col-span-2">

                                <p
                                    class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                >
                                    Description
                                </p>

                                <p
                                    class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300"
                                >
                                    <?= htmlspecialchars(
                                        $document['description']
                                    ) ?>
                                </p>

                            </div>

                        <?php endif; ?>


                    </div>

                </div>


                <!-- Files -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800"
                    >

                        <div>

                            <h3
                                class="font-semibold text-slate-900 dark:text-white"
                            >
                                Files
                            </h3>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                <?= count($files) ?>
                                file<?= count($files) === 1 ? '' : 's' ?>
                            </p>

                        </div>

                        <a
                            href="upload_task_document_file.php?document_id=<?= (int) $document['id'] ?>"
                            class="btn btn-primary"
                        >
                            Upload File
                        </a>

                    </div>


                    <?php if (empty($files)): ?>

                        <div class="px-5 py-14 text-center">

                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
                            >

                                <svg
                                    class="h-6 w-6 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 4v16m8-8H4"
                                    />

                                </svg>

                            </div>

                            <h4
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No files uploaded
                            </h4>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                Upload the first file for this document.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="divide-y divide-slate-200 dark:divide-slate-800">

                            <?php foreach ($files as $file): ?>

                                <div
                                    class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
                                >

                                    <div class="flex min-w-0 items-center gap-3">

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800"
                                        >

                                            <?= getFileIcon(
                                                $file['file_type'],
                                                $file['original_name']
                                            ) ?>

                                        </div>


                                        <div class="min-w-0">

                                            <p
                                                class="truncate text-sm font-medium text-slate-900 dark:text-white"
                                            >
                                                <?= htmlspecialchars(
                                                    $file['original_name']
                                                ) ?>
                                            </p>

                                            <p
                                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                <?= htmlspecialchars(
                                                    $file['file_type'] ?: 'Unknown type'
                                                ) ?>

                                                <span class="mx-1">•</span>

                                                <?= htmlspecialchars(
                                                    formatFileSize($file['file_size'])
                                                ) ?>

                                                <span class="mx-1">•</span>

                                                <?= date(
                                                    'd M Y',
                                                    strtotime($file['created_at'])
                                                ) ?>
                                            </p>

                                        </div>

                                    </div>


                                    <div class="flex shrink-0 gap-2">

                                        <a
                                            href="download_task_document_file.php?id=<?= (int) $file['id'] ?>"
                                            class="btn btn-secondary"
                                        >
                                            Download
                                        </a>

                                        <a
                                            href="delete_task_document_file.php?id=<?= (int) $file['id'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this file?');"
                                            class="btn btn-secondary text-red-600 hover:text-red-700 dark:text-red-400"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>


            </div>

        </main>

    </div>

</div>


<script src="../../dist/js/app.js"></script>

</body>

</html>