<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];


/*
|--------------------------------------------------------------------------
| Get User Projects
|--------------------------------------------------------------------------
|
| Currently a project belongs to the user who created it.
|
*/

$stmt = $conn->prepare("
    SELECT
        p.*,

        (
            SELECT COUNT(*)
            FROM tasks t
            WHERE t.project_id = p.id
        ) AS total_tasks,

        (
            SELECT COUNT(*)
            FROM tasks t
            WHERE t.project_id = p.id
            AND t.status = 'completed'
        ) AS completed_tasks,

        (
            SELECT COUNT(*)
            FROM tasks t
            WHERE t.project_id = p.id
            AND t.status = 'in_progress'
        ) AS in_progress_tasks,

        (
            SELECT COUNT(*)
            FROM tasks t
            WHERE t.project_id = p.id
            AND t.status = 'pending'
        ) AS pending_tasks

    FROM projects p

    WHERE p.created_by = ?

    ORDER BY p.created_at DESC
");

$stmt->execute([$user_id]);

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Project Statistics
|--------------------------------------------------------------------------
*/

$totalProjects = count($projects);

$activeProjects = 0;
$completedProjects = 0;
$onHoldProjects = 0;

foreach ($projects as $project) {

    if ($project['status'] === 'active') {
        $activeProjects++;
    }

    if ($project['status'] === 'completed') {
        $completedProjects++;
    }

    if ($project['status'] === 'on_hold') {
        $onHoldProjects++;
    }
}


/*
|--------------------------------------------------------------------------
| Status Helper
|--------------------------------------------------------------------------
*/

function projectStatusClass($status)
{
    if ($status === 'active') {

        return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400';

    } elseif ($status === 'completed') {

        return 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400';

    } elseif ($status === 'on_hold') {

        return 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400';

    } elseif ($status === 'cancelled') {

        return 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400';

    }

    return 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400';
}


function projectStatusLabel($status)
{
    if ($status === 'active') {
        return 'Active';
    }

    if ($status === 'completed') {
        return 'Completed';
    }

    if ($status === 'on_hold') {
        return 'On Hold';
    }

    if ($status === 'cancelled') {
        return 'Cancelled';
    }

    return ucfirst($status);
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

    <title>Projects — User</title>

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
                        class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
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
                                My Projects
                            </h2>

                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                View your projects and track their progress.
                            </p>

                        </div>


                        <div class="flex gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='tasks.php'"
                                class="btn btn-secondary"
                            >
                                My Tasks
                            </button>

                        </div>

                    </div>


                    <!-- =================================================
                         STATISTICS
                    ================================================== -->

                    <div
                        class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4"
                    >


                        <!-- Total -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                Total Projects
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                <?= $totalProjects ?>
                            </p>

                        </div>


                        <!-- Active -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                Active
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight text-emerald-600 dark:text-emerald-400"
                            >
                                <?= $activeProjects ?>
                            </p>

                        </div>


                        <!-- Completed -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                Completed
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight text-blue-600 dark:text-blue-400"
                            >
                                <?= $completedProjects ?>
                            </p>

                        </div>


                        <!-- On Hold -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900"
                        >

                            <p
                                class="text-sm font-medium text-slate-500 dark:text-slate-400"
                            >
                                On Hold
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight text-amber-600 dark:text-amber-400"
                            >
                                <?= $onHoldProjects ?>
                            </p>

                        </div>


                    </div>


                    <!-- =================================================
                         PROJECTS
                    ================================================== -->

                    <?php if (empty($projects)): ?>


                        <!-- Empty State -->

                        <div
                            class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-card dark:border-slate-800 dark:bg-slate-900"
                        >

                            <div
                                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800"
                            >

                                <svg
                                    class="h-7 w-7 text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"
                                    />

                                </svg>

                            </div>


                            <h3
                                class="mt-4 text-base font-semibold text-slate-900 dark:text-white"
                            >
                                No projects found
                            </h3>


                            <p
                                class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                            >
                                You don't have any projects yet.
                            </p>

                        </div>


                    <?php else: ?>


                        <!-- =================================================
                             PROJECT GRID
                        ================================================== -->

                        <div
                            class="grid gap-5 md:grid-cols-2 xl:grid-cols-3"
                        >


                            <?php foreach ($projects as $project): ?>


                                <?php

                                $totalTasks = (int) $project['total_tasks'];

                                $completedTasks = (int) $project['completed_tasks'];

                                $progress = 0;

                                if ($totalTasks > 0) {

                                    $progress = round(
                                        ($completedTasks / $totalTasks) * 100
                                    );

                                }

                                ?>


                                <!-- Project Card -->

                                <div
                                    class="rounded-2xl border border-slate-200 bg-white shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900"
                                >


                                    <div class="p-5">


                                        <!-- Title + Status -->

                                        <div
                                            class="flex items-start justify-between gap-3"
                                        >

                                            <h3
                                                class="min-w-0 text-base font-semibold text-slate-900 dark:text-white"
                                            >

                                                <a
                                                    href="view_project.php?id=<?= (int) $project['id'] ?>"
                                                    class="hover:text-brand-600 dark:hover:text-brand-400"
                                                >

                                                    <?= htmlspecialchars(
                                                        $project['name']
                                                    ) ?>

                                                </a>

                                            </h3>


                                            <span
                                                class="shrink-0 rounded-full px-2.5 py-1 text-xs font-medium <?= projectStatusClass($project['status']) ?>"
                                            >

                                                <?= htmlspecialchars(
                                                    projectStatusLabel(
                                                        $project['status']
                                                    )
                                                ) ?>

                                            </span>

                                        </div>


                                        <!-- Description -->

                                        <div class="mt-3">

                                            <?php if (!empty($project['description'])): ?>

                                                <p
                                                    class="line-clamp-3 text-sm leading-6 text-slate-500 dark:text-slate-400"
                                                >

                                                    <?= htmlspecialchars(
                                                        $project['description']
                                                    ) ?>

                                                </p>

                                            <?php else: ?>

                                                <p
                                                    class="text-sm italic text-slate-400"
                                                >
                                                    No description provided.
                                                </p>

                                            <?php endif; ?>

                                        </div>


                                        <!-- Progress -->

                                        <div class="mt-5">

                                            <div
                                                class="mb-2 flex items-center justify-between"
                                            >

                                                <span
                                                    class="text-xs font-medium text-slate-500 dark:text-slate-400"
                                                >
                                                    Progress
                                                </span>

                                                <span
                                                    class="text-xs font-semibold text-slate-700 dark:text-slate-300"
                                                >
                                                    <?= $progress ?>%
                                                </span>

                                            </div>


                                            <div
                                                class="h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
                                            >

                                                <div
                                                    class="h-full rounded-full bg-brand-600"
                                                    style="width: <?= $progress ?>%"
                                                ></div>

                                            </div>

                                        </div>


                                        <!-- Task Stats -->

                                        <div
                                            class="mt-5 grid grid-cols-3 gap-2"
                                        >

                                            <div
                                                class="rounded-xl bg-slate-50 p-3 text-center dark:bg-slate-800"
                                            >

                                                <p
                                                    class="text-lg font-semibold text-slate-900 dark:text-white"
                                                >
                                                    <?= $totalTasks ?>
                                                </p>

                                                <p
                                                    class="text-xs text-slate-500 dark:text-slate-400"
                                                >
                                                    Tasks
                                                </p>

                                            </div>


                                            <div
                                                class="rounded-xl bg-brand-50 p-3 text-center dark:bg-brand-950/30"
                                            >

                                                <p
                                                    class="text-lg font-semibold text-brand-600 dark:text-brand-400"
                                                >
                                                    <?= (int) $project['in_progress_tasks'] ?>
                                                </p>

                                                <p
                                                    class="text-xs text-brand-600 dark:text-brand-400"
                                                >
                                                    Active
                                                </p>

                                            </div>


                                            <div
                                                class="rounded-xl bg-emerald-50 p-3 text-center dark:bg-emerald-950/30"
                                            >

                                                <p
                                                    class="text-lg font-semibold text-emerald-600 dark:text-emerald-400"
                                                >
                                                    <?= $completedTasks ?>
                                                </p>

                                                <p
                                                    class="text-xs text-emerald-600 dark:text-emerald-400"
                                                >
                                                    Done
                                                </p>

                                            </div>

                                        </div>


                                        <!-- Dates -->

                                        <div
                                            class="mt-5 space-y-2 border-t border-slate-100 pt-4 dark:border-slate-800"
                                        >

                                            <div
                                                class="flex items-center justify-between text-xs"
                                            >

                                                <span
                                                    class="text-slate-500 dark:text-slate-400"
                                                >
                                                    Start Date
                                                </span>

                                                <span
                                                    class="font-medium text-slate-700 dark:text-slate-300"
                                                >

                                                    <?php if (!empty($project['start_date'])): ?>

                                                        <?= date(
                                                            'd M Y',
                                                            strtotime(
                                                                $project['start_date']
                                                            )
                                                        ) ?>

                                                    <?php else: ?>

                                                        -

                                                    <?php endif; ?>

                                                </span>

                                            </div>


                                            <div
                                                class="flex items-center justify-between text-xs"
                                            >

                                                <span
                                                    class="text-slate-500 dark:text-slate-400"
                                                >
                                                    Due Date
                                                </span>

                                                <span
                                                    class="font-medium text-slate-700 dark:text-slate-300"
                                                >

                                                    <?php if (!empty($project['due_date'])): ?>

                                                        <?= date(
                                                            'd M Y',
                                                            strtotime(
                                                                $project['due_date']
                                                            )
                                                        ) ?>

                                                    <?php else: ?>

                                                        -

                                                    <?php endif; ?>

                                                </span>

                                            </div>

                                        </div>


                                    </div>


                                    <!-- Card Footer -->

                                    <div
                                        class="border-t border-slate-200 p-4 dark:border-slate-800"
                                    >

                                        <button
                                            type="button"
                                            onclick="window.location.href='view_project.php?id=<?= (int) $project['id'] ?>'"
                                            class="btn btn-primary w-full"
                                        >
                                            View Project
                                        </button>

                                    </div>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                </div>

            </main>

        </div>

    </div>


    <script src="../../dist/js/app.js"></script>

</body>

</html>