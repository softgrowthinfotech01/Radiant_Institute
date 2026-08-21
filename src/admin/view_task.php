<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Task ID
// --------------------------------------------------

$task_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($task_id <= 0) {
    header('Location: list_tasks.php');
    exit;
}


// --------------------------------------------------
// Get Task
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        t.*,
        p.name AS project_name,
        u.name AS creator_name
    FROM tasks t
    LEFT JOIN projects p
        ON p.id = t.project_id
    LEFT JOIN users u
        ON u.id = t.created_by
    WHERE t.id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $task_id
]);

$task = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$task) {
    header('Location: list_tasks.php');
    exit;
}



// --------------------------------------------------
// Get Task Comments
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        tc.id,
        tc.user_id,
        tc.comment,
        tc.created_at,
        tc.updated_at,
        u.name AS user_name
    FROM task_comments tc
    LEFT JOIN users u
        ON u.id = tc.user_id
    WHERE tc.task_id = :task_id
    ORDER BY tc.created_at DESC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);



// --------------------------------------------------
// Get Assignees
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        u.id,
        u.name
    FROM task_assignees ta
    INNER JOIN users u
        ON u.id = ta.user_id
    WHERE ta.task_id = :task_id
    ORDER BY u.name ASC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$assignees = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Subtasks
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        ts.*,
        u.name AS creator_name
    FROM task_subtasks ts
    LEFT JOIN users u
        ON u.id = ts.created_by
    WHERE ts.task_id = :task_id
    ORDER BY ts.id DESC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$subtasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Comments
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        tc.*,
        u.name AS user_name
    FROM task_comments tc
    LEFT JOIN users u
        ON u.id = tc.user_id
    WHERE tc.task_id = :task_id
    ORDER BY tc.created_at DESC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Activities
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        ta.*,
        u.name AS user_name
    FROM task_activities ta
    LEFT JOIN users u
        ON u.id = ta.user_id
    WHERE ta.task_id = :task_id
    ORDER BY ta.created_at DESC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Documents
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        td.id,
        td.title,
        td.description,
        td.uploaded_by,
        td.created_at,
        u.name AS uploaded_by_name
    FROM task_documents td
    LEFT JOIN users u
        ON u.id = td.uploaded_by
    WHERE td.task_id = :task_id
    ORDER BY td.created_at DESC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Subtask Statistics
// --------------------------------------------------

$total_subtasks = count($subtasks);

$completed_subtasks = 0;

foreach ($subtasks as $subtask) {

    if ($subtask['status'] === 'completed') {
        $completed_subtasks++;
    }
}

$subtask_percentage = $total_subtasks > 0
    ? round(
        ($completed_subtasks / $total_subtasks) * 100
    )
    : 0;


// --------------------------------------------------
// Status Labels
// --------------------------------------------------

$status_labels = [
    'pending' => 'Pending',
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    'on_hold' => 'On Hold',
    'cancelled' => 'Cancelled'
];


$priority_labels = [
    'low' => 'Low',
    'medium' => 'Medium',
    'high' => 'High',
    'urgent' => 'Urgent'
];


// --------------------------------------------------
// Status Classes
// --------------------------------------------------

$status_classes = [
    'pending' =>
    'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400',

    'in_progress' =>
    'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',

    'completed' =>
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400',

    'on_hold' =>
    'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300',

    'cancelled' =>
    'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400'
];


$priority_classes = [
    'low' =>
    'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',

    'medium' =>
    'bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400',

    'high' =>
    'bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400',

    'urgent' =>
    'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400'
];


// --------------------------------------------------
// Format Dates
// --------------------------------------------------

function formatDate($date)
{
    if (!$date) {
        return '—';
    }

    return date('d M Y', strtotime($date));
}


function formatDateTime($date)
{
    if (!$date) {
        return '—';
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
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($task['title']) ?> — Task
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../../dist/css/output.css">

</head>


<body
    class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">


    <div
        id="mobile-backdrop"
        class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>


    <div class="flex min-h-full">


        <?php include('sidebar.php'); ?>


        <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


            <?php include('header.php'); ?>


            <main class="flex-1 overflow-auto p-4 lg:p-8">


                <div class="container">


                    <!-- ==================================================
                     HEADER
                =================================================== -->

                    <div
                        class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                        <div>

                            <p
                                class="text-sm font-medium text-brand-600 dark:text-brand-400">
                                Task Details
                            </p>


                            <h2
                                class="mt-1 text-display-sm text-slate-900 dark:text-white">
                                <?= htmlspecialchars($task['title']) ?>
                            </h2>


                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Project:
                                <?= htmlspecialchars($task['project_name'] ?? '—') ?>
                            </p>

                        </div>


                        <div class="flex flex-wrap gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='list_tasks.php'"
                                class="btn btn-secondary">
                                Back
                            </button>

<a
    href="list_task_activities.php?task_id=<?= $task_id ?>"
    class="btn btn-secondary"
>
    Activity History
</a>

                            <button
                                type="button"
                                onclick="window.location.href='edit_task.php?id=<?= $task_id ?>'"
                                class="btn btn-primary">
                                Edit Task
                            </button>

                        </div>

                    </div>


                    <!-- ==================================================
                     MAIN GRID
                =================================================== -->

                    <div
                        class="grid gap-6 xl:grid-cols-3">


                        <!-- ==================================================
                         LEFT / MAIN CONTENT
                    =================================================== -->

                        <div
                            class="space-y-6 xl:col-span-2">


                            <!-- Task Description -->

                            <div
                                class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div
                                    class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                                    <h3 class="font-semibold">
                                        Description
                                    </h3>

                                </div>


                                <div class="p-5">

                                    <?php if (!empty($task['description'])): ?>

                                        <div
                                            class="whitespace-pre-wrap text-sm leading-7 text-slate-600 dark:text-slate-300">
                                            <?= htmlspecialchars($task['description']) ?>
                                        </div>

                                    <?php else: ?>

                                        <p
                                            class="text-sm text-slate-400">
                                            No description added.
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- ==================================================
                             SUBTASKS
                        =================================================== -->

                            <div
                                class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div
                                    class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                                    <div>

                                        <h3 class="font-semibold">
                                            Subtasks
                                        </h3>

                                        <p
                                            class="mt-1 text-xs text-slate-500">
                                            <?= $completed_subtasks ?>
                                            of
                                            <?= $total_subtasks ?>
                                            completed
                                        </p>

                                    </div>


                                    <button
                                        type="button"
                                        onclick="window.location.href='add_subtask.php?task_id=<?= $task_id ?>'"
                                        class="btn btn-primary">
                                        Add Subtask
                                    </button>

                                </div>


                                <?php if ($total_subtasks > 0): ?>

                                    <div class="p-5">

                                        <div
                                            class="mb-5 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">

                                            <div
                                                class="h-full rounded-full bg-brand-600"
                                                style="width: <?= $subtask_percentage ?>%;"></div>

                                        </div>


                                        <div class="space-y-3">

                                            <?php foreach ($subtasks as $subtask): ?>

                                                <div
                                                    class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800">

                                                    <div
                                                        class="flex h-5 w-5 items-center justify-center rounded border <?= $subtask['status'] === 'completed'
                                                                                                                            ? 'border-emerald-500 bg-emerald-500 text-white'
                                                                                                                            : 'border-slate-300 dark:border-slate-600'
                                                                                                                        ?>">

                                                        <?php if ($subtask['status'] === 'completed'): ?>

                                                            ✓

                                                        <?php endif; ?>

                                                    </div>


                                                    <div class="min-w-0 flex-1">

                                                        <p
                                                            class="<?= $subtask['status'] === 'completed'
                                                                        ? 'text-slate-400 line-through'
                                                                        : 'text-slate-800 dark:text-slate-200'
                                                                    ?> text-sm font-medium">
                                                            <?= htmlspecialchars($subtask['title']) ?>
                                                        </p>

                                                    </div>


                                                    <div class="flex items-center gap-2">

                                                        <span
                                                            class="text-xs text-slate-400">
                                                            <?= htmlspecialchars(
                                                                ucfirst($subtask['status'])
                                                            ) ?>
                                                        </span>

                                                        <a
                                                            href="edit_subtask.php?id=<?= (int) $subtask['id'] ?>"
                                                            class="rounded-lg px-2 py-1 text-xs font-medium text-brand-600 hover:bg-brand-50 dark:text-brand-400 dark:hover:bg-brand-950/30">
                                                            Edit
                                                        </a>

                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    </div>

                                <?php else: ?>

                                    <div class="p-8 text-center">

                                        <p
                                            class="text-sm text-slate-400">
                                            No subtasks added yet.
                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>


                           <!-- ==================================================
     COMMENTS
================================================== -->

<div
    class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
>

    <!-- Header -->

    <div
        class="flex items-center justify-between border-b border-slate-200 px-6 py-5 dark:border-slate-800"
    >

        <div>

            <h3
                class="text-base font-semibold text-slate-900 dark:text-white"
            >
                Comments
            </h3>

            <p
                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
            >
                Discussion and updates related to this task.
            </p>

        </div>


        <a
            href="add_comment.php?task_id=<?= $task_id ?>"
            class="btn btn-primary"
        >
            Add Comment
        </a>

    </div>


    <!-- Comments -->

    <div class="p-6">

        <?php if (empty($comments)): ?>

            <div
                class="rounded-xl border border-dashed border-slate-300 px-6 py-10 text-center dark:border-slate-700"
            >

                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m2 4H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v8a4 4 0 01-4 4z"
                        />

                    </svg>

                </div>


                <p
                    class="mt-4 text-sm font-medium text-slate-700 dark:text-slate-300"
                >
                    No comments yet
                </p>


                <p
                    class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                >
                    Add the first comment to this task.
                </p>


                <a
                    href="add_comment.php?task_id=<?= $task_id ?>"
                    class="mt-4 inline-flex text-sm font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400"
                >
                    Add a comment
                </a>

            </div>

        <?php else: ?>


            <div class="space-y-5">

                <?php foreach ($comments as $comment): ?>

                    <div
                        class="rounded-xl border border-slate-200 p-5 dark:border-slate-800"
                    >

                        <!-- Comment Header -->

                        <div
                            class="flex items-start justify-between gap-4"
                        >

                            <div class="flex min-w-0 items-center gap-3">

                                <div
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-950/50 dark:text-brand-400"
                                >

                                    <?= htmlspecialchars(
                                        strtoupper(
                                            mb_substr(
                                                $comment['user_name'] ?? 'U',
                                                0,
                                                1
                                            )
                                        )
                                    ) ?>

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                                    >

                                        <?= htmlspecialchars(
                                            $comment['user_name'] ?? 'Unknown User'
                                        ) ?>

                                    </p>


                                    <p
                                        class="text-xs text-slate-500 dark:text-slate-400"
                                    >

                                        <?= htmlspecialchars(
                                            date(
                                                'd M Y, h:i A',
                                                strtotime(
                                                    $comment['created_at']
                                                )
                                            )
                                        ) ?>


                                        <?php if (
                                            !empty($comment['updated_at']) &&
                                            $comment['updated_at'] !==
                                            $comment['created_at']
                                        ): ?>

                                            <span>
                                                · edited
                                            </span>

                                        <?php endif; ?>

                                    </p>

                                </div>

                            </div>


                            <!-- Actions -->

                            <div class="flex shrink-0 items-center gap-1">

                                <a
                                    href="edit_comment.php?id=<?= (int) $comment['id'] ?>"
                                    class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-brand-600 transition hover:bg-brand-50 dark:text-brand-400 dark:hover:bg-brand-950/30"
                                >
                                    Edit
                                </a>


                                <a
                                    href="delete_comment.php?id=<?= (int) $comment['id'] ?>"
                                    class="rounded-lg px-2.5 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
                                >
                                    Delete
                                </a>

                            </div>

                        </div>


                        <!-- Comment Body -->

                        <div
                            class="mt-4 whitespace-pre-wrap break-words text-sm leading-6 text-slate-700 dark:text-slate-300"
                        >

                            <?= htmlspecialchars(
                                $comment['comment']
                            ) ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>


        <?php endif; ?>

    </div>

</div>
                            <!-- ==================================================
                             ACTIVITY
                        =================================================== -->

                            <div
                                class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div
                                    class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                                    <h3 class="font-semibold">
                                        Activity History
                                    </h3>

                                </div>


                                <?php if (count($activities) > 0): ?>

                                    <div class="p-5">

                                        <div class="space-y-5">

                                            <?php foreach ($activities as $activity): ?>

                                                <div
                                                    class="relative pl-7">

                                                    <div
                                                        class="absolute left-0 top-1 h-3 w-3 rounded-full bg-brand-500"></div>


                                                    <div>

                                                        <p
                                                            class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                                            <?= htmlspecialchars(
                                                                $activity['description']
                                                                    ?? $activity['activity_type']
                                                            ) ?>
                                                        </p>


                                                        <p
                                                            class="mt-1 text-xs text-slate-400">
                                                            <?= htmlspecialchars(
                                                                $activity['user_name']
                                                                    ?? 'Unknown User'
                                                            ) ?>

                                                            ·

                                                            <?= formatDateTime(
                                                                $activity['created_at']
                                                            ) ?>
                                                        </p>

                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    </div>

                                <?php else: ?>

                                    <div class="p-8 text-center">

                                        <p
                                            class="text-sm text-slate-400">
                                            No activity recorded.
                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>


                        </div>


                        <!-- ==================================================
                         RIGHT SIDEBAR
                    =================================================== -->

                        <div class="space-y-6">


                            <!-- Task Information -->

                            <div
                                class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div
                                    class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                                    <h3 class="font-semibold">
                                        Task Information
                                    </h3>

                                </div>


                                <div class="divide-y divide-slate-200 dark:divide-slate-800">


                                    <!-- Status -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Status
                                        </p>


                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $status_classes[$task['status']] ?? '' ?>">
                                            <?= htmlspecialchars(
                                                $status_labels[$task['status']]
                                                    ?? ucfirst($task['status'])
                                            ) ?>
                                        </span>

                                    </div>


                                    <!-- Priority -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-2 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Priority
                                        </p>


                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $priority_classes[$task['priority']] ?? '' ?>">
                                            <?= htmlspecialchars(
                                                $priority_labels[$task['priority']]
                                                    ?? ucfirst($task['priority'])
                                            ) ?>
                                        </span>

                                    </div>


                                    <!-- Project -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Project
                                        </p>


                                        <p
                                            class="text-sm font-medium">
                                            <?= htmlspecialchars(
                                                $task['project_name'] ?? '—'
                                            ) ?>
                                        </p>

                                    </div>


                                    <!-- Start Date -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Start Date
                                        </p>


                                        <p class="text-sm">

                                            <?= formatDate(
                                                $task['start_date']
                                            ) ?>

                                        </p>

                                    </div>


                                    <!-- Due Date -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Due Date
                                        </p>


                                        <p class="text-sm">

                                            <?= formatDate(
                                                $task['due_date']
                                            ) ?>

                                        </p>

                                    </div>


                                    <!-- Created By -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Created By
                                        </p>


                                        <p class="text-sm">

                                            <?= htmlspecialchars(
                                                $task['creator_name'] ?? 'Unknown'
                                            ) ?>

                                        </p>

                                    </div>


                                    <!-- Created At -->

                                    <div class="px-5 py-4">

                                        <p
                                            class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-400">
                                            Created At
                                        </p>


                                        <p class="text-sm">

                                            <?= formatDateTime(
                                                $task['created_at']
                                            ) ?>

                                        </p>

                                    </div>


                                </div>

                            </div>


                            <!-- ==================================================
                             ASSIGNEES
                        =================================================== -->

                            <div
                                class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div
                                    class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                                    <h3 class="font-semibold">
                                        Assigned Users
                                    </h3>

                                </div>


                                <div class="p-5">

                                    <?php if (count($assignees) > 0): ?>

                                        <div class="space-y-3">

                                            <?php foreach ($assignees as $assignee): ?>

                                                <div
                                                    class="flex items-center gap-3">

                                                    <div
                                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-950/50 dark:text-brand-400">

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


                                                    <div>

                                                        <p
                                                            class="text-sm font-medium">
                                                            <?= htmlspecialchars(
                                                                $assignee['name']
                                                            ) ?>
                                                        </p>

                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php else: ?>

                                        <p
                                            class="text-sm text-slate-400">
                                            No users assigned.
                                        </p>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- ==================================================
                             DOCUMENTS
                        =================================================== -->

                            <div
                                class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div
                                    class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                                    <div>

                                        <h3 class="font-semibold">
                                            Documents
                                        </h3>

                                        <p
                                            class="mt-1 text-xs text-slate-500">
                                            <?= count($documents) ?> document(s)
                                        </p>

                                    </div>


                                    <button
                                        type="button"
                                        onclick="window.location.href='list_task_documents.php?task_id=<?= $task_id ?>'"
                                        class="btn btn-secondary">
                                        View
                                    </button>

                                </div>


                                <div class="p-5">

                                    <?php if (count($documents) > 0): ?>

                                        <div class="space-y-3">

                                            <?php foreach (array_slice($documents, 0, 3) as $document): ?>

                                                <div
                                                    class="rounded-xl border border-slate-200 p-3 dark:border-slate-800">

                                                    <p
                                                        class="text-sm font-medium">
                                                        <?= htmlspecialchars(
                                                            $document['title']
                                                        ) ?>
                                                    </p>


                                                    <p
                                                        class="mt-1 text-xs text-slate-400">
                                                        <?= formatDate(
                                                            $document['created_at']
                                                        ) ?>
                                                    </p>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php else: ?>

                                        <p
                                            class="text-sm text-slate-400">
                                            No task documents uploaded.
                                        </p>

                                    <?php endif; ?>


                                    <button
                                        type="button"
                                        onclick="window.location.href='add_task_document.php?task_id=<?= $task_id ?>'"
                                        class="mt-4 w-full rounded-xl border border-dashed border-slate-300 px-4 py-3 text-sm font-medium text-slate-600 transition hover:border-brand-500 hover:text-brand-600 dark:border-slate-700 dark:text-slate-400">
                                        + Add Document
                                    </button>

                                </div>

                            </div>


                        </div>


                    </div>


                </div>


            </main>


            <?php include('footer.php'); ?>


        </div>

    </div>


    <script src="../dist/js/app.js"></script>

</body>

</html>