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
|--------------------------------------------------------------------------
|
| A user can only view projects created by that user.
|
*/

$stmt = $conn->prepare("
    SELECT
        p.*,
        u.name AS created_by_name

    FROM projects p

    LEFT JOIN users u
        ON p.created_by = u.id

    WHERE p.id = ?
    AND p.created_by = ?

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
| Task Statistics
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE project_id = ?
");

$stmt->execute([$project_id]);

$totalTasks = (int) $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE project_id = ?
    AND status = 'pending'
");

$stmt->execute([$project_id]);

$pendingTasks = (int) $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE project_id = ?
    AND status = 'in_progress'
");

$stmt->execute([$project_id]);

$inProgressTasks = (int) $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE project_id = ?
    AND status = 'completed'
");

$stmt->execute([$project_id]);

$completedTasks = (int) $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE project_id = ?
    AND status = 'on_hold'
");

$stmt->execute([$project_id]);

$onHoldTasks = (int) $stmt->fetchColumn();


$stmt = $conn->prepare("
    SELECT COUNT(*)
    FROM tasks
    WHERE project_id = ?
    AND status = 'cancelled'
");

$stmt->execute([$project_id]);

$cancelledTasks = (int) $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| Project Progress
|--------------------------------------------------------------------------
*/

$progress = 0;

if ($totalTasks > 0) {

    $progress = round(
        ($completedTasks / $totalTasks) * 100
    );
}


/*
|--------------------------------------------------------------------------
| Get Project Tasks
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

        (
            SELECT COUNT(*)
            FROM task_assignees ta
            WHERE ta.task_id = t.id
        ) AS assignee_count

    FROM tasks t

    WHERE t.project_id = ?

    ORDER BY
        CASE t.status
            WHEN 'in_progress' THEN 1
            WHEN 'pending' THEN 2
            WHEN 'on_hold' THEN 3
            WHEN 'completed' THEN 4
            WHEN 'cancelled' THEN 5
            ELSE 6
        END,

        CASE
            WHEN t.due_date IS NULL THEN 1
            ELSE 0
        END,

        t.due_date ASC,
        t.id DESC
");

$stmt->execute([$project_id]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function projectStatusClass($status)
{
    switch ($status) {

        case 'active':
            return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400';

        case 'completed':
            return 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400';

        case 'on_hold':
            return 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400';

        case 'cancelled':
            return 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
}


function projectStatusLabel($status)
{
    switch ($status) {

        case 'active':
            return 'Active';

        case 'completed':
            return 'Completed';

        case 'on_hold':
            return 'On Hold';

        case 'cancelled':
            return 'Cancelled';

        default:
            return ucfirst($status);
    }
}


function taskStatusClass($status)
{
    switch ($status) {

        case 'pending':
            return 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400';

        case 'in_progress':
            return 'bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-400';

        case 'completed':
            return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400';

        case 'on_hold':
            return 'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400';

        case 'cancelled':
            return 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
}


function taskStatusLabel($status)
{
    switch ($status) {

        case 'pending':
            return 'Pending';

        case 'in_progress':
            return 'In Progress';

        case 'completed':
            return 'Completed';

        case 'on_hold':
            return 'On Hold';

        case 'cancelled':
            return 'Cancelled';

        default:
            return ucfirst($status);
    }
}


function taskPriorityClass($priority)
{
    switch ($priority) {

        case 'low':
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';

        case 'medium':
            return 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400';

        case 'high':
            return 'bg-orange-50 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400';

        case 'urgent':
            return 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400';

        default:
            return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
    }
}


function taskPriorityLabel($priority)
{
    switch ($priority) {

        case 'low':
            return 'Low';

        case 'medium':
            return 'Medium';

        case 'high':
            return 'High';

        case 'urgent':
            return 'Urgent';

        default:
            return ucfirst($priority);
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <title>
        <?= htmlspecialchars($project['name']) ?> — Project
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
    />

</head>


<body
    class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
>


    <!-- Mobile Backdrop -->

    <div
        id="mobile-backdrop"
        class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"
    ></div>


    <div class="flex min-h-full">


        <!-- Sidebar -->

        <?php include('sidebar.php'); ?>


        <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


            <!-- Header -->

            <?php include('header.php'); ?>


            <!-- =====================================================
                 MAIN CONTENT
            ====================================================== -->

            <main class="flex-1 overflow-auto p-4 lg:p-8">

                <div class="container">


                    <!-- =================================================
                         PAGE HEADER
                    ================================================== -->

                    <div
                        class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                    >

                        <div>

                            <div
                                class="mb-2 flex items-center gap-2 text-sm"
                            >

                                <a
                                    href="projects.php"
                                    class="text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400"
                                >
                                    My Projects
                                </a>

                                <span class="text-slate-400">
                                    /
                                </span>

                                <span
                                    class="truncate text-slate-600 dark:text-slate-300"
                                >
                                    Project
                                </span>

                            </div>


                            <div
                                class="flex flex-wrap items-center gap-3"
                            >

                                <h2
                                    class="text-display-sm text-slate-900 dark:text-white"
                                >

                                    <?= htmlspecialchars(
                                        $project['name']
                                    ) ?>

                                </h2>


                                <span
                                    class="rounded-full px-2.5 py-1 text-xs font-medium <?= projectStatusClass($project['status']) ?>"
                                >

                                    <?= htmlspecialchars(
                                        projectStatusLabel(
                                            $project['status']
                                        )
                                    ) ?>

                                </span>

                            </div>


                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                Project details and task progress.
                            </p>

                        </div>


                        <div class="flex flex-wrap gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='projects.php'"
                                class="btn btn-secondary"
                            >
                                Back to Projects
                            </button>


                            <button
                                type="button"
                                onclick="window.location.href='tasks.php'"
                                class="btn btn-primary"
                            >
                                My Tasks
                            </button>
<a
    href="project_tasks.php?id=<?= (int) $project['id'] ?>"
    class="btn btn-primary"
>
    View Tasks
</a>
                        </div>

                    </div>


                    <!-- =================================================
                         PROJECT INFORMATION
                    ================================================== -->

                    <div
                        class="mb-6 grid gap-6 lg:grid-cols-3"
                    >


                        <!-- Project Details -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900 lg:col-span-2"
                        >

                            <div
                                class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                            >

                                <h3
                                    class="text-base font-semibold text-slate-900 dark:text-white"
                                >
                                    Project Information
                                </h3>

                            </div>


                            <div class="p-5">


                                <div
                                    class="grid gap-5 sm:grid-cols-2"
                                >


                                    <!-- Created By -->

                                    <div>

                                        <p
                                            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                        >
                                            Created By
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                                        >

                                            <?= htmlspecialchars(
                                                $project['created_by_name']
                                                ?? 'Unknown'
                                            ) ?>

                                        </p>

                                    </div>


                                    <!-- Created Date -->

                                    <div>

                                        <p
                                            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                        >
                                            Created Date
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                                        >

                                            <?= !empty($project['created_at'])
                                                ? date(
                                                    'd M Y',
                                                    strtotime(
                                                        $project['created_at']
                                                    )
                                                )
                                                : '-'
                                            ?>

                                        </p>

                                    </div>


                                    <!-- Start Date -->

                                    <div>

                                        <p
                                            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                        >
                                            Start Date
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                                        >

                                            <?= !empty($project['start_date'])
                                                ? date(
                                                    'd M Y',
                                                    strtotime(
                                                        $project['start_date']
                                                    )
                                                )
                                                : '-'
                                            ?>

                                        </p>

                                    </div>


                                    <!-- Due Date -->

                                    <div>

                                        <p
                                            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                        >
                                            Due Date
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                                        >

                                            <?= !empty($project['due_date'])
                                                ? date(
                                                    'd M Y',
                                                    strtotime(
                                                        $project['due_date']
                                                    )
                                                )
                                                : '-'
                                            ?>

                                        </p>

                                    </div>


                                </div>


                                <!-- Description -->

                                <div
                                    class="mt-6 border-t border-slate-100 pt-5 dark:border-slate-800"
                                >

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Description
                                    </p>


                                    <?php if (!empty($project['description'])): ?>

                                        <p
                                            class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300"
                                        >

                                            <?= htmlspecialchars(
                                                $project['description']
                                            ) ?>

                                        </p>

                                    <?php else: ?>

                                        <p
                                            class="mt-2 text-sm italic text-slate-400 dark:text-slate-500"
                                        >
                                            No description provided.
                                        </p>

                                    <?php endif; ?>

                                </div>


                            </div>

                        </div>


                        <!-- Progress -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                        >

                            <div
                                class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                            >

                                <h3
                                    class="text-base font-semibold text-slate-900 dark:text-white"
                                >
                                    Project Progress
                                </h3>

                            </div>


                            <div class="p-5">


                                <div class="text-center">

                                    <p
                                        class="text-4xl font-bold text-brand-600 dark:text-brand-400"
                                    >
                                        <?= $progress ?>%
                                    </p>

                                    <p
                                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                                    >
                                        Completed
                                    </p>

                                </div>


                                <div
                                    class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
                                >

                                    <div
                                        class="h-full rounded-full bg-brand-600 transition-all"
                                        style="width: <?= $progress ?>%"
                                    ></div>

                                </div>


                                <div
                                    class="mt-6 space-y-3"
                                >


                                    <div
                                        class="flex items-center justify-between text-sm"
                                    >

                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                        >
                                            Total Tasks
                                        </span>

                                        <span
                                            class="font-semibold text-slate-900 dark:text-white"
                                        >
                                            <?= $totalTasks ?>
                                        </span>

                                    </div>


                                    <div
                                        class="flex items-center justify-between text-sm"
                                    >

                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                        >
                                            Pending
                                        </span>

                                        <span
                                            class="font-semibold text-amber-600 dark:text-amber-400"
                                        >
                                            <?= $pendingTasks ?>
                                        </span>

                                    </div>


                                    <div
                                        class="flex items-center justify-between text-sm"
                                    >

                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                        >
                                            In Progress
                                        </span>

                                        <span
                                            class="font-semibold text-brand-600 dark:text-brand-400"
                                        >
                                            <?= $inProgressTasks ?>
                                        </span>

                                    </div>


                                    <div
                                        class="flex items-center justify-between text-sm"
                                    >

                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                        >
                                            Completed
                                        </span>

                                        <span
                                            class="font-semibold text-emerald-600 dark:text-emerald-400"
                                        >
                                            <?= $completedTasks ?>
                                        </span>

                                    </div>


                                    <div
                                        class="flex items-center justify-between text-sm"
                                    >

                                        <span
                                            class="text-slate-500 dark:text-slate-400"
                                        >
                                            On Hold
                                        </span>

                                        <span
                                            class="font-semibold text-orange-600 dark:text-orange-400"
                                        >
                                            <?= $onHoldTasks ?>
                                        </span>

                                    </div>


                                    <?php if ($cancelledTasks > 0): ?>

                                        <div
                                            class="flex items-center justify-between text-sm"
                                        >

                                            <span
                                                class="text-slate-500 dark:text-slate-400"
                                            >
                                                Cancelled
                                            </span>

                                            <span
                                                class="font-semibold text-red-600 dark:text-red-400"
                                            >
                                                <?= $cancelledTasks ?>
                                            </span>

                                        </div>

                                    <?php endif; ?>


                                </div>

                            </div>

                        </div>


                    </div>


                    <!-- =================================================
                         PROJECT TASKS
                    ================================================== -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >


                        <!-- Header -->

                        <div
                            class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800"
                        >

                            <div>

                                <h3
                                    class="text-base font-semibold text-slate-900 dark:text-white"
                                >
                                    Project Tasks
                                </h3>

                                <p
                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                >
                                    Tasks available in this project.
                                </p>

                            </div>


                            <button
                                type="button"
                                onclick="window.location.href='tasks.php?project_id=<?= (int) $project['id'] ?>'"
                                class="btn btn-secondary"
                            >
                                View All My Tasks
                            </button>

                        </div>


                        <?php if (empty($tasks)): ?>


                            <!-- Empty -->

                            <div
                                class="px-5 py-12 text-center"
                            >

                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
                                >

                                    <svg
                                        class="h-6 w-6 text-slate-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5h6M9 9h6M9 13h6M9 17h6"
                                        />

                                    </svg>

                                </div>


                                <p
                                    class="mt-4 text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    No tasks found
                                </p>


                                <p
                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                >
                                    There are currently no tasks in this project.
                                </p>

                            </div>


                        <?php else: ?>


                            <!-- =================================================
                                 DESKTOP TABLE
                            ================================================== -->

                            <div class="hidden overflow-x-auto md:block">

                                <table class="w-full text-left">

                                    <thead>

                                        <tr
                                            class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/40"
                                        >

                                            <th
                                                class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                            >
                                                Task
                                            </th>

                                            <th
                                                class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                            >
                                                Priority
                                            </th>

                                            <th
                                                class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                            >
                                                Status
                                            </th>

                                            <th
                                                class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                            >
                                                Due Date
                                            </th>

                                            <th
                                                class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                            >
                                                Assignees
                                            </th>

                                            <th
                                                class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                            >
                                                Action
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody
                                        class="divide-y divide-slate-200 dark:divide-slate-800"
                                    >


                                        <?php foreach ($tasks as $task): ?>


                                            <tr
                                                class="transition hover:bg-slate-50 dark:hover:bg-slate-800/40"
                                            >


                                                <!-- Task -->

                                                <td class="px-5 py-4">

                                                    <a
                                                        href="view_task.php?id=<?= (int) $task['id'] ?>"
                                                        class="font-medium text-slate-900 hover:text-brand-600 dark:text-white dark:hover:text-brand-400"
                                                    >

                                                        <?= htmlspecialchars(
                                                            $task['title']
                                                        ) ?>

                                                    </a>


                                                    <?php if (!empty($task['description'])): ?>

                                                        <p
                                                            class="mt-1 max-w-sm truncate text-xs text-slate-500 dark:text-slate-400"
                                                        >

                                                            <?= htmlspecialchars(
                                                                $task['description']
                                                            ) ?>

                                                        </p>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- Priority -->

                                                <td class="px-5 py-4">

                                                    <span
                                                        class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskPriorityClass($task['priority']) ?>"
                                                    >

                                                        <?= htmlspecialchars(
                                                            taskPriorityLabel(
                                                                $task['priority']
                                                            )
                                                        ) ?>

                                                    </span>

                                                </td>


                                                <!-- Status -->

                                                <td class="px-5 py-4">

                                                    <span
                                                        class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskStatusClass($task['status']) ?>"
                                                    >

                                                        <?= htmlspecialchars(
                                                            taskStatusLabel(
                                                                $task['status']
                                                            )
                                                        ) ?>

                                                    </span>

                                                </td>


                                                <!-- Due Date -->

                                                <td class="px-5 py-4">

                                                    <?php if (!empty($task['due_date'])): ?>

                                                        <span
                                                            class="text-sm text-slate-600 dark:text-slate-300"
                                                        >

                                                            <?= date(
                                                                'd M Y',
                                                                strtotime(
                                                                    $task['due_date']
                                                                )
                                                            ) ?>

                                                        </span>

                                                    <?php else: ?>

                                                        <span
                                                            class="text-sm text-slate-400"
                                                        >
                                                            -
                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- Assignees -->

                                                <td class="px-5 py-4">

                                                    <span
                                                        class="text-sm text-slate-600 dark:text-slate-300"
                                                    >

                                                        <?= (int) $task['assignee_count'] ?>

                                                        <?= (int) $task['assignee_count'] === 1
                                                            ? 'User'
                                                            : 'Users'
                                                        ?>

                                                    </span>

                                                </td>


                                                <!-- Action -->

                                                <td
                                                    class="px-5 py-4 text-right"
                                                >

                                                    <button
                                                        type="button"
                                                        onclick="window.location.href='view_task.php?id=<?= (int) $task['id'] ?>'"
                                                        class="btn btn-secondary"
                                                    >
                                                        View
                                                    </button>

                                                </td>


                                            </tr>


                                        <?php endforeach; ?>


                                    </tbody>

                                </table>

                            </div>


                            <!-- =================================================
                                 MOBILE CARDS
                            ================================================== -->

                            <div
                                class="divide-y divide-slate-200 md:hidden dark:divide-slate-800"
                            >


                                <?php foreach ($tasks as $task): ?>


                                    <div class="p-5">


                                        <div
                                            class="flex items-start justify-between gap-3"
                                        >

                                            <div class="min-w-0">

                                                <a
                                                    href="view_task.php?id=<?= (int) $task['id'] ?>"
                                                    class="font-medium text-slate-900 dark:text-white"
                                                >

                                                    <?= htmlspecialchars(
                                                        $task['title']
                                                    ) ?>

                                                </a>


                                                <?php if (!empty($task['description'])): ?>

                                                    <p
                                                        class="mt-1 line-clamp-2 text-xs leading-5 text-slate-500 dark:text-slate-400"
                                                    >

                                                        <?= htmlspecialchars(
                                                            $task['description']
                                                        ) ?>

                                                    </p>

                                                <?php endif; ?>

                                            </div>


                                            <button
                                                type="button"
                                                onclick="window.location.href='view_task.php?id=<?= (int) $task['id'] ?>'"
                                                class="shrink-0 text-xs font-medium text-brand-600 dark:text-brand-400"
                                            >
                                                View
                                            </button>

                                        </div>


                                        <div
                                            class="mt-4 flex flex-wrap gap-2"
                                        >

                                            <span
                                                class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskPriorityClass($task['priority']) ?>"
                                            >

                                                <?= htmlspecialchars(
                                                    taskPriorityLabel(
                                                        $task['priority']
                                                    )
                                                ) ?>

                                            </span>


                                            <span
                                                class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskStatusClass($task['status']) ?>"
                                            >

                                                <?= htmlspecialchars(
                                                    taskStatusLabel(
                                                        $task['status']
                                                    )
                                                ) ?>

                                            </span>

                                        </div>


                                        <div
                                            class="mt-4 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400"
                                        >

                                            <span>

                                                Due:

                                                <?php if (!empty($task['due_date'])): ?>

                                                    <?= date(
                                                        'd M Y',
                                                        strtotime(
                                                            $task['due_date']
                                                        )
                                                    ) ?>

                                                <?php else: ?>

                                                    -

                                                <?php endif; ?>

                                            </span>


                                            <span>

                                                <?= (int) $task['assignee_count'] ?>

                                                <?= (int) $task['assignee_count'] === 1
                                                    ? 'assignee'
                                                    : 'assignees'
                                                ?>

                                            </span>

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