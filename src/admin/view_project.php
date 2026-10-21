<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Project ID
// --------------------------------------------------

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: list_projects.php');
    exit;
}


// --------------------------------------------------
// Get Project
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        p.*,
        u.name AS created_by_name
    FROM projects p
    LEFT JOIN users u ON p.created_by = u.id
    WHERE p.id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $id
]);

$project = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$project) {
    header('Location: list_projects.php');
    exit;
}


// --------------------------------------------------
// Get Task Statistics
// --------------------------------------------------
// Tasks will be created later.
// These queries are safe because the tasks table
// has already been created.

$totalTasks = 0;
$pendingTasks = 0;
$inProgressTasks = 0;
$completedTasks = 0;
$onHoldTasks = 0;


$stmt = $conn->prepare("
    SELECT
        COUNT(*) AS total_tasks,
        SUM(status = 'pending') AS pending_tasks,
        SUM(status = 'in_progress') AS in_progress_tasks,
        SUM(status = 'completed') AS completed_tasks,
        SUM(status = 'on_hold') AS on_hold_tasks
    FROM tasks
    WHERE project_id = :project_id
");

$stmt->execute([
    ':project_id' => $id
]);

$taskStats = $stmt->fetch(PDO::FETCH_ASSOC);


if ($taskStats) {

    $totalTasks = (int) ($taskStats['total_tasks'] ?? 0);

    $pendingTasks = (int) ($taskStats['pending_tasks'] ?? 0);

    $inProgressTasks = (int) ($taskStats['in_progress_tasks'] ?? 0);

    $completedTasks = (int) ($taskStats['completed_tasks'] ?? 0);

    $onHoldTasks = (int) ($taskStats['on_hold_tasks'] ?? 0);
}


// --------------------------------------------------
// Calculate Progress
// --------------------------------------------------

$progress = 0;

if ($totalTasks > 0) {

    $progress = round(
        ($completedTasks / $totalTasks) * 100
    );

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
        <?= htmlspecialchars($project['name']) ?> — Admin
    </title>

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
                                <?= htmlspecialchars($project['name']) ?>
                            </h2>


                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                Project overview and task progress.
                            </p>

                        </div>


                        <div class="flex flex-wrap gap-2">


                            <button
                                type="button"
                                onclick="window.location.href='list_projects.php'"
                                class="btn btn-secondary"
                            >
                                Back to Projects
                            </button>


                            <button
                                type="button"
                                onclick="window.location.href='edit_project.php?id=<?= $project['id'] ?>'"
                                class="btn btn-primary"
                            >
                                Edit Project
                            </button>


                        </div>

                    </div>


                    <!-- Project Information -->

                    <div
                        class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <div
                            class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <h3 class="text-base font-semibold">
                                Project Information
                            </h3>

                        </div>


                        <div class="p-5">


                            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">


                                <!-- Status -->

                                <div>

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Status
                                    </p>


                                    <div class="mt-2">


                                        <?php if ($project['status'] === 'active'): ?>

                                            <span
                                                class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400"
                                            >
                                                Active
                                            </span>


                                        <?php elseif ($project['status'] === 'completed'): ?>

                                            <span
                                                class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950/50 dark:text-brand-300"
                                            >
                                                Completed
                                            </span>


                                        <?php elseif ($project['status'] === 'on_hold'): ?>

                                            <span
                                                class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/50 dark:text-amber-400"
                                            >
                                                On Hold
                                            </span>


                                        <?php elseif ($project['status'] === 'cancelled'): ?>

                                            <span
                                                class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-950/50 dark:text-red-400"
                                            >
                                                Cancelled
                                            </span>

                                        <?php endif; ?>


                                    </div>

                                </div>


                                <!-- Created By -->

                                <div>

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Created By
                                    </p>


                                    <p
                                        class="mt-2 text-sm font-medium"
                                    >
                                        <?= htmlspecialchars($project['created_by_name'] ?? 'Unknown') ?>
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
                                        class="mt-2 text-sm font-medium"
                                    >

                                        <?php

                                        if (!empty($project['start_date'])) {

                                            echo date(
                                                'd M Y',
                                                strtotime($project['start_date'])
                                            );

                                        } else {

                                            echo '-';

                                        }

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
                                        class="mt-2 text-sm font-medium"
                                    >

                                        <?php

                                        if (!empty($project['due_date'])) {

                                            echo date(
                                                'd M Y',
                                                strtotime($project['due_date'])
                                            );

                                        } else {

                                            echo '-';

                                        }

                                        ?>

                                    </p>

                                </div>


                            </div>


                            <?php if (!empty($project['description'])): ?>

                                <div
                                    class="mt-6 border-t border-slate-200 pt-5 dark:border-slate-800"
                                >

                                    <p
                                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                                    >
                                        Description
                                    </p>


                                    <p
                                        class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600 dark:text-slate-300"
                                    >
                                        <?= htmlspecialchars($project['description']) ?>
                                    </p>

                                </div>

                            <?php endif; ?>


                        </div>

                    </div>


                    <!-- Task Statistics -->

                    <div
                        class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-5"
                    >


                        <!-- Total -->

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
                                <?= $totalTasks ?>
                            </p>

                        </div>


                        <!-- Pending -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                Pending
                            </p>


                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                <?= $pendingTasks ?>
                            </p>

                        </div>


                        <!-- In Progress -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                In Progress
                            </p>


                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                <?= $inProgressTasks ?>
                            </p>

                        </div>


                        <!-- Completed -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                Completed
                            </p>


                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                <?= $completedTasks ?>
                            </p>

                        </div>


                        <!-- On Hold -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                On Hold
                            </p>


                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                <?= $onHoldTasks ?>
                            </p>

                        </div>


                    </div>


                    <!-- Progress -->

                    <div
                        class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >

                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <h3 class="text-base font-semibold">
                                    Project Progress
                                </h3>

                                <p
                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                >
                                    Based on completed tasks.
                                </p>

                            </div>


                            <span
                                class="text-lg font-semibold"
                            >
                                <?= $progress ?>%
                            </span>

                        </div>


                        <div
                            class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
                        >

                            <div
                                class="h-full rounded-full bg-brand-500 transition-all"
                                style="width: <?= $progress ?>%;"
                            ></div>

                        </div>

                    </div>


                    <!-- Tasks Section -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >


                        <div
                            class="flex flex-col gap-4 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800"
                        >

                            <div>

                                <h3 class="text-base font-semibold">
                                    Project Tasks
                                </h3>

                                <p
                                    class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                                >
                                    Tasks belonging to this project will appear here.
                                </p>

                            </div>


                            <button
                                type="button"
                                onclick="window.location.href='create_task.php?project_id=<?= $project['id'] ?>'"
                                class="btn btn-primary"
                            >
                                + Create Task
                            </button>

                        </div>


                        <div class="px-5 py-12 text-center">


                            <p
                                class="text-sm font-medium"
                            >
                                Task management is coming next
                            </p>


                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Once we create the task module, tasks for this project will be displayed here.
                            </p>


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


</body>

</html>