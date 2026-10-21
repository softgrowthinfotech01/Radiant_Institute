<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$project_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($project_id <= 0) {
    header("Location: projects.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Project
| User must have at least one assigned task in this project
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT DISTINCT
        p.id,
        p.name,
        p.description,
        p.status,
        p.start_date,
        p.due_date

    FROM projects p

    INNER JOIN tasks t
        ON t.project_id = p.id

    INNER JOIN task_assignees ta
        ON ta.task_id = t.id

    WHERE p.id = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $project_id,
    $user_id
]);

$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    header("Location: projects.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Project Tasks Assigned To Current User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        t.id,
        t.title,
        t.description,
        t.priority,
        t.status,
        t.start_date,
        t.due_date,
        t.created_at,

        p.name AS project_name

    FROM tasks t

    INNER JOIN projects p
        ON t.project_id = p.id

    INNER JOIN task_assignees ta
        ON ta.task_id = t.id

    WHERE t.project_id = ?
      AND ta.user_id = ?

    ORDER BY
        CASE t.status
            WHEN 'in_progress' THEN 1
            WHEN 'pending' THEN 2
            WHEN 'on_hold' THEN 3
            WHEN 'completed' THEN 4
            WHEN 'cancelled' THEN 5
            ELSE 6
        END,
        t.due_date ASC,
        t.id DESC
");

$stmt->execute([
    $project_id,
    $user_id
]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Counts
|--------------------------------------------------------------------------
*/

$totalTasks = count($tasks);

$pendingTasks = 0;
$inProgressTasks = 0;
$completedTasks = 0;

foreach ($tasks as $task) {

    if ($task['status'] === 'pending') {
        $pendingTasks++;
    }

    if ($task['status'] === 'in_progress') {
        $inProgressTasks++;
    }

    if ($task['status'] === 'completed') {
        $completedTasks++;
    }
}


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

function taskPriorityBadge($priority)
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

    <title>
        <?= htmlspecialchars($project['name']) ?> — Tasks
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

            <div class="container max-w-6xl">


                <!-- Header -->

                <div
                    class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <p
                            class="text-sm font-medium text-brand-600 dark:text-brand-400"
                        >
                            Project Tasks
                        </p>

                        <h2
                            class="mt-1 text-display-sm text-slate-900 dark:text-white"
                        >
                            <?= htmlspecialchars($project['name']) ?>
                        </h2>

                        <?php if (!empty($project['description'])): ?>

                            <p
                                class="mt-1 max-w-2xl text-sm text-slate-600 dark:text-slate-400"
                            >
                                <?= htmlspecialchars($project['description']) ?>
                            </p>

                        <?php endif; ?>

                    </div>


                    <button
                        type="button"
                        onclick="window.location.href='view_project.php?id=<?= (int) $project_id ?>'"
                        class="btn btn-secondary"
                    >
                        Back to Project
                    </button>

                </div>


                <!-- Summary -->

                <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            Total Tasks
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight">
                            <?= $totalTasks ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            Pending
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-amber-600 dark:text-amber-400">
                            <?= $pendingTasks ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            In Progress
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-blue-600 dark:text-blue-400">
                            <?= $inProgressTasks ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                            Completed
                        </p>

                        <p class="mt-2 text-3xl font-semibold tracking-tight text-emerald-600 dark:text-emerald-400">
                            <?= $completedTasks ?>
                        </p>

                    </div>

                </div>


                <!-- Tasks -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            Tasks
                        </h3>

                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Tasks assigned to you in this project.
                        </p>

                    </div>


                    <?php if (empty($tasks)): ?>

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
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 5a3 3 0 016 0v1H9V5z"
                                    />

                                </svg>

                            </div>

                            <h3
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No tasks found
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                You don't have any tasks assigned in this project.
                            </p>

                        </div>


                    <?php else: ?>


                        <div class="divide-y divide-slate-200 dark:divide-slate-800">

                            <?php foreach ($tasks as $task): ?>

                                <div class="px-5 py-5">

                                    <div
                                        class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between"
                                    >

                                        <div class="min-w-0">

                                            <div class="flex flex-wrap items-center gap-2">

                                                <h4
                                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                                >
                                                    <?= htmlspecialchars($task['title']) ?>
                                                </h4>


                                                <span
                                                    class="rounded-full px-2 py-1 text-xs font-medium <?= taskStatusBadge($task['status']) ?>"
                                                >
                                                    <?= htmlspecialchars(
                                                        ucwords(
                                                            str_replace(
                                                                '_',
                                                                ' ',
                                                                $task['status']
                                                            )
                                                        )
                                                    ) ?>
                                                </span>


                                                <span
                                                    class="rounded-full px-2 py-1 text-xs font-medium <?= taskPriorityBadge($task['priority']) ?>"
                                                >
                                                    <?= htmlspecialchars(
                                                        ucfirst($task['priority'])
                                                    ) ?>
                                                </span>

                                            </div>


                                            <?php if (!empty($task['description'])): ?>

                                                <p
                                                    class="mt-2 line-clamp-2 text-sm text-slate-500 dark:text-slate-400"
                                                >
                                                    <?= htmlspecialchars($task['description']) ?>
                                                </p>

                                            <?php endif; ?>


                                            <div
                                                class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500 dark:text-slate-400"
                                            >

                                                <?php if (!empty($task['start_date'])): ?>

                                                    <span>
                                                        Start:
                                                        <?= date(
                                                            'd M Y',
                                                            strtotime($task['start_date'])
                                                        ) ?>
                                                    </span>

                                                <?php endif; ?>


                                                <?php if (!empty($task['due_date'])): ?>

                                                    <span>
                                                        Due:
                                                        <?= date(
                                                            'd M Y',
                                                            strtotime($task['due_date'])
                                                        ) ?>
                                                    </span>

                                                <?php endif; ?>

                                            </div>

                                        </div>


                                        <div class="shrink-0">

                                            <a
                                                href="view_task.php?id=<?= (int) $task['id'] ?>"
                                                class="btn btn-primary"
                                            >
                                                View Task
                                            </a>

                                        </div>

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