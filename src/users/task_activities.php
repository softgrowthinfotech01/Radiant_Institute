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
| Get Task + Verify User Assignment
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        t.id,
        t.title,
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
| Get Activities
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        a.id,
        a.task_id,
        a.user_id,
        a.activity_type,
        a.description,
        a.created_at,

        u.name AS user_name

    FROM task_activities a

    LEFT JOIN users u
        ON a.user_id = u.id

    WHERE a.task_id = ?

    ORDER BY a.created_at DESC, a.id DESC
");

$stmt->execute([
    $task_id
]);

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Activity Icon
|--------------------------------------------------------------------------
*/

function activityIcon($type)
{
    switch ($type) {

        case 'task_created':
            return '
                <svg class="h-5 w-5 text-brand-600 dark:text-brand-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>
            ';

        case 'status_updated':
        case 'task_status_updated':
            return '
                <svg class="h-5 w-5 text-amber-600 dark:text-amber-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            ';

        case 'comment_added':
            return '
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8 10h8M8 14h5"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M19 4H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"/>
                </svg>
            ';

        case 'document_file_deleted':
        case 'task_document_updated':
            return '
                <svg class="h-5 w-5 text-red-600 dark:text-red-400"
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

        default:
            return '
                <svg class="h-5 w-5 text-slate-500 dark:text-slate-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 8v4l3 2"/>
                    <circle cx="12"
                            cy="12"
                            r="9"/>
                </svg>
            ';
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

    <title>Task Activities — User</title>

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
                            Task Activity
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


                    <button
                        type="button"
                        onclick="window.location.href='view_task.php?id=<?= (int) $task_id ?>'"
                        class="btn btn-secondary"
                    >
                        Back to Task
                    </button>

                </div>


                <!-- Activity List -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            Activity History
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            <?= count($activities) ?>
                            activit<?= count($activities) === 1 ? 'y' : 'ies' ?>
                        </p>

                    </div>


                    <?php if (empty($activities)): ?>

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
                                        d="M12 8v4l3 2"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                    />

                                </svg>

                            </div>

                            <h3
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No activity yet
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                Activity related to this task will appear here.
                            </p>

                        </div>


                    <?php else: ?>

                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($activities as $activity): ?>

                                <div class="flex gap-4 px-5 py-5">

                                    <!-- Icon -->

                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800"
                                    >

                                        <?= activityIcon(
                                            $activity['activity_type']
                                        ) ?>

                                    </div>


                                    <!-- Content -->

                                    <div class="min-w-0 flex-1">

                                        <div
                                            class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                                        >

                                            <p
                                                class="text-sm font-semibold text-slate-900 dark:text-white"
                                            >
                                                <?= htmlspecialchars(
                                                    ucwords(
                                                        str_replace(
                                                            '_',
                                                            ' ',
                                                            $activity['activity_type']
                                                        )
                                                    )
                                                ) ?>
                                            </p>

                                            <span
                                                class="text-xs text-slate-400"
                                            >
                                                <?= date(
                                                    'd M Y, h:i A',
                                                    strtotime(
                                                        $activity['created_at']
                                                    )
                                                ) ?>
                                            </span>

                                        </div>


                                        <?php if (!empty($activity['description'])): ?>

                                            <p
                                                class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300"
                                            >
                                                <?= htmlspecialchars(
                                                    $activity['description']
                                                ) ?>
                                            </p>

                                        <?php endif; ?>


                                        <p
                                            class="mt-2 text-xs text-slate-400 dark:text-slate-500"
                                        >
                                            By
                                            <?= htmlspecialchars(
                                                $activity['user_name'] ?? 'Unknown User'
                                            ) ?>
                                        </p>

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