<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];


/*
|--------------------------------------------------------------------------
| Get Logged In User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$user_id]);

$ret = $stmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

$statusFilter = $_GET['status'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';
$search = trim($_GET['search'] ?? '');


$allowedStatuses = [
    'pending',
    'in_progress',
    'completed',
    'on_hold',
    'cancelled'
];

$allowedPriorities = [
    'low',
    'medium',
    'high',
    'urgent'
];


/*
|--------------------------------------------------------------------------
| Build Query
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        t.*,

        p.name AS project_name,

        creator.name AS created_by_name

    FROM tasks t

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    INNER JOIN projects p
        ON t.project_id = p.id

    LEFT JOIN users creator
        ON t.created_by = creator.id

    WHERE ta.user_id = ?
";

$params = [$user_id];


/*
|--------------------------------------------------------------------------
| Status Filter
|--------------------------------------------------------------------------
*/

if (
    !empty($statusFilter) &&
    in_array($statusFilter, $allowedStatuses, true)
) {

    $sql .= " AND t.status = ? ";

    $params[] = $statusFilter;
}


/*
|--------------------------------------------------------------------------
| Priority Filter
|--------------------------------------------------------------------------
*/

if (
    !empty($priorityFilter) &&
    in_array($priorityFilter, $allowedPriorities, true)
) {

    $sql .= " AND t.priority = ? ";

    $params[] = $priorityFilter;
}


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

if (!empty($search)) {

    $sql .= "
        AND (
            t.title LIKE ?
            OR t.description LIKE ?
            OR p.name LIKE ?
        )
    ";

    $searchValue = '%' . $search . '%';

    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;
}


/*
|--------------------------------------------------------------------------
| Order
|--------------------------------------------------------------------------
*/

$sql .= "
    ORDER BY
        CASE t.status
            WHEN 'in_progress' THEN 1
            WHEN 'pending' THEN 2
            WHEN 'on_hold' THEN 3
            WHEN 'completed' THEN 4
            WHEN 'cancelled' THEN 5
            ELSE 6
        END,

        CASE t.priority
            WHEN 'urgent' THEN 1
            WHEN 'high' THEN 2
            WHEN 'medium' THEN 3
            WHEN 'low' THEN 4
            ELSE 5
        END,

        t.due_date ASC,
        t.id DESC
";


$stmt = $conn->prepare($sql);

$stmt->execute($params);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        COUNT(*) AS total,

        SUM(
            CASE
                WHEN t.status = 'pending'
                THEN 1 ELSE 0
            END
        ) AS pending,

        SUM(
            CASE
                WHEN t.status = 'in_progress'
                THEN 1 ELSE 0
            END
        ) AS in_progress,

        SUM(
            CASE
                WHEN t.status = 'completed'
                THEN 1 ELSE 0
            END
        ) AS completed,

        SUM(
            CASE
                WHEN t.status = 'on_hold'
                THEN 1 ELSE 0
            END
        ) AS on_hold

    FROM tasks t

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE ta.user_id = ?
");

$stmt->execute([$user_id]);

$stats = $stmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function statusClass($status)
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


function statusLabel($status)
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


function priorityClass($priority)
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


function priorityLabel($priority)
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


function formatDate($date)
{
    if (empty($date)) {
        return '-';
    }

    return date('d M Y', strtotime($date));
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

    <title>My Tasks — User</title>

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

            <div class="container">


                <!-- =====================================================
                     HEADER
                ====================================================== -->

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
                            class="text-display-sm text-slate-900 dark:text-white"
                        >
                            My Tasks
                        </h2>

                        <p
                            class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                        >
                            View and manage tasks assigned to you.
                        </p>

                    </div>

                </div>


                <!-- =====================================================
                     STATISTICS
                ====================================================== -->

                <div
                    class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5"
                >


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Total Tasks
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight"
                        >
                            <?= (int) ($stats['total'] ?? 0) ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Pending
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-amber-600 dark:text-amber-400"
                        >
                            <?= (int) ($stats['pending'] ?? 0) ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            In Progress
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-brand-600 dark:text-brand-400"
                        >
                            <?= (int) ($stats['in_progress'] ?? 0) ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Completed
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-emerald-600 dark:text-emerald-400"
                        >
                            <?= (int) ($stats['completed'] ?? 0) ?>
                        </p>

                    </div>


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            On Hold
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-orange-600 dark:text-orange-400"
                        >
                            <?= (int) ($stats['on_hold'] ?? 0) ?>
                        </p>

                    </div>


                </div>


                <!-- =====================================================
                     FILTERS
                ====================================================== -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <form
                        method="GET"
                        class="grid gap-4 md:grid-cols-4"
                    >


                        <!-- Search -->

                        <div class="md:col-span-2">

                            <label
                                for="search"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="<?= htmlspecialchars($search) ?>"
                                placeholder="Search task or project..."
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >

                        </div>


                        <!-- Status -->

                        <div>

                            <label
                                for="status"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Status
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >

                                <option value="">
                                    All Statuses
                                </option>

                                <?php foreach ($allowedStatuses as $status): ?>

                                    <option
                                        value="<?= htmlspecialchars($status) ?>"
                                        <?= $statusFilter === $status ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            statusLabel($status)
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- Priority -->

                        <div>

                            <label
                                for="priority"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Priority
                            </label>

                            <select
                                name="priority"
                                id="priority"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >

                                <option value="">
                                    All Priorities
                                </option>

                                <?php foreach ($allowedPriorities as $priority): ?>

                                    <option
                                        value="<?= htmlspecialchars($priority) ?>"
                                        <?= $priorityFilter === $priority ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            priorityLabel($priority)
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="flex items-end gap-2 md:col-span-4">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Filter Tasks
                            </button>

                            <button
                                type="button"
                                onclick="window.location.href='tasks.php'"
                                class="btn btn-secondary"
                            >
                                Clear
                            </button>

                        </div>


                    </form>

                </div>


                <!-- =====================================================
                     TASK LIST
                ====================================================== -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="text-base font-semibold text-slate-900 dark:text-white"
                        >
                            Assigned Tasks
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            <?= count($tasks) ?>
                            task<?= count($tasks) === 1 ? '' : 's' ?>
                            found
                        </p>

                    </div>


                    <?php if (empty($tasks)): ?>

                        <div class="px-5 py-14 text-center">

                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
                            >

                                <svg
                                    class="h-6 w-6 text-slate-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />

                                </svg>

                            </div>


                            <h4
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No tasks found
                            </h4>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                There are no tasks matching your current filters.
                            </p>

                        </div>

                    <?php else: ?>


                        <!-- Desktop Table -->

                        <div class="hidden overflow-x-auto md:block">

                            <table class="w-full text-left">

                                <thead
                                    class="border-b border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/50"
                                >

                                    <tr>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Task
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Project
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Priority
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Status
                                        </th>

                                        <th
                                            class="px-5 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500"
                                        >
                                            Due Date
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

                                    <?php foreach ($tasks as $task): ?>

                                        <tr
                                            class="transition hover:bg-slate-50 dark:hover:bg-slate-800/50"
                                        >

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
                                                        class="mt-1 max-w-xs truncate text-xs text-slate-500 dark:text-slate-400"
                                                    >
                                                        <?= htmlspecialchars(
                                                            $task['description']
                                                        ) ?>
                                                    </p>

                                                <?php endif; ?>

                                            </td>


                                            <td class="px-5 py-4">

                                                <span
                                                    class="text-sm text-slate-600 dark:text-slate-300"
                                                >
                                                    <?= htmlspecialchars(
                                                        $task['project_name']
                                                    ) ?>
                                                </span>

                                            </td>


                                            <td class="px-5 py-4">

                                                <span
                                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium <?= priorityClass($task['priority']) ?>"
                                                >
                                                    <?= htmlspecialchars(
                                                        priorityLabel($task['priority'])
                                                    ) ?>
                                                </span>

                                            </td>


                                            <td class="px-5 py-4">

                                                <span
                                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium <?= statusClass($task['status']) ?>"
                                                >
                                                    <?= htmlspecialchars(
                                                        statusLabel($task['status'])
                                                    ) ?>
                                                </span>

                                            </td>


                                            <td class="px-5 py-4">

                                                <span
                                                    class="text-sm text-slate-600 dark:text-slate-300"
                                                >
                                                    <?= formatDate($task['due_date']) ?>
                                                </span>

                                            </td>


                                            <td class="px-5 py-4 text-right">

                                                <a
                                                    href="view_task.php?id=<?= (int) $task['id'] ?>"
                                                    class="btn btn-secondary"
                                                >
                                                    View
                                                </a>

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

                            <?php foreach ($tasks as $task): ?>

                                <div class="p-5">

                                    <div
                                        class="flex items-start justify-between gap-3"
                                    >

                                        <div class="min-w-0">

                                            <a
                                                href="view_task.php?id=<?= (int) $task['id'] ?>"
                                                class="font-medium text-slate-900 hover:text-brand-600 dark:text-white dark:hover:text-brand-400"
                                            >
                                                <?= htmlspecialchars(
                                                    $task['title']
                                                ) ?>
                                            </a>

                                            <p
                                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                <?= htmlspecialchars(
                                                    $task['project_name']
                                                ) ?>
                                            </p>

                                        </div>


                                        <span
                                            class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium <?= statusClass($task['status']) ?>"
                                        >
                                            <?= htmlspecialchars(
                                                statusLabel($task['status'])
                                            ) ?>
                                        </span>

                                    </div>


                                    <div
                                        class="mt-4 grid grid-cols-2 gap-3"
                                    >

                                        <div>

                                            <p
                                                class="text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                Priority
                                            </p>

                                            <span
                                                class="mt-1 inline-flex rounded-full px-2.5 py-1 text-xs font-medium <?= priorityClass($task['priority']) ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    priorityLabel($task['priority'])
                                                ) ?>
                                            </span>

                                        </div>


                                        <div>

                                            <p
                                                class="text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                Due Date
                                            </p>

                                            <p
                                                class="mt-1 text-sm font-medium text-slate-700 dark:text-slate-300"
                                            >
                                                <?= formatDate($task['due_date']) ?>
                                            </p>

                                        </div>

                                    </div>


                                    <div class="mt-4">

                                        <a
                                            href="view_task.php?id=<?= (int) $task['id'] ?>"
                                            class="btn btn-secondary w-full"
                                        >
                                            View Task
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