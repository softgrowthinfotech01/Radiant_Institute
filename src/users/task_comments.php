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
        t.description,
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
| Get Comments
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        tc.id,
        tc.task_id,
        tc.user_id,
        tc.comment,
        tc.created_at,
        tc.updated_at,

        u.name AS user_name

    FROM task_comments tc

    LEFT JOIN users u
        ON tc.user_id = u.id

    WHERE tc.task_id = ?

    ORDER BY tc.created_at DESC
");

$stmt->execute([
    $task_id
]);

$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Task Comments — User</title>

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
                            Task Comments
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


                    <div class="flex flex-wrap gap-2">

                        <button
                            type="button"
                            onclick="window.location.href='view_task.php?id=<?= (int) $task_id ?>'"
                            class="btn btn-secondary"
                        >
                            Back to Task
                        </button>

                        <a
                            href="add_comment.php?task_id=<?= (int) $task_id ?>"
                            class="btn btn-primary"
                        >
                            Add Comment
                        </a>

                    </div>

                </div>


                <!-- Comments -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            Comments
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            <?= count($comments) ?>
                            comment<?= count($comments) === 1 ? '' : 's' ?>
                        </p>

                    </div>


                    <?php if (empty($comments)): ?>

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
                                        d="M8 10h8M8 14h5"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 4H5a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2z"
                                    />

                                </svg>

                            </div>


                            <h3
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No comments yet
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                Start the discussion by adding a comment.
                            </p>


                            <div class="mt-5">

                                <a
                                    href="add_comment.php?task_id=<?= (int) $task_id ?>"
                                    class="btn btn-primary"
                                >
                                    Add Comment
                                </a>

                            </div>

                        </div>


                    <?php else: ?>


                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($comments as $comment): ?>

                                <div class="px-5 py-5">

                                    <div
                                        class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
                                    >

                                        <div class="flex min-w-0 gap-3">

                                            <!-- Avatar -->

                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-950/50 dark:text-brand-400"
                                            >

                                                <?= htmlspecialchars(
                                                    strtoupper(
                                                        substr(
                                                            $comment['user_name'] ?? 'U',
                                                            0,
                                                            1
                                                        )
                                                    )
                                                ) ?>

                                            </div>


                                            <!-- Comment -->

                                            <div class="min-w-0">

                                                <div
                                                    class="flex flex-wrap items-center gap-2"
                                                >

                                                    <p
                                                        class="text-sm font-semibold text-slate-900 dark:text-white"
                                                    >
                                                        <?= htmlspecialchars(
                                                            $comment['user_name'] ?? 'Unknown User'
                                                        ) ?>
                                                    </p>

                                                    <span
                                                        class="text-xs text-slate-400"
                                                    >
                                                        <?= date(
                                                            'd M Y, h:i A',
                                                            strtotime($comment['created_at'])
                                                        ) ?>
                                                    </span>

                                                </div>


                                                <p
                                                    class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300"
                                                >
                                                    <?= htmlspecialchars($comment['comment']) ?>
                                                </p>


                                                <?php
                                                $isOwner =
                                                    (int) $comment['user_id'] ===
                                                    (int) $user_id;
                                                ?>

                                                <?php if ($isOwner): ?>

                                                    <div class="mt-3 flex gap-2">

                                                        <a
                                                            href="edit_comment.php?id=<?= (int) $comment['id'] ?>"
                                                            class="text-xs font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400"
                                                        >
                                                            Edit
                                                        </a>

                                                        <a
                                                            href="delete_comment.php?id=<?= (int) $comment['id'] ?>"
                                                            onclick="return confirm('Are you sure you want to delete this comment?');"
                                                            class="text-xs font-medium text-red-600 hover:text-red-700 dark:text-red-400"
                                                        >
                                                            Delete
                                                        </a>

                                                    </div>

                                                <?php endif; ?>

                                            </div>

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