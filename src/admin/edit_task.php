<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Task ID
// --------------------------------------------------

$task_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : (int) ($_POST['task_id'] ?? 0);


if ($task_id <= 0) {
    header('Location: list_tasks.php');
    exit;
}


// --------------------------------------------------
// Get Logged-in User
// --------------------------------------------------

$logged_in_user =
    $_SESSION['user_id']
    ?? $_SESSION['id']
    ?? null;


// --------------------------------------------------
// Get Task
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        t.*,
        p.name AS project_name
    FROM tasks t
    LEFT JOIN projects p
        ON p.id = t.project_id
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
// Get Existing Assignees
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT user_id
    FROM task_assignees
    WHERE task_id = :task_id
");

$stmt->execute([
    ':task_id' => $task_id
]);

$existing_assignees = $stmt->fetchAll(PDO::FETCH_COLUMN);

$existing_assignees = array_map(
    'intval',
    $existing_assignees
);


// --------------------------------------------------
// Error
// --------------------------------------------------

$error = '';


// --------------------------------------------------
// Update Task
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

    } elseif (!$logged_in_user) {

        $error = 'Unable to identify the logged-in user. Please login again.';

    } else {

        try {

            // --------------------------------------------------
            // Verify Project
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
                // Update Task
                // --------------------------------------------------

                $stmt = $conn->prepare("
                    UPDATE tasks
                    SET
                        project_id = :project_id,
                        title = :title,
                        description = :description,
                        priority = :priority,
                        status = :status,
                        start_date = :start_date,
                        due_date = :due_date
                    WHERE id = :id
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
                    ':id' => $task_id
                ]);


                // --------------------------------------------------
                // Replace Assignees
                // --------------------------------------------------

                $stmt = $conn->prepare("
                    DELETE FROM task_assignees
                    WHERE task_id = :task_id
                ");

                $stmt->execute([
                    ':task_id' => $task_id
                ]);


                // --------------------------------------------------
                // Insert New Assignees
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


                    $userCheck = $conn->prepare("
                        SELECT id
                        FROM users
                        WHERE id = :user_id
                        LIMIT 1
                    ");


                    foreach ($selected_assignees as $user_id) {

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
                // Activity
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
                    ':user_id' => $logged_in_user,
                    ':activity_type' => 'task_updated',
                    ':description' => 'Task details were updated.'
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

            $error = 'Something went wrong while updating the task.';

        }

    }

}


// --------------------------------------------------
// Values To Display
// --------------------------------------------------

$form_project_id = $_POST['project_id']
    ?? $task['project_id'];

$form_title = $_POST['title']
    ?? $task['title'];

$form_description = $_POST['description']
    ?? $task['description'];

$form_priority = $_POST['priority']
    ?? $task['priority'];

$form_status = $_POST['status']
    ?? $task['status'];

$form_start_date = $_POST['start_date']
    ?? $task['start_date'];

$form_due_date = $_POST['due_date']
    ?? $task['due_date'];


$form_assignees = isset($_POST['assignees'])
    ? array_map(
        'intval',
        (array) $_POST['assignees']
    )
    : $existing_assignees;

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
        Edit Task — Admin
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


                    <!-- Header -->

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
                                Edit Task
                            </h2>


                            <p
                                class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                            >
                                Update task details and assigned users.
                            </p>

                        </div>


                        <div class="flex gap-2">

                            <button
                                type="button"
                                onclick="window.location.href='view_task.php?id=<?= $task_id ?>'"
                                class="btn btn-secondary"
                            >
                                Back to Task
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

                        </div>


                        <form
                            method="POST"
                            action="edit_task.php?id=<?= $task_id ?>"
                            class="p-5"
                        >

                            <input
                                type="hidden"
                                name="task_id"
                                value="<?= $task_id ?>"
                            />


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
                                                <?= (int) $form_project_id === (int) $project['id']
                                                    ? 'selected'
                                                    : ''
                                                ?>
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
                                            <?= $form_status === 'pending' ? 'selected' : '' ?>
                                        >
                                            Pending
                                        </option>


                                        <option
                                            value="in_progress"
                                            <?= $form_status === 'in_progress' ? 'selected' : '' ?>
                                        >
                                            In Progress
                                        </option>


                                        <option
                                            value="completed"
                                            <?= $form_status === 'completed' ? 'selected' : '' ?>
                                        >
                                            Completed
                                        </option>


                                        <option
                                            value="on_hold"
                                            <?= $form_status === 'on_hold' ? 'selected' : '' ?>
                                        >
                                            On Hold
                                        </option>


                                        <option
                                            value="cancelled"
                                            <?= $form_status === 'cancelled' ? 'selected' : '' ?>
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
                                        value="<?= htmlspecialchars($form_title) ?>"
                                        required
                                        maxlength="255"
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
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                                    ><?= htmlspecialchars($form_description ?? '') ?></textarea>

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
                                            <?= $form_priority === 'low' ? 'selected' : '' ?>
                                        >
                                            Low
                                        </option>


                                        <option
                                            value="medium"
                                            <?= $form_priority === 'medium' ? 'selected' : '' ?>
                                        >
                                            Medium
                                        </option>


                                        <option
                                            value="high"
                                            <?= $form_priority === 'high' ? 'selected' : '' ?>
                                        >
                                            High
                                        </option>


                                        <option
                                            value="urgent"
                                            <?= $form_priority === 'urgent' ? 'selected' : '' ?>
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
                                        value="<?= htmlspecialchars($form_start_date ?? '') ?>"
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
                                        value="<?= htmlspecialchars($form_due_date ?? '') ?>"
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

                                        <?php foreach ($users as $user): ?>

                                            <option
                                                value="<?= $user['id'] ?>"
                                                <?= in_array(
                                                    (int) $user['id'],
                                                    $form_assignees,
                                                    true
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
                                    onclick="window.location.href='view_task.php?id=<?= $task_id ?>'"
                                    class="btn btn-secondary"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Update Task
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