<?php

include('../check.php');
include('../conn.php');

$error = '';


// --------------------------------------------------
// Get Project ID If Provided
// --------------------------------------------------

$project_id = isset($_GET['project_id'])
    ? (int) $_GET['project_id']
    : (int) ($_POST['project_id'] ?? 0);


// --------------------------------------------------
// Get Projects
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        id,
        name
    FROM projects
    ORDER BY name ASC
");

$stmt->execute();

$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Get Users
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        id,
        name
    FROM users
    ORDER BY name ASC
");

$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


// --------------------------------------------------
// Create Task
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $project_id = (int) ($_POST['project_id'] ?? 0);

    $title = trim($_POST['title'] ?? '');

    $description = trim($_POST['description'] ?? '');

    $priority = $_POST['priority'] ?? 'medium';

    $status = $_POST['status'] ?? 'pending';

    $start_date = !empty($_POST['start_date'])
        ? $_POST['start_date']
        : null;

    $due_date = !empty($_POST['due_date'])
        ? $_POST['due_date']
        : null;

    $selected_assignees = $_POST['assignees'] ?? [];

    $selected_assignees = array_map(
        'intval',
        (array) $selected_assignees
    );

    $selected_assignees = array_values(
        array_unique($selected_assignees)
    );


    // --------------------------------------------------
    // Logged-in User
    // --------------------------------------------------

    $created_by =
        $_SESSION['user_id']
        ?? $_SESSION['id']
        ?? null;


    // --------------------------------------------------
    // Validation
    // --------------------------------------------------

    if ($project_id <= 0) {

        $error = 'Please select a project.';

    } elseif ($title === '') {

        $error = 'Task title is required.';

    } elseif (
        !in_array(
            $priority,
            [
                'low',
                'medium',
                'high',
                'urgent'
            ]
        )
    ) {

        $error = 'Invalid task priority.';

    } elseif (
        !in_array(
            $status,
            [
                'pending',
                'in_progress',
                'completed',
                'on_hold',
                'cancelled'
            ]
        )
    ) {

        $error = 'Invalid task status.';

    } elseif (
        $start_date &&
        $due_date &&
        $due_date < $start_date
    ) {

        $error = 'Due date cannot be earlier than the start date.';

    } elseif (!$created_by) {

        $error = 'Unable to identify the logged-in user. Please login again.';

    } else {

        try {

            // --------------------------------------------------
            // Verify Project Exists
            // --------------------------------------------------

            $stmt = $conn->prepare("
                SELECT id
                FROM projects
                WHERE id = :id
                LIMIT 1
            ");

            $stmt->execute([
                ':id' => $project_id
            ]);

            if (!$stmt->fetch()) {

                $error = 'Selected project does not exist.';

            } else {

                // --------------------------------------------------
                // Start Transaction
                // --------------------------------------------------

                $conn->beginTransaction();


                // --------------------------------------------------
                // Insert Task
                // --------------------------------------------------

                $stmt = $conn->prepare("
                    INSERT INTO tasks
                    (
                        project_id,
                        title,
                        description,
                        priority,
                        status,
                        start_date,
                        due_date,
                        created_by
                    )
                    VALUES
                    (
                        :project_id,
                        :title,
                        :description,
                        :priority,
                        :status,
                        :start_date,
                        :due_date,
                        :created_by
                    )
                ");


                $stmt->execute([
                    ':project_id' => $project_id,
                    ':title' => $title,
                    ':description' => $description !== ''
                        ? $description
                        : null,
                    ':priority' => $priority,
                    ':status' => $status,
                    ':start_date' => $start_date,
                    ':due_date' => $due_date,
                    ':created_by' => $created_by
                ]);


                $task_id = $conn->lastInsertId();


                // --------------------------------------------------
                // Insert Assignees
                // --------------------------------------------------

                if (count($selected_assignees) > 0) {

                    $assigneeStmt = $conn->prepare("
                        INSERT INTO task_assignees
                        (
                            task_id,
                            user_id
                        )
                        VALUES
                        (
                            :task_id,
                            :user_id
                        )
                    ");


                    foreach ($selected_assignees as $user_id) {

                        // Verify user exists
                        $userCheck = $conn->prepare("
                            SELECT id
                            FROM users
                            WHERE id = :user_id
                            LIMIT 1
                        ");

                        $userCheck->execute([
                            ':user_id' => $user_id
                        ]);


                        if ($userCheck->fetch()) {

                            $assigneeStmt->execute([
                                ':task_id' => $task_id,
                                ':user_id' => $user_id
                            ]);

                        }

                    }

                }


                // --------------------------------------------------
                // Create Activity
                // --------------------------------------------------

                $activityStmt = $conn->prepare("
                    INSERT INTO task_activities
                    (
                        task_id,
                        user_id,
                        activity_type,
                        description
                    )
                    VALUES
                    (
                        :task_id,
                        :user_id,
                        :activity_type,
                        :description
                    )
                ");


                $activityStmt->execute([
                    ':task_id' => $task_id,
                    ':user_id' => $created_by,
                    ':activity_type' => 'task_created',
                    ':description' => 'Task was created.'
                ]);


                // --------------------------------------------------
                // Commit
                // --------------------------------------------------

                $conn->commit();


                header(
                    'Location: view_task.php?id=' . $task_id
                );

                exit;

            }

        } catch (PDOException $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $error = 'Something went wrong while creating the task.';

        }

    }

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

    <title>Create Task — Admin</title>


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
                                Create Task
                            </h2>


                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                Create a new task and assign it to one or more users.
                            </p>

                        </div>


                        <div class="flex gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='list_tasks.php'"
                                class="btn btn-secondary"
                            >
                                Back to Tasks
                            </button>

                        </div>

                    </div>


                    <!-- Error -->

                    <?php if ($error !== ''): ?>

                        <div
                            class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                        >

                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <!-- Form -->

                    <div
                        class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                    >


                        <div
                            class="border-b border-slate-200 px-5 py-4 dark:border-slate-800"
                        >

                            <h3 class="text-base font-semibold">
                                Task Details
                            </h3>


                            <p
                                class="mt-1 text-xs text-slate-500 dark:text-slate-400"
                            >
                                Enter the task information below.
                            </p>

                        </div>


                        <form
                            method="POST"
                            action=""
                            class="p-5"
                        >


                            <div class="grid gap-6 lg:grid-cols-2">


                                <!-- Project -->

                                <div>

                                    <label
                                        for="project_id"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Project
                                        <span class="text-red-500">*</span>
                                    </label>


                                    <select
                                        id="project_id"
                                        name="project_id"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    >

                                        <option value="">
                                            Select Project
                                        </option>


                                        <?php foreach ($projects as $project): ?>

                                            <option
                                                value="<?= $project['id'] ?>"
                                                <?= $project_id == $project['id'] ? 'selected' : '' ?>
                                            >

                                                <?= htmlspecialchars($project['name']) ?>

                                            </option>

                                        <?php endforeach; ?>


                                    </select>

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
                                            value="pending"
                                            <?= ($_POST['status'] ?? 'pending') === 'pending' ? 'selected' : '' ?>
                                        >
                                            Pending
                                        </option>


                                        <option
                                            value="in_progress"
                                            <?= ($_POST['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>
                                        >
                                            In Progress
                                        </option>


                                        <option
                                            value="completed"
                                            <?= ($_POST['status'] ?? '') === 'completed' ? 'selected' : '' ?>
                                        >
                                            Completed
                                        </option>


                                        <option
                                            value="on_hold"
                                            <?= ($_POST['status'] ?? '') === 'on_hold' ? 'selected' : '' ?>
                                        >
                                            On Hold
                                        </option>


                                        <option
                                            value="cancelled"
                                            <?= ($_POST['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>
                                        >
                                            Cancelled
                                        </option>

                                    </select>

                                </div>


                                <!-- Title -->

                                <div class="lg:col-span-2">

                                    <label
                                        for="title"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Task Title
                                        <span class="text-red-500">*</span>
                                    </label>


                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                                        required
                                        maxlength="255"
                                        placeholder="Enter task title"
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
                                        rows="6"
                                        placeholder="Describe the task..."
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

                                </div>


                                <!-- Priority -->

                                <div>

                                    <label
                                        for="priority"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Priority
                                    </label>


                                    <select
                                        id="priority"
                                        name="priority"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    >

                                        <option
                                            value="low"
                                            <?= ($_POST['priority'] ?? 'medium') === 'low' ? 'selected' : '' ?>
                                        >
                                            Low
                                        </option>


                                        <option
                                            value="medium"
                                            <?= ($_POST['priority'] ?? 'medium') === 'medium' ? 'selected' : '' ?>
                                        >
                                            Medium
                                        </option>


                                        <option
                                            value="high"
                                            <?= ($_POST['priority'] ?? '') === 'high' ? 'selected' : '' ?>
                                        >
                                            High
                                        </option>


                                        <option
                                            value="urgent"
                                            <?= ($_POST['priority'] ?? '') === 'urgent' ? 'selected' : '' ?>
                                        >
                                            Urgent
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
                                        value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>"
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
                                        value="<?= htmlspecialchars($_POST['due_date'] ?? '') ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    />

                                </div>


                                <!-- Assignees -->

                                <div class="lg:col-span-2">

                                    <label
                                        for="assignees"
                                        class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                                    >
                                        Assign Users
                                    </label>


                                    <select
                                        id="assignees"
                                        name="assignees[]"
                                        multiple
                                        size="6"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    >


                                        <?php

                                        $oldAssignees = array_map(
                                            'intval',
                                            (array) ($_POST['assignees'] ?? [])
                                        );

                                        ?>


                                        <?php foreach ($users as $user): ?>

                                            <option
                                                value="<?= $user['id'] ?>"
                                                <?= in_array(
                                                    (int) $user['id'],
                                                    $oldAssignees
                                                )
                                                    ? 'selected'
                                                    : ''
                                                ?>
                                            >

                                                <?= htmlspecialchars($user['name']) ?>

                                            </option>

                                        <?php endforeach; ?>


                                    </select>


                                    <p
                                        class="mt-2 text-xs text-slate-500 dark:text-slate-400"
                                    >
                                        Hold Ctrl (Windows) or Command (Mac) to select multiple users.
                                    </p>

                                </div>


                            </div>


                            <!-- Buttons -->

                            <div
                                class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end dark:border-slate-800"
                            >

                                <button
                                    type="button"
                                    onclick="window.location.href='list_tasks.php'"
                                    class="btn btn-secondary"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Create Task
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

        const startDate =
            document.getElementById('start_date');

        const dueDate =
            document.getElementById('due_date');


        if (startDate && dueDate) {

            function updateDueDateMinimum() {

                dueDate.min =
                    startDate.value || '';

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