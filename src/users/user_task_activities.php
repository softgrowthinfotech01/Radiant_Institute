<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$task_id = isset($_GET['task_id']) ? (int) $_GET['task_id'] : 0;

if ($task_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Check Task Access
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        t.id,
        t.title,
        t.project_id,
        p.name AS project_name

    FROM tasks t

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    INNER JOIN projects p
        ON t.project_id = p.id

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
| Get Task Activities
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        ta.id,
        ta.activity_type,
        ta.description,
        ta.created_at,
        u.name AS user_name

    FROM task_activities ta

    LEFT JOIN users u
        ON ta.user_id = u.id

    WHERE ta.task_id = ?

    ORDER BY ta.created_at DESC, ta.id DESC
");

$stmt->execute([$task_id]);

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Activity Icon
|--------------------------------------------------------------------------
*/

function activityIcon($type)
{
    switch ($type) {

        case 'status_changed':
            return '
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 12h16m-7-7l7 7-7 7"/>
                </svg>
            ';

        case 'document_added':
            return '
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            ';

        case 'comment_added':
            return '
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 10h8m-8 4h5m8-2a8 8 0 11-16 0 8 8 0 0016 0z"/>
                </svg>
            ';

        case 'subtask_added':
            return '
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5h6M9 9h6M9 13h6M9 17h6M5 5h.01M5 9h.01M5 13h.01M5 17h.01"/>
                </svg>
            ';

        default:
            return '
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            ';
    }
}


/*
|--------------------------------------------------------------------------
| Activity Label
|--------------------------------------------------------------------------
*/

function activityLabel($type)
{
    switch ($type) {

        case 'status_changed':
            return 'Status Changed';

        case 'document_added':
            return 'Document Added';

        case 'comment_added':
            return 'Comment Added';

        case 'subtask_added':
            return 'Subtask Added';

        default:
            return ucfirst(str_replace('_', ' ', $type));
    }
}


/*
|--------------------------------------------------------------------------
| Format Date
|--------------------------------------------------------------------------
*/

function formatActivityDate($date)
{
    if (empty($date)) {
        return '-';
    }

    return date('d M Y, h:i A', strtotime($date));
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

            <div class="container max-w-4xl">


                <!-- Header -->

                <div
                    class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <p
                            class="text-sm font-medium text-brand-600 dark:text-brand-400"
                        >
                            Task History
                        </p>

                        <h2
                            class="mt-1 text-display-sm text-slate-900 dark:text-white"
                        >
                            Activity
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                        >
                            <?= htmlspecialchars($task['title']) ?>
                        </p>

                    </div>


                    <div class="flex gap-2">

                        <button
                            type="button"
                            onclick="window.location.href='view_task.php?id=<?= (int) $task_id ?>'"
                            class="btn btn-secondary"
                        >
                            Back to Task
                        </button>

                    </div>

                </div>


                <!-- Task Information -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <p
                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                    >
                        Project
                    </p>

                    <p
                        class="mt-1 font-medium text-slate-900 dark:text-white"
                    >
                        <?= htmlspecialchars($task['project_name']) ?>
                    </p>

                </div>


                <!-- Activities -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="text-base font-semibold text-slate-900 dark:text-white"
                        >
                            Task Activity
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            <?= count($activities) ?>
                            activity<?= count($activities) === 1 ? '' : 'ies' ?>
                        </p>

                    </div>


                    <?php if (empty($activities)): ?>

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
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />

                                </svg>

                            </div>


                            <h4
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No activity yet
                            </h4>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                Activity related to this task will appear here.
                            </p>

                        </div>

                    <?php else: ?>


                        <div class="p-5">

                            <div class="relative">


                                <!-- Timeline Line -->

                                <div
                                    class="absolute bottom-0 left-5 top-0 w-px bg-slate-200 dark:bg-slate-800"
                                ></div>


                                <?php foreach ($activities as $activity): ?>

                                    <div
                                        class="relative flex gap-4 pb-8 last:pb-0"
                                    >


                                        <!-- Icon -->

                                        <div
                                            class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-brand-600 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-brand-400"
                                        >

                                            <?= activityIcon(
                                                $activity['activity_type']
                                            ) ?>

                                        </div>


                                        <!-- Content -->

                                        <div
                                            class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950"
                                        >

                                            <div
                                                class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                                            >

                                                <p
                                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                                >
                                                    <?= htmlspecialchars(
                                                        activityLabel(
                                                            $activity['activity_type']
                                                        )
                                                    ) ?>
                                                </p>

                                                <time
                                                    class="text-xs text-slate-500 dark:text-slate-400"
                                                >
                                                    <?= htmlspecialchars(
                                                        formatActivityDate(
                                                            $activity['created_at']
                                                        )
                                                    ) ?>
                                                </time>

                                            </div>


                                            <?php if (!empty($activity['description'])): ?>

                                                <p
                                                    class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300"
                                                >
                                                    <?= htmlspecialchars(
                                                        $activity['description']
                                                    ) ?>
                                                </p>

                                            <?php endif; ?>


                                            <p
                                                class="mt-3 text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                By
                                                <span
                                                    class="font-medium text-slate-700 dark:text-slate-300"
                                                >
                                                    <?= htmlspecialchars(
                                                        $activity['user_name'] ?? 'Unknown User'
                                                    ) ?>
                                                </span>
                                            </p>

                                        </div>


                                    </div>

                                <?php endforeach; ?>


                            </div>

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