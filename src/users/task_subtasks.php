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
| Get Subtasks
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        ts.id,
        ts.task_id,
        ts.title,
        ts.status,
        ts.created_by,
        ts.created_at,
        ts.updated_at,

        u.name AS created_by_name

    FROM task_subtasks ts

    LEFT JOIN users u
        ON ts.created_by = u.id

    WHERE ts.task_id = ?

    ORDER BY
        CASE
            WHEN ts.status = 'pending' THEN 0
            ELSE 1
        END,
        ts.id DESC
");

$stmt->execute([
    $task_id
]);

$subtasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Counts
|--------------------------------------------------------------------------
*/

$totalSubtasks = count($subtasks);

$completedSubtasks = 0;
$pendingSubtasks = 0;

foreach ($subtasks as $subtask) {

    if ($subtask['status'] === 'completed') {
        $completedSubtasks++;
    } else {
        $pendingSubtasks++;
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

    <title>Task Subtasks — User</title>

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
                            Task Subtasks
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
                            href="add_subtask.php?task_id=<?= (int) $task_id ?>"
                            class="btn btn-primary"
                        >
                            Add Subtask
                        </a>

                    </div>

                </div>


                <!-- Summary -->

                <div class="mb-6 grid gap-4 sm:grid-cols-3">


                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Total Subtasks
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white"
                        >
                            <?= $totalSubtasks ?>
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
                            <?= $pendingSubtasks ?>
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
                            <?= $completedSubtasks ?>
                        </p>

                    </div>


                </div>


                <!-- Subtasks -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            Subtasks
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            Manage the smaller tasks associated with this task.
                        </p>

                    </div>


                    <?php if (empty($subtasks)): ?>


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
                                No subtasks yet
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                Break this task into smaller pieces by adding a subtask.
                            </p>


                            <div class="mt-5">

                                <a
                                    href="add_subtask.php?task_id=<?= (int) $task_id ?>"
                                    class="btn btn-primary"
                                >
                                    Add Subtask
                                </a>

                            </div>

                        </div>


                    <?php else: ?>


                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($subtasks as $subtask): ?>

                                <?php
                                $isCompleted =
                                    $subtask['status'] === 'completed';
                                ?>


                                <div
                                    class="flex flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between"
                                >


                                    <div class="flex min-w-0 items-start gap-3">


                                        <!-- Status -->

                                        <div class="pt-0.5">

                                            <?php if ($isCompleted): ?>

                                                <div
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/50"
                                                >

                                                    <svg
                                                        class="h-4 w-4 text-emerald-600 dark:text-emerald-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="2"
                                                    >

                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M5 13l4 4L19 7"
                                                        />

                                                    </svg>

                                                </div>

                                            <?php else: ?>

                                                <div
                                                    class="h-6 w-6 rounded-full border-2 border-slate-300 dark:border-slate-600"
                                                ></div>

                                            <?php endif; ?>

                                        </div>


                                        <!-- Details -->

                                        <div class="min-w-0">

                                            <h4
                                                class="<?= $isCompleted
                                                    ? 'line-through text-slate-400 dark:text-slate-500'
                                                    : 'text-slate-900 dark:text-white'
                                                ?> text-sm font-semibold"
                                            >
                                                <?= htmlspecialchars(
                                                    $subtask['title']
                                                ) ?>
                                            </h4>


                                            <div
                                                class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400"
                                            >

                                                <span
                                                    class="rounded-full px-2 py-1 <?= $isCompleted
                                                        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400'
                                                        : 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400'
                                                    ?>"
                                                >
                                                    <?= $isCompleted
                                                        ? 'Completed'
                                                        : 'Pending'
                                                    ?>
                                                </span>


                                                <span>•</span>


                                                <span>
                                                    By
                                                    <?= htmlspecialchars(
                                                        $subtask['created_by_name'] ?? 'Unknown'
                                                    ) ?>
                                                </span>


                                                <span>•</span>


                                                <span>
                                                    <?= date(
                                                        'd M Y',
                                                        strtotime(
                                                            $subtask['created_at']
                                                        )
                                                    ) ?>
                                                </span>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Actions -->

                                    <div class="flex shrink-0 gap-2">

                                        <a
                                            href="edit_subtask.php?id=<?= (int) $subtask['id'] ?>"
                                            class="btn btn-secondary"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="delete_subtask.php?id=<?= (int) $subtask['id'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this subtask?');"
                                            class="btn btn-secondary text-red-600 hover:text-red-700 dark:text-red-400"
                                        >
                                            Delete
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