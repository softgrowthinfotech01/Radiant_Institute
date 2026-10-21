<?php

include('../check.php');
include('../conn.php');


/*
|--------------------------------------------------------------------------
| Get Task Documents
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
        t.status AS task_status,
        t.priority AS task_priority,

        p.id AS project_id,
        p.name AS project_name,

        u.name AS uploaded_by_name,

        (
            SELECT COUNT(*)
            FROM task_document_files tdf
            WHERE tdf.task_document_id = td.id
        ) AS file_count

    FROM task_documents td

    INNER JOIN tasks t
        ON td.task_id = t.id

    INNER JOIN projects p
        ON t.project_id = p.id

    LEFT JOIN users u
        ON td.uploaded_by = u.id

    ORDER BY td.created_at DESC, td.id DESC
");

$stmt->execute();

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Status Badge
|--------------------------------------------------------------------------
*/

function taskStatusBadge($status)
{
    switch ($status) {

        case 'completed':
            return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400';

        case 'in_progress':
            return 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400';

        case 'on_hold':
            return 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400';

        case 'cancelled':
            return 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400';

        default:
            return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
    }
}


/*
|--------------------------------------------------------------------------
| Priority Badge
|--------------------------------------------------------------------------
*/

function priorityBadge($priority)
{
    switch ($priority) {

        case 'urgent':
            return 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400';

        case 'high':
            return 'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400';

        case 'medium':
            return 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/40 dark:text-yellow-400';

        default:
            return 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300';
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

    <title>Task Documents — Admin</title>

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

            <div class="container max-w-7xl">


                <!-- Page Header -->

                <div
                    class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <p
                            class="text-sm font-medium text-brand-600 dark:text-brand-400"
                        >
                            Task Management
                        </p>

                        <h2
                            class="mt-1 text-display-sm text-slate-900 dark:text-white"
                        >
                            Task Documents
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                        >
                            Manage documents attached to tasks.
                        </p>

                    </div>


                    <div>

                        <span
                            class="inline-flex items-center rounded-full bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                        >
                            <?= count($documents) ?>
                            document<?= count($documents) === 1 ? '' : 's' ?>
                        </span>

                    </div>

                </div>


                <!-- Documents -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            All Task Documents
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            Documents uploaded against tasks and projects.
                        </p>

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
                                        d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14 2v6h6"
                                    />

                                </svg>

                            </div>


                            <h3
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No task documents found
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                No documents have been uploaded to any task yet.
                            </p>

                        </div>


                    <?php else: ?>


                        <!-- Desktop Table -->

                        <div class="hidden overflow-x-auto md:block">

                            <table class="w-full text-left">

                                <thead>

                                    <tr
                                        class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/50"
                                    >

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Document
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Project
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Task
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Status
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Files
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Uploaded By
                                        </th>

                                        <th
                                            class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody
                                    class="divide-y divide-slate-200 dark:divide-slate-800"
                                >

                                    <?php foreach ($documents as $document): ?>

                                        <tr
                                            class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/40"
                                        >

                                            <!-- Document -->

                                            <td class="px-5 py-4">

                                                <div>

                                                    <p
                                                        class="font-medium text-slate-900 dark:text-white"
                                                    >
                                                        <?= htmlspecialchars(
                                                            $document['title']
                                                        ) ?>
                                                    </p>

                                                    <p
                                                        class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                                    >
                                                        <?= date(
                                                            'd M Y, h:i A',
                                                            strtotime(
                                                                $document['created_at']
                                                            )
                                                        ) ?>
                                                    </p>

                                                </div>

                                            </td>


                                            <!-- Project -->

                                            <td class="px-5 py-4">

                                                <a
                                                    href="view_project.php?id=<?= (int) $document['project_id'] ?>"
                                                    class="text-sm font-medium text-brand-600 hover:underline dark:text-brand-400"
                                                >
                                                    <?= htmlspecialchars(
                                                        $document['project_name']
                                                    ) ?>
                                                </a>

                                            </td>


                                            <!-- Task -->

                                            <td class="px-5 py-4">

                                                <a
                                                    href="view_task.php?id=<?= (int) $document['task_id'] ?>"
                                                    class="text-sm font-medium text-slate-700 hover:text-brand-600 dark:text-slate-300 dark:hover:text-brand-400"
                                                >
                                                    <?= htmlspecialchars(
                                                        $document['task_title']
                                                    ) ?>
                                                </a>

                                                <div class="mt-1">

                                                    <span
                                                        class="rounded-full px-2 py-1 text-xs font-medium <?= priorityBadge($document['task_priority']) ?>"
                                                    >
                                                        <?= htmlspecialchars(
                                                            ucfirst(
                                                                $document['task_priority']
                                                            )
                                                        ) ?>
                                                    </span>

                                                </div>

                                            </td>


                                            <!-- Status -->

                                            <td class="px-5 py-4">

                                                <span
                                                    class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskStatusBadge($document['task_status']) ?>"
                                                >
                                                    <?= htmlspecialchars(
                                                        ucwords(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $document['task_status']
                                                            )
                                                        )
                                                    ) ?>
                                                </span>

                                            </td>


                                            <!-- Files -->

                                            <td class="px-5 py-4">

                                                <span
                                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                                                >

                                                    <?= (int) $document['file_count'] ?>

                                                    file<?= (int) $document['file_count'] === 1 ? '' : 's' ?>

                                                </span>

                                            </td>


                                            <!-- Uploaded By -->

                                            <td class="px-5 py-4">

                                                <span
                                                    class="text-sm text-slate-600 dark:text-slate-400"
                                                >
                                                    <?= htmlspecialchars(
                                                        $document['uploaded_by_name'] ?? 'Unknown'
                                                    ) ?>
                                                </span>

                                            </td>


                                            <!-- Actions -->

                                            <td class="px-5 py-4">

                                                <div
                                                    class="flex justify-end gap-2"
                                                >

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

                                                    <a
                                                        href="delete_task_document.php?id=<?= (int) $document['id'] ?>"
                                                        class="btn btn-secondary text-red-600 hover:text-red-700"
                                                        onclick="return confirm('Are you sure you want to delete this task document?');"
                                                    >
                                                        Delete
                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>


                        <!-- Mobile Cards -->

                        <div
                            class="divide-y divide-slate-200 md:hidden dark:divide-slate-800"
                        >

                            <?php foreach ($documents as $document): ?>

                                <div class="p-5">

                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >

                                        <div class="min-w-0">

                                            <h4
                                                class="font-semibold text-slate-900 dark:text-white"
                                            >
                                                <?= htmlspecialchars(
                                                    $document['title']
                                                ) ?>
                                            </h4>

                                            <p
                                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                <?= htmlspecialchars(
                                                    $document['project_name']
                                                ) ?>
                                            </p>

                                        </div>


                                        <span
                                            class="shrink-0 rounded-full px-2 py-1 text-xs font-medium <?= taskStatusBadge($document['task_status']) ?>"
                                        >
                                            <?= htmlspecialchars(
                                                ucwords(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $document['task_status']
                                                    )
                                                )
                                            ) ?>
                                        </span>

                                    </div>


                                    <div
                                        class="mt-4 space-y-2 text-sm"
                                    >

                                        <p class="text-slate-600 dark:text-slate-400">

                                            <span class="font-medium">
                                                Task:
                                            </span>

                                            <?= htmlspecialchars(
                                                $document['task_title']
                                            ) ?>

                                        </p>


                                        <p class="text-slate-600 dark:text-slate-400">

                                            <span class="font-medium">
                                                Files:
                                            </span>

                                            <?= (int) $document['file_count'] ?>

                                        </p>


                                        <p class="text-slate-600 dark:text-slate-400">

                                            <span class="font-medium">
                                                Uploaded By:
                                            </span>

                                            <?= htmlspecialchars(
                                                $document['uploaded_by_name'] ?? 'Unknown'
                                            ) ?>

                                        </p>

                                    </div>


                                    <div
                                        class="mt-5 flex flex-wrap gap-2"
                                    >

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

                                        <a
                                            href="delete_task_document.php?id=<?= (int) $document['id'] ?>"
                                            class="btn btn-secondary text-red-600"
                                            onclick="return confirm('Are you sure you want to delete this task document?');"
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