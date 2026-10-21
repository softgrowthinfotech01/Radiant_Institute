<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Task ID
// --------------------------------------------------

$task_id = isset($_GET['task_id'])
    ? (int) $_GET['task_id']
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
        id,
        title,
        project_id
    FROM tasks
    WHERE id = :id
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
// Get Activities
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        ta.id,
        ta.task_id,
        ta.user_id,
        ta.activity_type,
        ta.description,
        ta.created_at,
        u.name AS user_name
    FROM task_activities ta
    LEFT JOIN users u
        ON u.id = ta.user_id
    WHERE ta.task_id = :task_id
    ORDER BY ta.created_at DESC, ta.id DESC
");

$stmt->execute([
    ':task_id' => $task_id
]);

$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Activity Helpers
// --------------------------------------------------

function activityLabel($type)
{
    return match ($type) {

        'subtask_added'
            => 'Subtask Added',

        'subtask_updated'
            => 'Subtask Updated',

        'subtask_deleted'
            => 'Subtask Deleted',

        'comment_added'
            => 'Comment Added',

        'comment_updated'
            => 'Comment Updated',

        'comment_deleted'
            => 'Comment Deleted',

        'task_created'
            => 'Task Created',

        'task_updated'
            => 'Task Updated',

        'task_deleted'
            => 'Task Deleted',

        'document_added'
            => 'Document Added',

        'document_updated'
            => 'Document Updated',

        'document_deleted'
            => 'Document Deleted',

        default
            => ucwords(
                str_replace(
                    '_',
                    ' ',
                    $type
                )
            )
    };
}


function activityIcon($type)
{
    return match ($type) {

        'subtask_added',
        'subtask_updated',
        'subtask_deleted'
            => 'subtask',

        'comment_added',
        'comment_updated',
        'comment_deleted'
            => 'comment',

        'document_added',
        'document_updated',
        'document_deleted'
            => 'document',

        default
            => 'activity'
    };
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
        Task Activity — Admin
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
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


<div class="flex min-h-full">


    <?php include('sidebar.php'); ?>


    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


        <?php include('header.php'); ?>


        <main class="flex-1 p-4 lg:p-8">


            <div class="mx-auto max-w-5xl">


                <!-- ==================================================
                     PAGE HEADER
                =================================================== -->

                <div
                    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >

                    <div>

                        <p
                            class="text-sm font-medium text-brand-600 dark:text-brand-400"
                        >
                            Task Management
                        </p>


                        <h1
                            class="mt-1 text-display-sm text-slate-900 dark:text-white"
                        >
                            Activity History
                        </h1>


                        <p
                            class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                        >
                            Complete history of activity on this task.
                        </p>

                    </div>


                    <a
                        href="view_task.php?id=<?= $task_id ?>"
                        class="btn btn-secondary"
                    >
                        Back to Task
                    </a>

                </div>


                <!-- ==================================================
                     TASK INFO
                =================================================== -->

                <div
                    class="mb-6 rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <p
                        class="text-xs font-medium uppercase tracking-wide text-slate-400"
                    >
                        Task
                    </p>


                    <div
                        class="mt-1 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                    >

                        <h2
                            class="text-base font-semibold text-slate-900 dark:text-white"
                        >

                            <?= htmlspecialchars(
                                $task['title']
                            ) ?>

                        </h2>


                        <span
                            class="text-xs text-slate-500"
                        >

                            Task #<?= $task_id ?>

                        </span>

                    </div>

                </div>


                <!-- ==================================================
                     ACTIVITY CARD
                =================================================== -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <div
                        class="border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                    >

                        <div class="flex items-center justify-between">

                            <div>

                                <h3
                                    class="text-base font-semibold text-slate-900 dark:text-white"
                                >
                                    Activity
                                </h3>


                                <p
                                    class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                                >

                                    <?= count($activities) ?>
                                    activity record<?= count($activities) === 1 ? '' : 's' ?>

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="p-6">


                        <?php if (empty($activities)): ?>


                            <!-- Empty -->

                            <div
                                class="rounded-xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-slate-700"
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
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />

                                    </svg>

                                </div>


                                <p
                                    class="mt-4 text-sm font-medium text-slate-700 dark:text-slate-300"
                                >
                                    No activity yet
                                </p>


                                <p
                                    class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                                >
                                    Activity related to this task will appear
                                    here.
                                </p>

                            </div>


                        <?php else: ?>


                            <!-- Timeline -->

                            <div class="relative">


                                <!-- Vertical Line -->

                                <div
                                    class="absolute bottom-0 left-5 top-0 w-px bg-slate-200 dark:bg-slate-800"
                                ></div>


                                <div class="space-y-8">


                                    <?php foreach (
                                        $activities
                                        as $activity
                                    ): ?>


                                        <?php

                                        $type =
                                            $activity['activity_type'];

                                        $icon =
                                            activityIcon($type);

                                        ?>


                                        <div
                                            class="relative flex gap-4"
                                        >


                                            <!-- Icon -->

                                            <div
                                                class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-4 border-white bg-slate-100 text-slate-500 dark:border-slate-900 dark:bg-slate-800 dark:text-slate-300"
                                            >

                                                <?php if ($icon === 'comment'): ?>

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
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


                                                <?php elseif ($icon === 'subtask'): ?>

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 5h6M9 9h6M9 13h6M9 17h6M5 5h.01M5 9h.01M5 13h.01M5 17h.01"
                                                        />

                                                    </svg>


                                                <?php elseif ($icon === 'document'): ?>

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586A2 2 0 0114 3.586L19.414 9A2 2 0 0120 10.414V19a2 2 0 01-2 2z"
                                                        />

                                                    </svg>


                                                <?php else: ?>

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 01-18 0"
                                                        />

                                                    </svg>

                                                <?php endif; ?>

                                            </div>


                                            <!-- Content -->

                                            <div
                                                class="min-w-0 flex-1 pb-1"
                                            >


                                                <div
                                                    class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
                                                >

                                                    <div>

                                                        <span
                                                            class="text-sm font-semibold text-slate-900 dark:text-white"
                                                        >

                                                            <?= htmlspecialchars(
                                                                activityLabel(
                                                                    $type
                                                                )
                                                            ) ?>

                                                        </span>

                                                    </div>


                                                    <time
                                                        class="text-xs text-slate-500 dark:text-slate-400"
                                                    >

                                                        <?= htmlspecialchars(
                                                            date(
                                                                'd M Y, h:i A',
                                                                strtotime(
                                                                    $activity['created_at']
                                                                )
                                                            )
                                                        ) ?>

                                                    </time>

                                                </div>


                                                <p
                                                    class="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300"
                                                >

                                                    <?= htmlspecialchars(
                                                        $activity['description']
                                                        ?? ''
                                                    ) ?>

                                                </p>


                                                <p
                                                    class="mt-2 text-xs text-slate-500 dark:text-slate-400"
                                                >

                                                    By
                                                    <span
                                                        class="font-medium text-slate-600 dark:text-slate-300"
                                                    >

                                                        <?= htmlspecialchars(
                                                            $activity['user_name']
                                                            ?? 'Unknown User'
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


            </div>


        </main>


        <?php include('footer.php'); ?>


    </div>

</div>


<script src="../dist/js/app.js"></script>


</body>

</html>