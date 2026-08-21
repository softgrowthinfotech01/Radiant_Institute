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
|
| User can only view a task assigned to them.
|
*/

$stmt = $conn->prepare("
    SELECT
        t.*,

        p.name AS project_name,

        creator.name AS created_by_name

    FROM tasks t

    INNER JOIN task_assignees my_assignment
        ON t.id = my_assignment.task_id
        AND my_assignment.user_id = ?

    INNER JOIN projects p
        ON t.project_id = p.id

    LEFT JOIN users creator
        ON t.created_by = creator.id

    WHERE t.id = ?

    LIMIT 1
");

$stmt->execute([
    $user_id,
    $task_id
]);

$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get All Assignees
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        u.id,
        u.name

    FROM task_assignees ta

    INNER JOIN users u
        ON ta.user_id = u.id

    WHERE ta.task_id = ?

    ORDER BY u.name ASC
");

$stmt->execute([$task_id]);

$assignees = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Get Subtasks
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        ts.*,
        u.name AS created_by_name

    FROM task_subtasks ts

    LEFT JOIN users u
        ON ts.created_by = u.id

    WHERE ts.task_id = ?

    ORDER BY ts.id DESC
");

$stmt->execute([$task_id]);

$subtasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Subtask Statistics
|--------------------------------------------------------------------------
*/

$totalSubtasks = count($subtasks);

$completedSubtasks = 0;

foreach ($subtasks as $subtask) {

    if ($subtask['status'] === 'completed') {
        $completedSubtasks++;
    }
}

$subtaskProgress = 0;

if ($totalSubtasks > 0) {

    $subtaskProgress = round(
        ($completedSubtasks / $totalSubtasks) * 100
    );
}


/*
|--------------------------------------------------------------------------
| Get Comments
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        tc.*,
        u.name AS user_name

    FROM task_comments tc

    INNER JOIN users u
        ON tc.user_id = u.id

    WHERE tc.task_id = ?

    ORDER BY tc.created_at DESC
");

$stmt->execute([$task_id]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Get Activities
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        ta.*,
        u.name AS user_name

    FROM task_activities ta

    INNER JOIN users u
        ON ta.user_id = u.id

    WHERE ta.task_id = ?

    ORDER BY ta.created_at DESC

    LIMIT 50
");

$stmt->execute([$task_id]);

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Get Task Documents
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        td.*,
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

    ORDER BY td.created_at DESC
");

$stmt->execute([$task_id]);

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Task Document Files
|--------------------------------------------------------------------------
*/

$documentFiles = [];

if (!empty($documents)) {

    $documentIds = array_column($documents, 'id');

    $placeholders = implode(
        ',',
        array_fill(0, count($documentIds), '?')
    );

    $stmt = $conn->prepare("
        SELECT
            tdf.*,
            td.title AS document_title

        FROM task_document_files tdf

        INNER JOIN task_documents td
            ON tdf.task_document_id = td.id

        WHERE tdf.task_document_id IN ($placeholders)

        ORDER BY tdf.created_at DESC
    ");

    $stmt->execute($documentIds);

    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($files as $file) {

        $documentFiles[$file['task_document_id']][] = $file;
    }
}


/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

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


function formatDate($date)
{
    if (empty($date)) {
        return '-';
    }

    return date('d M Y', strtotime($date));
}


function formatDateTime($date)
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

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <title>
        <?= htmlspecialchars($task['title']) ?> — Task
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
                     PAGE HEADER
                ====================================================== -->

                <div
                    class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <div
                            class="mb-2 flex flex-wrap items-center gap-2 text-sm"
                        >

                            <a
                                href="tasks.php"
                                class="text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400"
                            >
                                My Tasks
                            </a>

                            <span class="text-slate-400">
                                /
                            </span>

                            <a
                                href="view_project.php?id=<?= (int) $task['project_id'] ?>"
                                class="text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400"
                            >
                                <?= htmlspecialchars($task['project_name']) ?>
                            </a>

                            <span class="text-slate-400">
                                /
                            </span>

                            <span class="text-slate-600 dark:text-slate-300">
                                Task
                            </span>

                        </div>


                        <div
                            class="flex flex-wrap items-center gap-3"
                        >

                            <h2
                                class="text-display-sm text-slate-900 dark:text-white"
                            >
                                <?= htmlspecialchars($task['title']) ?>
                            </h2>


                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskStatusClass($task['status']) ?>"
                            >
                                <?= htmlspecialchars(
                                    taskStatusLabel($task['status'])
                                ) ?>
                            </span>


                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-medium <?= taskPriorityClass($task['priority']) ?>"
                            >
                                <?= htmlspecialchars(
                                    taskPriorityLabel($task['priority'])
                                ) ?>
                            </span>

                        </div>


                        <p
                            class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                        >
                            Task details, subtasks, comments and documents.
                        </p>

                    </div>


                    <div class="flex flex-wrap gap-2">

    <button
        type="button"
        onclick="window.location.href='update_task_status.php?id=<?= (int) $task_id ?>'"
        class="btn btn-primary"
    >
        Update Status
    </button>

    <button
        type="button"
        onclick="window.location.href='tasks.php'"
        class="btn btn-secondary"
    >
        Back to Tasks
    </button>
<a
    href="user_task_activities.php?task_id=<?= (int) $task_id ?>"
    class="btn btn-secondary"
>
    Activity
</a>

<a
    href="task_comments.php?id=<?= (int) $task['id'] ?>"
    class="btn btn-secondary"
>
    Comments
</a>


<a
    href="task_documents.php?id=<?= (int) $task['id'] ?>"
    class="btn btn-secondary"
>
    Documents
</a>

<a
    href="task_subtasks.php?id=<?= (int) $task['id'] ?>"
    class="btn btn-secondary"
>
    Subtasks
</a>

<a
    href="task_assignees.php?id=<?= (int) $task['id'] ?>"
    class="btn btn-secondary"
>
    Team
</a>

<a
    href="add_subtask.php?task_id=<?= (int) $task['id'] ?>"
    class="btn btn-primary"
>
    Add Subtask
</a>


</div>

                </div>


                <!-- =====================================================
                     TASK INFORMATION
                ====================================================== -->

                <div
                    class="mb-6 grid gap-6 lg:grid-cols-3"
                >


                    <!-- Task Details -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900 lg:col-span-2"
                    >

                        <div
                            class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <h3
                                class="text-base font-semibold text-slate-900 dark:text-white"
                            >
                                Task Information
                            </h3>

                        </div>


                        <div class="p-5">


                            <div
                                class="grid gap-5 sm:grid-cols-2"
                            >


                                <div>

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Project
                                    </p>

                                    <a
                                        href="view_project.php?id=<?= (int) $task['project_id'] ?>"
                                        class="mt-1 block text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400"
                                    >
                                        <?= htmlspecialchars(
                                            $task['project_name']
                                        ) ?>
                                    </a>

                                </div>


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
                                            $task['created_by_name']
                                            ?? 'Unknown'
                                        ) ?>
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Start Date
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                                    >
                                        <?= formatDate($task['start_date']) ?>
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Due Date
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-medium text-slate-900 dark:text-white"
                                    >
                                        <?= formatDate($task['due_date']) ?>
                                    </p>

                                </div>


                            </div>


                            <div
                                class="mt-6 border-t border-slate-100 pt-5 dark:border-slate-800"
                            >

                                <p
                                    class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                >
                                    Description
                                </p>


                                <?php if (!empty($task['description'])): ?>

                                    <p
                                        class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300"
                                    >
                                        <?= htmlspecialchars(
                                            $task['description']
                                        ) ?>
                                    </p>

                                <?php else: ?>

                                    <p
                                        class="mt-2 text-sm italic text-slate-400"
                                    >
                                        No description provided.
                                    </p>

                                <?php endif; ?>

                            </div>


                        </div>

                    </div>


                    <!-- Subtask Progress -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <div
                            class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <h3
                                class="text-base font-semibold text-slate-900 dark:text-white"
                            >
                                Subtask Progress
                            </h3>

                        </div>


                        <div class="p-5">


                            <div class="text-center">

                                <p
                                    class="text-4xl font-bold text-brand-600 dark:text-brand-400"
                                >
                                    <?= $subtaskProgress ?>%
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
                                    class="h-full rounded-full bg-brand-600"
                                    style="width: <?= $subtaskProgress ?>%"
                                ></div>

                            </div>


                            <div
                                class="mt-6 grid grid-cols-2 gap-3"
                            >

                                <div
                                    class="rounded-xl bg-slate-50 p-3 text-center dark:bg-slate-800"
                                >

                                    <p
                                        class="text-xl font-semibold text-slate-900 dark:text-white"
                                    >
                                        <?= $totalSubtasks ?>
                                    </p>

                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        Total
                                    </p>

                                </div>


                                <div
                                    class="rounded-xl bg-emerald-50 p-3 text-center dark:bg-emerald-950/30"
                                >

                                    <p
                                        class="text-xl font-semibold text-emerald-600 dark:text-emerald-400"
                                    >
                                        <?= $completedSubtasks ?>
                                    </p>

                                    <p
                                        class="text-xs text-emerald-600 dark:text-emerald-400"
                                    >
                                        Completed
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                </div>


                <!-- =====================================================
                     ASSIGNEES
                ====================================================== -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="text-base font-semibold text-slate-900 dark:text-white"
                        >
                            Assigned Users
                        </h3>

                    </div>


                    <div class="p-5">

                        <?php if (empty($assignees)): ?>

                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                No users assigned.
                            </p>

                        <?php else: ?>

                            <div
                                class="flex flex-wrap gap-3"
                            >

                                <?php foreach ($assignees as $assignee): ?>

                                    <div
                                        class="flex items-center gap-2 rounded-full bg-slate-100 py-2 pl-2 pr-4 dark:bg-slate-800"
                                    >

                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-xs font-semibold text-white"
                                        >

                                            <?= htmlspecialchars(
                                                strtoupper(
                                                    substr(
                                                        $assignee['name'],
                                                        0,
                                                        1
                                                    )
                                                )
                                            ) ?>

                                        </div>


                                        <span
                                            class="text-sm font-medium text-slate-700 dark:text-slate-300"
                                        >
                                            <?= htmlspecialchars(
                                                $assignee['name']
                                            ) ?>
                                        </span>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- =====================================================
                     SUBTASKS
                ====================================================== -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800"
                    >

                        <div>

                            <h3
                                class="text-base font-semibold text-slate-900 dark:text-white"
                            >
                                Subtasks
                            </h3>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                <?= $completedSubtasks ?>
                                of
                                <?= $totalSubtasks ?>
                                completed
                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="window.location.href='add_subtask.php?task_id=<?= (int) $task_id ?>'"
                            class="btn btn-primary"
                        >
                            Add Subtask
                        </button>

                    </div>


                    <?php if (empty($subtasks)): ?>

                        <div class="px-5 py-10 text-center">

                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                No subtasks added yet.
                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($subtasks as $subtask): ?>

                                <div
                                    class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
                                >

                                    <div class="flex items-start gap-3">

                                        <div
                                            class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded border <?= $subtask['status'] === 'completed'
                                                ? 'border-emerald-500 bg-emerald-500'
                                                : 'border-slate-300 dark:border-slate-600'
                                            ?>"
                                        >

                                            <?php if ($subtask['status'] === 'completed'): ?>

                                                <svg
                                                    class="h-3 w-3 text-white"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="3"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7"
                                                    />
                                                </svg>

                                            <?php endif; ?>

                                        </div>


                                        <div>

                                            <p
                                                class="<?= $subtask['status'] === 'completed'
                                                    ? 'text-slate-400 line-through'
                                                    : 'text-slate-900 dark:text-white'
                                                ?> text-sm font-medium"
                                            >
                                                <?= htmlspecialchars(
                                                    $subtask['title']
                                                ) ?>
                                            </p>

                                            <p
                                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                            >
                                                Created by
                                                <?= htmlspecialchars(
                                                    $subtask['created_by_name']
                                                    ?? 'Unknown'
                                                ) ?>
                                            </p>

                                        </div>

                                    </div>


                                    <div class="flex items-center gap-2">

                                        <span
                                            class="rounded-full px-2.5 py-1 text-xs font-medium <?= $subtask['status'] === 'completed'
                                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400'
                                                : 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400'
                                            ?>"
                                        >

                                            <?= $subtask['status'] === 'completed'
                                                ? 'Completed'
                                                : 'Pending'
                                            ?>

                                        </span>


                                        <button
                                            type="button"
                                            onclick="window.location.href='edit_subtask.php?id=<?= (int) $subtask['id'] ?>'"
                                            class="text-xs font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400"
                                        >
                                            Edit
                                        </button>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- =====================================================
                     COMMENTS
                ====================================================== -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800"
                    >

                        <div>

                            <h3
                                class="text-base font-semibold text-slate-900 dark:text-white"
                            >
                                Comments
                            </h3>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Discuss this task with the team.
                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="window.location.href='add_comment.php?task_id=<?= (int) $task_id ?>'"
                            class="btn btn-primary"
                        >
                            Add Comment
                        </button>

                    </div>


                    <?php if (empty($comments)): ?>

                        <div class="px-5 py-10 text-center">

                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                No comments yet.
                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($comments as $comment): ?>

                                <div class="p-5">

                                    <div
                                        class="flex items-start justify-between gap-4"
                                    >

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-600 text-xs font-semibold text-white"
                                            >

                                                <?= htmlspecialchars(
                                                    strtoupper(
                                                        substr(
                                                            $comment['user_name'],
                                                            0,
                                                            1
                                                        )
                                                    )
                                                ) ?>

                                            </div>


                                            <div>

                                                <p
                                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                                >
                                                    <?= htmlspecialchars(
                                                        $comment['user_name']
                                                    ) ?>
                                                </p>

                                                <p
                                                    class="text-xs text-slate-500 dark:text-slate-400"
                                                >
                                                    <?= formatDateTime(
                                                        $comment['created_at']
                                                    ) ?>
                                                </p>

                                            </div>

                                        </div>


                                        <?php if ((int) $comment['user_id'] === (int) $user_id): ?>

                                            <div class="flex gap-2">

                                                <a
                                                    href="edit_comment.php?id=<?= (int) $comment['id'] ?>"
                                                    class="text-xs font-medium text-brand-600 dark:text-brand-400"
                                                >
                                                    Edit
                                                </a>

                                                <a
                                                    href="delete_comment.php?id=<?= (int) $comment['id'] ?>"
                                                    onclick="return confirm('Are you sure you want to delete this comment?');"
                                                    class="text-xs font-medium text-red-600 dark:text-red-400"
                                                >
                                                    Delete
                                                </a>

                                            </div>

                                        <?php endif; ?>

                                    </div>


                                    <p
                                        class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300"
                                    >
                                        <?= htmlspecialchars(
                                            $comment['comment']
                                        ) ?>
                                    </p>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- =====================================================
                     TASK DOCUMENTS
                ====================================================== -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800"
                    >

                        <div>

                            <h3
                                class="text-base font-semibold text-slate-900 dark:text-white"
                            >
                                Task Documents
                            </h3>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Documents and files attached to this task.
                            </p>

                        </div>


                        <button
                            type="button"
                            onclick="window.location.href='add_task_document.php?task_id=<?= (int) $task_id ?>'"
                            class="btn btn-primary"
                        >
                            Add Document
                        </button>

                    </div>


                    <?php if (empty($documents)): ?>

                        <div class="px-5 py-10 text-center">

                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                No documents attached to this task.
                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($documents as $document): ?>

                                <div class="p-5">

                                    <div
                                        class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
                                    >

                                        <div>

                                            <a
                                                href="view_task_document.php?id=<?= (int) $document['id'] ?>"
                                                class="text-sm font-semibold text-slate-900 hover:text-brand-600 dark:text-white dark:hover:text-brand-400"
                                            >
                                                <?= htmlspecialchars(
                                                    $document['title']
                                                ) ?>
                                            </a>


                                            <?php if (!empty($document['description'])): ?>

                                                <p
                                                    class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                                                >
                                                    <?= htmlspecialchars(
                                                        $document['description']
                                                    ) ?>
                                                </p>

                                            <?php endif; ?>


                                            <div
                                                class="mt-2 flex flex-wrap gap-3 text-xs text-slate-500 dark:text-slate-400"
                                            >

                                                <span>
                                                    <?= (int) $document['file_count'] ?>
                                                    <?= (int) $document['file_count'] === 1
                                                        ? 'file'
                                                        : 'files'
                                                    ?>
                                                </span>

                                                <span>
                                                    Uploaded by
                                                    <?= htmlspecialchars(
                                                        $document['uploaded_by_name']
                                                        ?? 'Unknown'
                                                    ) ?>
                                                </span>

                                                <span>
                                                    <?= formatDateTime(
                                                        $document['created_at']
                                                    ) ?>
                                                </span>

                                            </div>

                                        </div>


                                        <button
                                            type="button"
                                            onclick="window.location.href='view_task_document.php?id=<?= (int) $document['id'] ?>'"
                                            class="btn btn-secondary"
                                        >
                                            View
                                        </button>

                                    </div>


                                    <?php if (!empty($documentFiles[$document['id']])): ?>

                                        <div
                                            class="mt-4 space-y-2"
                                        >

                                            <?php foreach ($documentFiles[$document['id']] as $file): ?>

                                                <div
                                                    class="flex flex-col gap-2 rounded-xl bg-slate-50 p-3 sm:flex-row sm:items-center sm:justify-between dark:bg-slate-800"
                                                >

                                                    <div class="min-w-0">

                                                        <p
                                                            class="truncate text-sm font-medium text-slate-700 dark:text-slate-300"
                                                        >
                                                            <?= htmlspecialchars(
                                                                $file['original_name']
                                                            ) ?>
                                                        </p>


                                                        <p
                                                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                                        >

                                                            <?php if (!empty($file['file_type'])): ?>

                                                                <?= htmlspecialchars(
                                                                    $file['file_type']
                                                                ) ?>

                                                            <?php endif; ?>


                                                            <?php if (!empty($file['file_size'])): ?>

                                                                ·

                                                                <?= number_format(
                                                                    ((int) $file['file_size']) / 1024,
                                                                    1
                                                                ) ?>
                                                                KB

                                                            <?php endif; ?>

                                                        </p>

                                                    </div>


                                                    <a
                                                        href="download_task_document_file.php?id=<?= (int) $file['id'] ?>"
                                                        class="btn btn-secondary shrink-0"
                                                    >
                                                        Download
                                                    </a>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endif; ?>


                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- =====================================================
                     ACTIVITY
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
                            Activity
                        </h3>

                    </div>


                    <?php if (empty($activities)): ?>

                        <div class="px-5 py-10 text-center">

                            <p
                                class="text-sm text-slate-500 dark:text-slate-400"
                            >
                                No activity recorded yet.
                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($activities as $activity): ?>

                                <div class="flex gap-4 p-5">

                                    <div
                                        class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
                                    >

                                        <svg
                                            class="h-4 w-4 text-slate-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="2"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />

                                        </svg>

                                    </div>


                                    <div class="min-w-0">

                                        <p
                                            class="text-sm text-slate-700 dark:text-slate-300"
                                        >

                                            <span class="font-semibold">
                                                <?= htmlspecialchars(
                                                    $activity['user_name']
                                                ) ?>
                                            </span>

                                            <?= htmlspecialchars(
                                                $activity['description']
                                                ?? $activity['activity_type']
                                            ) ?>

                                        </p>


                                        <p
                                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                        >
                                            <?= formatDateTime(
                                                $activity['created_at']
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