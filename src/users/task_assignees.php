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
| Get Task + Verify Current User Is Assigned
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

    INNER JOIN task_assignees current_ta
        ON t.id = current_ta.task_id

    WHERE t.id = ?
      AND current_ta.user_id = ?

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
| Get Assigned Users
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        ta.id,
        ta.user_id,
        ta.assigned_at,
        u.name,
        u.email

    FROM task_assignees ta

    INNER JOIN users u
        ON ta.user_id = u.id

    WHERE ta.task_id = ?

    ORDER BY u.name ASC
");

$stmt->execute([
    $task_id
]);

$assignees = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Task Assignees — User</title>

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
                            Task Team
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


                    <button
                        type="button"
                        onclick="window.location.href='view_task.php?id=<?= (int) $task_id ?>'"
                        class="btn btn-secondary"
                    >
                        Back to Task
                    </button>

                </div>


                <!-- Summary -->

                <div class="mb-6">

                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <p
                            class="text-sm font-medium text-slate-500 dark:text-slate-400"
                        >
                            Assigned Users
                        </p>

                        <p
                            class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-white"
                        >
                            <?= count($assignees) ?>
                        </p>

                    </div>

                </div>


                <!-- Team -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <div
                        class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                    >

                        <h3
                            class="font-semibold text-slate-900 dark:text-white"
                        >
                            Assigned Team Members
                        </h3>

                        <p
                            class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                        >
                            Users currently assigned to this task.
                        </p>

                    </div>


                    <?php if (empty($assignees)): ?>

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
                                        d="M17 20h5v-1a6 6 0 00-9-5.197"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 20H4v-1a6 6 0 019-5.197"
                                    />

                                    <circle
                                        cx="9"
                                        cy="7"
                                        r="4"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 11a4 4 0 100-8"
                                    />

                                </svg>

                            </div>


                            <h3
                                class="mt-4 text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                No team members found
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                No users are currently assigned to this task.
                            </p>

                        </div>


                    <?php else: ?>


                        <div
                            class="divide-y divide-slate-200 dark:divide-slate-800"
                        >

                            <?php foreach ($assignees as $assignee): ?>

                                <?php

                                $name = $assignee['name'] ?: 'Unknown User';

                                $initial = strtoupper(
                                    substr($name, 0, 1)
                                );

                                ?>

                                <div
                                    class="flex flex-col gap-4 px-5 py-5 sm:flex-row sm:items-center sm:justify-between"
                                >

                                    <div class="flex items-center gap-4">

                                        <!-- Avatar -->

                                        <div
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-950/50 dark:text-brand-400"
                                        >

                                            <?= htmlspecialchars($initial) ?>

                                        </div>


                                        <!-- User -->

                                        <div class="min-w-0">

                                            <h4
                                                class="truncate text-sm font-semibold text-slate-900 dark:text-white"
                                            >
                                                <?= htmlspecialchars($name) ?>
                                            </h4>

                                            <?php if (!empty($assignee['email'])): ?>

                                                <p
                                                    class="mt-1 truncate text-sm text-slate-500 dark:text-slate-400"
                                                >
                                                    <?= htmlspecialchars($assignee['email']) ?>
                                                </p>

                                            <?php endif; ?>

                                        </div>

                                    </div>


                                    <!-- Assigned Date -->

                                    <div
                                        class="text-xs text-slate-500 dark:text-slate-400"
                                    >

                                        Assigned on

                                        <?= date(
                                            'd M Y, h:i A',
                                            strtotime($assignee['assigned_at'])
                                        ) ?>

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