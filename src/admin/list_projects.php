<?php

include('../check.php');
include('../conn.php');

$stmt = $conn->prepare("
    SELECT 
        p.*,
        u.name AS created_by_name
    FROM projects p
    LEFT JOIN users u ON p.created_by = u.id
    ORDER BY p.id DESC
");

$stmt->execute();

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Projects — Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />

    <link rel="stylesheet" href="../../dist/css/output.css" />

</head>


<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">

    <div
        id="mobile-backdrop"
        class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden">
    </div>


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

                    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                        <div>

                            <p class="text-sm font-medium text-brand-600 dark:text-brand-400">
                                Task Management
                            </p>

                            <h2 class="text-display-sm text-slate-900 dark:text-white">
                                Projects
                            </h2>

                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                Manage your projects and track their progress.
                            </p>

                        </div>


                        <div class="flex gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='create_project.php'"
                                class="btn btn-primary">

                                + Create Project

                            </button>

                        </div>

                    </div>


                    <!-- Projects Table -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                        <!-- Table Header -->

                        <div
                            class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">

                            <div>

                                <h3 class="text-base font-semibold">
                                    All Projects
                                </h3>

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    <?= count($projects) ?> project(s) found
                                </p>

                            </div>

                        </div>


                        <!-- Table -->

                        <div class="overflow-x-auto">

                            <table class="w-full min-w-[900px] text-left text-sm">

                                <thead
                                    class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400">

                                    <tr>

                                        <th class="px-5 py-3 font-semibold">
                                            #
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Project
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Created By
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Status
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Start Date
                                        </th>

                                        <th class="px-5 py-3 font-semibold">
                                            Due Date
                                        </th>

                                        <th class="px-5 py-3 font-semibold text-right">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">


                                    <?php if (count($projects) > 0): ?>


                                        <?php foreach ($projects as $index => $project): ?>

                                            <tr
                                                class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">


                                                <!-- Number -->

                                                <td class="px-5 py-4">

                                                    <span class="font-medium">
                                                        <?= $index + 1 ?>
                                                    </span>

                                                </td>


                                                <!-- Project -->

                                                <td class="px-5 py-4">

                                                    <div>

                                                        <p class="font-semibold text-slate-900 dark:text-white">

                                                            <?= htmlspecialchars($project['name']) ?>

                                                        </p>


                                                        <?php if (!empty($project['description'])): ?>

                                                            <p
                                                                class="mt-1 max-w-md truncate text-xs text-slate-500 dark:text-slate-400">

                                                                <?= htmlspecialchars($project['description']) ?>

                                                            </p>

                                                        <?php endif; ?>

                                                    </div>

                                                </td>


                                                <!-- Created By -->

                                                <td class="px-5 py-4">

                                                    <span class="text-slate-700 dark:text-slate-300">

                                                        <?= htmlspecialchars($project['created_by_name'] ?? 'Unknown') ?>

                                                    </span>

                                                </td>


                                                <!-- Status -->

                                                <td class="px-5 py-4">


                                                    <?php if ($project['status'] === 'active'): ?>

                                                        <span
                                                            class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">

                                                            Active

                                                        </span>


                                                    <?php elseif ($project['status'] === 'completed'): ?>

                                                        <span
                                                            class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950/50 dark:text-brand-300">

                                                            Completed

                                                        </span>


                                                    <?php elseif ($project['status'] === 'on_hold'): ?>

                                                        <span
                                                            class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-950/50 dark:text-amber-400">

                                                            On Hold

                                                        </span>


                                                    <?php elseif ($project['status'] === 'cancelled'): ?>

                                                        <span
                                                            class="rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-950/50 dark:text-red-400">

                                                            Cancelled

                                                        </span>

                                                    <?php endif; ?>


                                                </td>


                                                <!-- Start Date -->

                                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">

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

                                                </td>


                                                <!-- Due Date -->

                                                <td class="px-5 py-4 text-slate-600 dark:text-slate-400">

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

                                                </td>


                                                <!-- Actions -->

                                                <td class="px-5 py-4">

                                                    <div class="flex justify-end gap-2">


                                                        <button
                                                            type="button"
                                                            onclick="window.location.href='view_project.php?id=<?= $project['id'] ?>'"
                                                            class="btn btn-secondary">

                                                            View

                                                        </button>


                                                        <button
                                                            type="button"
                                                            onclick="window.location.href='edit_project.php?id=<?= $project['id'] ?>'"
                                                            class="btn btn-secondary">

                                                            Edit

                                                        </button>


                                                        <button
                                                            type="button"
                                                            onclick="deleteProject(<?= $project['id'] ?>)"
                                                            class="btn btn-secondary">

                                                            Delete

                                                        </button>


                                                    </div>

                                                </td>


                                            </tr>

                                        <?php endforeach; ?>


                                    <?php else: ?>


                                        <tr>

                                            <td
                                                colspan="7"
                                                class="px-5 py-10 text-center">

                                                <div>

                                                    <p class="text-sm font-medium">
                                                        No projects found
                                                    </p>

                                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                        Create your first project to get started.
                                                    </p>

                                                </div>

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

        function deleteProject(id) {

            if (confirm("Are you sure you want to delete this project?")) {

                window.location.href = "delete_project.php?id=" + id;

            }

        }

    </script>


</body>

</html>