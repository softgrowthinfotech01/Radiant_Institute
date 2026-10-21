<?php

include('../check.php');
include('../conn.php');

$error = '';


// --------------------------------------------------
// Get Project ID
// --------------------------------------------------

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: list_projects.php');
    exit;
}


// --------------------------------------------------
// Get Existing Project
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT *
    FROM projects
    WHERE id = :id
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
// Update Project
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'active';

    $start_date = !empty($_POST['start_date'])
        ? $_POST['start_date']
        : null;

    $due_date = !empty($_POST['due_date'])
        ? $_POST['due_date']
        : null;


    // --------------------------------------------------
    // Validation
    // --------------------------------------------------

    if ($name === '') {

        $error = 'Project name is required.';

    } elseif (
        !in_array(
            $status,
            ['active', 'completed', 'on_hold', 'cancelled']
        )
    ) {

        $error = 'Invalid project status.';

    } elseif (
        $start_date &&
        $due_date &&
        $due_date < $start_date
    ) {

        $error = 'Due date cannot be earlier than the start date.';

    } else {

        try {

            $stmt = $conn->prepare("
                UPDATE projects
                SET
                    name = :name,
                    description = :description,
                    status = :status,
                    start_date = :start_date,
                    due_date = :due_date
                WHERE id = :id
            ");

            $stmt->execute([
                ':name' => $name,
                ':description' => $description !== ''
                    ? $description
                    : null,
                ':status' => $status,
                ':start_date' => $start_date,
                ':due_date' => $due_date,
                ':id' => $id
            ]);


            header('Location: list_projects.php');
            exit;


        } catch (PDOException $e) {

            $error = 'Something went wrong while updating the project.';

        }

    }


    // --------------------------------------------------
    // Keep Submitted Values After Validation Error
    // --------------------------------------------------

    $project['name'] = $name;
    $project['description'] = $description;
    $project['status'] = $status;
    $project['start_date'] = $start_date;
    $project['due_date'] = $due_date;

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

    <title>Edit Project — Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />

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
                                Edit Project
                            </h2>


                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                Update the project information below.
                            </p>

                        </div>


                        <div class="flex gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='list_projects.php'"
                                class="btn btn-secondary"
                            >
                                Back to Projects
                            </button>

                        </div>

                    </div>


                    <!-- Error Message -->

                    <?php if ($error !== ''): ?>

                        <div
                            class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                        >

                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <!-- Form Card -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >


                        <div
                            class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <h3 class="text-base font-semibold">
                                Project Details
                            </h3>

                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Update the information for this project.
                            </p>

                        </div>


                        <form
                            method="POST"
                            action=""
                            class="p-5"
                        >


                            <div class="grid gap-6 lg:grid-cols-2">


                                <!-- Project Name -->

                                <div class="lg:col-span-2">

                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Project Name
                                        <span class="text-red-500">*</span>
                                    </label>


                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="<?= htmlspecialchars($project['name']) ?>"
                                        required
                                        maxlength="255"
                                        placeholder="Enter project name"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    />

                                </div>


                                <!-- Description -->

                                <div class="lg:col-span-2">

                                    <label
                                        for="description"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Description
                                    </label>


                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="5"
                                        placeholder="Enter project description"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    ><?= htmlspecialchars($project['description'] ?? '') ?></textarea>

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

                                        <option
                                            value="active"
                                            <?= $project['status'] === 'active' ? 'selected' : '' ?>
                                        >
                                            Active
                                        </option>


                                        <option
                                            value="on_hold"
                                            <?= $project['status'] === 'on_hold' ? 'selected' : '' ?>
                                        >
                                            On Hold
                                        </option>


                                        <option
                                            value="completed"
                                            <?= $project['status'] === 'completed' ? 'selected' : '' ?>
                                        >
                                            Completed
                                        </option>


                                        <option
                                            value="cancelled"
                                            <?= $project['status'] === 'cancelled' ? 'selected' : '' ?>
                                        >
                                            Cancelled
                                        </option>

                                    </select>

                                </div>


                                <!-- Start Date -->

                                <div>

                                    <label
                                        for="start_date"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Start Date
                                    </label>


                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        value="<?= htmlspecialchars($project['start_date'] ?? '') ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    />

                                </div>


                                <!-- Due Date -->

                                <div>

                                    <label
                                        for="due_date"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Due Date
                                    </label>


                                    <input
                                        type="date"
                                        id="due_date"
                                        name="due_date"
                                        value="<?= htmlspecialchars($project['due_date'] ?? '') ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    />

                                </div>


                            </div>


                            <!-- Form Buttons -->

                            <div
                                class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-800"
                            >

                                <button
                                    type="button"
                                    onclick="window.location.href='list_projects.php'"
                                    class="btn btn-secondary"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Update Project
                                </button>

                            </div>


                        </form>

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

        const startDate = document.getElementById('start_date');
        const dueDate = document.getElementById('due_date');

        if (startDate && dueDate) {

            function updateDueDateMinimum() {

                dueDate.min = startDate.value || '';

            }

            startDate.addEventListener(
                'change',
                updateDueDateMinimum
            );

            updateDueDateMinimum();

        }

    </script>


</body>

</html>