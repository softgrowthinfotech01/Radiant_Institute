<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Filters
// --------------------------------------------------

$project_id = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;

$status = $_GET['status'] ?? '';

$priority = $_GET['priority'] ?? '';


// --------------------------------------------------
// Get Projects For Filter
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        id,
        name
    FROM projects
    ORDER BY name ASC
");

$stmt->execute();

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Tasks
// --------------------------------------------------

$sql = "
    SELECT
        t.*,

        p.name AS project_name,

        u.name AS created_by_name

    FROM tasks t

    LEFT JOIN projects p
        ON p.id = t.project_id

    LEFT JOIN users u
        ON u.id = t.created_by

    WHERE 1 = 1
";


$params = [];


// --------------------------------------------------
// Project Filter
// --------------------------------------------------

if ($project_id > 0) {

    $sql .= "
        AND t.project_id = :project_id
    ";

    $params[':project_id'] = $project_id;

}


// --------------------------------------------------
// Status Filter
// --------------------------------------------------

if (
    in_array(
        $status,
        [
            'pending',
            'in_progress',
            'completed',
            'on_hold',
            'cancelled'
        ]
    )
) {

    $sql .= "
        AND t.status = :status
    ";

    $params[':status'] = $status;

}


// --------------------------------------------------
// Priority Filter
// --------------------------------------------------

if (
    in_array(
        $priority,
        [
            'low',
            'medium',
            'high',
            'urgent'
        ]
    )
) {

    $sql .= "
        AND t.priority = :priority
    ";

    $params[':priority'] = $priority;

}


$sql .= "
    ORDER BY t.id DESC
";


$stmt = $conn->prepare($sql);

$stmt->execute($params);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Assignees For All Tasks
// --------------------------------------------------

$taskAssignees = [];


if (count($tasks) > 0) {

    $taskIds = array_column($tasks, 'id');

    $placeholders = [];

    $assigneeParams = [];

    foreach ($taskIds as $index => $taskId) {

        $placeholder = ':task_' . $index;

        $placeholders[] = $placeholder;

        $assigneeParams[$placeholder] = $taskId;

    }


    $assigneeSql = "
        SELECT
            ta.task_id,
            u.name
        FROM task_assignees ta

        INNER JOIN users u
            ON u.id = ta.user_id

        WHERE ta.task_id IN (
            " . implode(',', $placeholders) . "
        )

        ORDER BY u.name ASC
    ";


    $stmt = $conn->prepare($assigneeSql);

    $stmt->execute($assigneeParams);

    $assignees = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($assignees as $assignee) {

        $taskAssignees[$assignee['task_id']][] =
            $assignee['name'];

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

    <title>Tasks — Admin</title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    />


    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    />


    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />


    <link
        rel="stylesheet"
        href="../../dist/css/output.css"
    />

</head>


<body
    class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
>


    <div
        id="mobile-backdrop"
        class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"
    ></div>


    <div class="flex min-h-full">


        <?php
        include('sidebar.php');
        ?>


        <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


            <?php
            include('header.php');
            ?>


            <main class="flex-1 overflow-auto p-4 lg:p-8">


                <div class="container">


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
                                class="text-display-sm text-slate-900 dark:text-white"
                            >
                                Tasks
                            </h2>


                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                Manage tasks across all projects.
                            </p>

                        </div>


                        <div class="flex gap-2">


                            <button
                                type="button"
                                onclick="window.location.href='create_task.php'"
                                class="btn btn-primary"
                            >
                                + Create Task
                            </button>


                        </div>

                    </div>


                    <!-- Filters -->

                    <div
                        class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <div
                            class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <h3 class="text-base font-semibold">
                                Filter Tasks
                            </h3>

                        </div>


                        <form
                            method="GET"
                            action=""
                            class="grid gap-4 p-5 md:grid-cols-2 lg:grid-cols-4"
                        >


                            <!-- Project -->

                            <div>

                                <label
                                    for="project_id"
                                    class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    Project
                                </label>


                                <select
                                    id="project_id"
                                    name="project_id"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                >

                                    <option value="">
                                        All Projects
                                    </option>


                                    <?php foreach ($projects as $project): ?>

                                        <option
                                            value="<?= $project['id'] ?>"
                                            <?= $project_id == $project['id'] ? 'selected' : '' ?>
                                        >

                                            <?= htmlspecialchars($project['name']) ?>

                                        </option>

                                    <?php endforeach; ?>


                                </select>

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
                                    id="status"
                                    name="status"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                >

                                    <option value="">
                                        All Statuses
                                    </option>


                                    <option
                                        value="pending"
                                        <?= $status === 'pending' ? 'selected' : '' ?>
                                    >
                                        Pending
                                    </option>


                                    <option
                                        value="in_progress"
                                        <?= $status === 'in_progress' ? 'selected' : '' ?>
                                    >
                                        In Progress
                                    </option>


                                    <option
                                        value="completed"
                                        <?= $status === 'completed' ? 'selected' : '' ?>
                                    >
                                        Completed
                                    </option>


                                    <option
                                        value="on_hold"
                                        <?= $status === 'on_hold' ? 'selected' : '' ?>
                                    >
                                        On Hold
                                    </option>


                                    <option
                                        value="cancelled"
                                        <?= $status === 'cancelled' ? 'selected' : '' ?>
                                    >
                                        Cancelled
                                    </option>

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
                                    id="priority"
                                    name="priority"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                >

                                    <option value="">
                                        All Priorities
                                    </option>


                                    <option
                                        value="low"
                                        <?= $priority === 'low' ? 'selected' : '' ?>
                                    >
                                        Low
                                    </option>


                                    <option
                                        value="medium"
                                        <?= $priority === 'medium' ? 'selected' : '' ?>
                                    >
                                        Medium
                                    </option>


                                    <option
                                        value="high"
                                        <?= $priority === 'high' ? 'selected' : '' ?>
                                    >
                                        High
                                    </option>


                                    <option
                                        value="urgent"
                                        <?= $priority === 'urgent' ? 'selected' : '' ?>
                                    >
                                        Urgent
                                    </option>

                                </select>

                            </div>


                            <!-- Buttons -->

                            <div class="flex items-end gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Apply Filters
                                </button>


                                <button
                                    type="button"
                                    onclick="window.location.href='list_tasks.php'"
                                    class="btn btn-secondary"
                                >
                                    Reset
                                </button>

                            </div>


                        </form>

                    </div>


                    <!-- Task Table -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >


                        <!-- Table Header -->

                        <div
                            class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <div>

                                <h3 class="text-base font-semibold">
                                    All Tasks
                                </h3>


                                <p
                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                >

                                    <?= count($tasks) ?>

                                    task(s) found

                                </p>

                            </div>

                        </div>


                        <!-- Table -->

                        <div class="overflow-x-auto">


                            <table
                                class="w-full min-w-[1200px] text-left text-sm"
                            >


                                <thead
                                    class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400"
                                >

                                    <tr>

                                        <th class="px-5 py-3 font-semibold">
                                            #
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Task
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Project
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Priority
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Status
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Assignees
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Due Date
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Created By
                                        </th>

                                        <th class="px-5 py-3 font-semibold text-right">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody
                                    class="divide-y divide-slate-100 dark:divide-slate-800"
                                >


                                    <?php if (count($tasks) > 0): ?>


                                        <?php foreach ($tasks as $index => $task): ?>


                                            <tr
                                                class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50"
                                            >


                                                <!-- Number -->

                                                <td class="px-5 py-4">

                                                    <span class="font-medium">
                                                        <?= $index + 1 ?>
                                                    </span>

                                                </td>


                                                <!-- Task -->

                                                <td class="px-5 py-4">

                                                    <div>

                                                        <p
                                                            class="font-semibold text-slate-900 dark:text-white"
                                                        >

                                                            <?= htmlspecialchars($task['title']) ?>

                                                        </p>


                                                        <?php if (!empty($task['description'])): ?>

                                                            <p
                                                                class="mt-1 max-w-xs truncate text-xs text-slate-500 dark:text-slate-400"
                                                            >

                                                                <?= htmlspecialchars($task['description']) ?>

                                                            </p>

                                                        <?php endif; ?>

                                                    </div>

                                                </td>


                                                <!-- Project -->

                                                <td class="px-5 py-4">

                                                    <a
                                                        href="view_project.php?id=<?= $task['project_id'] ?>"
                                                        class="font-medium text-brand-600 hover:underline dark:text-brand-400"
                                                    >

                                                        <?= htmlspecialchars($task['project_name']) ?>

                                                    </a>

                                                </td>


                                                <!-- Priority -->

                                                <td class="px-5 py-4">


                                                    <?php if ($task['priority'] === 'urgent'): ?>

                                                        <span
                                                            class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-950/50 dark:text-red-400"
                                                        >
                                                            Urgent
                                                        </span>


                                                    <?php elseif ($task['priority'] === 'high'): ?>

                                                        <span
                                                            class="rounded-full bg-orange-50 px-2.5 py-0.5 text-xs font-medium text-orange-700 dark:bg-orange-950/50 dark:text-orange-400"
                                                        >
                                                            High
                                                        </span>


                                                    <?php elseif ($task['priority'] === 'medium'): ?>

                                                        <span
                                                            class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/50 dark:text-amber-400"
                                                        >
                                                            Medium
                                                        </span>


                                                    <?php else: ?>

                                                        <span
                                                            class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                                                        >
                                                            Low
                                                        </span>

                                                    <?php endif; ?>


                                                </td>


                                                <!-- Status -->

                                                <td class="px-5 py-4">


                                                    <?php if ($task['status'] === 'pending'): ?>

                                                        <span
                                                            class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                                                        >
                                                            Pending
                                                        </span>


                                                    <?php elseif ($task['status'] === 'in_progress'): ?>

                                                        <span
                                                            class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-950/50 dark:text-blue-400"
                                                        >
                                                            In Progress
                                                        </span>


                                                    <?php elseif ($task['status'] === 'completed'): ?>

                                                        <span
                                                            class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400"
                                                        >
                                                            Completed
                                                        </span>


                                                    <?php elseif ($task['status'] === 'on_hold'): ?>

                                                        <span
                                                            class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/50 dark:text-amber-400"
                                                        >
                                                            On Hold
                                                        </span>


                                                    <?php elseif ($task['status'] === 'cancelled'): ?>

                                                        <span
                                                            class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-950/50 dark:text-red-400"
                                                        >
                                                            Cancelled
                                                        </span>

                                                    <?php endif; ?>


                                                </td>


                                                <!-- Assignees -->

                                                <td class="px-5 py-4">

                                                    <?php if (!empty($taskAssignees[$task['id']])): ?>


                                                        <div class="flex max-w-xs flex-wrap gap-1">


                                                            <?php foreach ($taskAssignees[$task['id']] as $assignee): ?>

                                                                <span
                                                                    class="rounded-full bg-brand-50 px-2 py-1 text-xs font-medium text-brand-700 dark:bg-brand-950/50 dark:text-brand-300"
                                                                >

                                                                    <?= htmlspecialchars($assignee) ?>

                                                                </span>

                                                            <?php endforeach; ?>


                                                        </div>


                                                    <?php else: ?>

                                                        <span
                                                            class="text-xs text-slate-400"
                                                        >
                                                            Unassigned
                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- Due Date -->

                                                <td class="px-5 py-4">

                                                    <?php if (!empty($task['due_date'])): ?>

                                                        <?php

                                                        $dueTimestamp =
                                                            strtotime($task['due_date']);

                                                        $isOverdue =
                                                            $task['status'] !== 'completed'
                                                            &&
                                                            $dueTimestamp < strtotime(date('Y-m-d'));

                                                        ?>


                                                        <span
                                                            class="<?= $isOverdue
                                                                ? 'font-medium text-red-600 dark:text-red-400'
                                                                : 'text-slate-600 dark:text-slate-400'
                                                            ?>"
                                                        >

                                                            <?= date(
                                                                'd M Y',
                                                                $dueTimestamp
                                                            ) ?>

                                                        </span>


                                                    <?php else: ?>

                                                        <span
                                                            class="text-slate-400"
                                                        >
                                                            -
                                                        </span>

                                                    <?php endif; ?>

                                                </td>


                                                <!-- Created By -->

                                                <td class="px-5 py-4">

                                                    <span
                                                        class="text-slate-600 dark:text-slate-400"
                                                    >

                                                        <?= htmlspecialchars(
                                                            $task['created_by_name'] ?? 'Unknown'
                                                        ) ?>

                                                    </span>

                                                </td>


                                                <!-- Actions -->

                                                <td class="px-5 py-4">

                                                    <div
                                                        class="flex justify-end gap-2"
                                                    >


                                                        <button
                                                            type="button"
                                                            onclick="window.location.href='view_task.php?id=<?= $task['id'] ?>'"
                                                            class="btn btn-secondary"
                                                        >
                                                            View
                                                        </button>


                                                        <button
                                                            type="button"
                                                            onclick="window.location.href='edit_task.php?id=<?= $task['id'] ?>'"
                                                            class="btn btn-secondary"
                                                        >
                                                            Edit
                                                        </button>


                                                        <button
                                                            type="button"
                                                            onclick="deleteTask(<?= $task['id'] ?>)"
                                                            class="btn btn-secondary"
                                                        >
                                                            Delete
                                                        </button>


                                                    </div>

                                                </td>


                                            </tr>


                                        <?php endforeach; ?>


                                    <?php else: ?>


                                        <tr>

                                            <td
                                                colspan="9"
                                                class="px-5 py-12 text-center"
                                            >

                                                <p
                                                    class="text-sm font-medium"
                                                >
                                                    No tasks found
                                                </p>


                                                <p
                                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                                >
                                                    Create a task to get started.
                                                </p>


                                            </td>

                                        </tr>


                                    <?php endif; ?>


                                </tbody>

                            </table>

                        </div>


                    </div>


                </div>


            </main>


            <?php
            include('footer.php');
            ?>


        </div>

    </div>


    <script src="../dist/js/app.js"></script>


    <script>

        function deleteTask(id) {

            if (
                confirm(
                    'Are you sure you want to delete this task?'
                )
            ) {

                window.location.href =
                    'delete_task.php?id=' + id;

            }

        }

    </script>


</body>

</html>