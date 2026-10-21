<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$task_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($task_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Task
|--------------------------------------------------------------------------
| User must be assigned to this task.
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        t.id,
        t.title,
        t.description,
        t.status,
        t.priority,

        p.id AS project_id,
        p.name AS project_name

    FROM tasks t

    INNER JOIN projects p
        ON t.project_id = p.id

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE t.id = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $task_id,
    $user_id
]);

$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Task Documents
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        td.id,
        td.title,
        td.description,
        td.uploaded_by,
        td.created_at,

        u.name AS uploaded_by_name,

        (
            SELECT COUNT(*)
            FROM task_document_files tdf
            WHERE tdf.task_document_id = td.id
        ) AS file_count

    FROM task_documents td

    LEFT JOIN users u
        ON td.uploaded_by = u.id

    WHERE td.task_id = ?

    ORDER BY td.id DESC
");

$stmt->execute([
    $task_id
]);

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Status Badge
|--------------------------------------------------------------------------
*/

function documentStatusClass($count)
{
    if ($count > 0) {
        return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400';
    }

    return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
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

    <title>Task Documents — User</title>

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

            <div class="container max-w-6xl">


                <!-- Header -->

                <div
                    class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <p
                            class="text-sm font-medium text-brand-600 dark:text-brand-400"
                        >
                            Task Documents
                        </p>

                        <h2
                            class="mt-1 text-display-sm text-slate-900 dark:text-white"
                        >
                            <?= htmlspecialchars($task['title']) ?>
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                        >
                            Project:
                            <?= htmlspecialchars($task['project_name']) ?>
                        </p>

                    </div>


                    <div class="flex flex-wrap gap-2">

                        <button
                            type="button"
                            onclick="window.location.href='view_task.php?id=<?= (int) $task_id ?>'"
                            class="btn btn-secondary"
                        >
                            Back to Task
                        </button>

                        <a
                            href="add_task_document.php?task_id=<?= (int) $task_id ?>"
                            class="btn btn-primary"
                        >
                            Add Document
                        </a>

                    </div>

                </div>


                <!-- Documents -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <div>

                            <h3
                                class="font-semibold text-slate-900 dark:text-white"
                            >
                                Documents
                            </h3>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                <?= count($documents) ?>
                                document<?= count($documents) === 1 ? '' : 's' ?>
                            </p>

                        </div>

                    </div>


                    <?php if (empty($documents)): ?>

                        <div class="px-5 py-16 text-center">

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
                                        d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 3v5h5"
                                    />

                                </svg>

                            </div>


                            <h3
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No documents found
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                No documents have been added to this task yet.
                            </p>


                            <div class="mt-5">

                                <a
                                    href="add_task_document.php?task_id=<?= (int) $task_id ?>"
                                    class="btn btn-primary"
                                >
                                    Add Document
                                </a>

                            </div>

                        </div>


                    <?php else: ?>


                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($documents as $document): ?>

                                <div
                                    class="flex flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between"
                                >

                                    <div class="flex min-w-0 items-start gap-4">

                                        <!-- Icon -->

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-50 dark:bg-brand-950/40"
                                        >

                                            <svg
                                                class="h-5 w-5 text-brand-600 dark:text-brand-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                stroke-width="2"
                                            >

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                                                />

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M15 3v5h5"
                                                />

                                            </svg>

                                        </div>


                                        <!-- Details -->

                                        <div class="min-w-0">

                                            <h4
                                                class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                                            >
                                                <?= htmlspecialchars($document['title']) ?>
                                            </h4>


                                            <?php if (!empty($document['description'])): ?>

                                                <p
                                                    class="mt-1 line-clamp-2 text-sm text-slate-500 dark:text-slate-400"
                                                >
                                                    <?= htmlspecialchars($document['description']) ?>
                                                </p>

                                            <?php endif; ?>


                                            <div
                                                class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400"
                                            >

                                                <span>
                                                    By
                                                    <?= htmlspecialchars(
                                                        $document['uploaded_by_name'] ?? 'Unknown'
                                                    ) ?>
                                                </span>

                                                <span>•</span>

                                                <span>
                                                    <?= date(
                                                        'd M Y',
                                                        strtotime($document['created_at'])
                                                    ) ?>
                                                </span>

                                                <span>•</span>

                                                <span
                                                    class="rounded-full px-2 py-1 <?= documentStatusClass(
                                                        (int) $document['file_count']
                                                    ) ?>"
                                                >
                                                    <?= (int) $document['file_count'] ?>
                                                    file<?= (int) $document['file_count'] === 1 ? '' : 's' ?>
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Actions -->

                                    <div class="flex shrink-0 gap-2">

                                        <a
                                            href="view_task_document.php?id=<?= (int) $document['id'] ?>"
                                            class="btn btn-secondary"
                                        >
                                            View
                                        </a>

                                        <a
                                            href="edit_task_document.php?id=<?= (int) $document['id'] ?>"
                                            class="btn btn-secondary"
                                        >
                                            Edit
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