<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Task ID
// --------------------------------------------------

$task_id = isset($_GET['task_id'])
    ? (int) $_GET['task_id']
    : (int) ($_POST['task_id'] ?? 0);


if ($task_id <= 0) {
    header('Location: list_tasks.php');
    exit;
}


// --------------------------------------------------
// Logged-in User
// --------------------------------------------------

$logged_in_user =
    $_SESSION['user_id']
    ?? $_SESSION['id']
    ?? null;


if (!$logged_in_user) {
    header('Location: ../login.php');
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
// Variables
// --------------------------------------------------

$error = '';

$subtask_title = '';


// --------------------------------------------------
// Add Subtask
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subtask_title = trim(
        $_POST['title'] ?? ''
    );


    // --------------------------------------------------
    // Validation
    // --------------------------------------------------

    if ($subtask_title === '') {

        $error = 'Subtask title is required.';

    } elseif (mb_strlen($subtask_title) > 255) {

        $error = 'Subtask title cannot exceed 255 characters.';

    } else {

        try {

            // --------------------------------------------------
            // Start Transaction
            // --------------------------------------------------

            $conn->beginTransaction();


            // --------------------------------------------------
            // Insert Subtask
            // --------------------------------------------------

            $stmt = $conn->prepare("
                INSERT INTO task_subtasks
                (
                    task_id,
                    title,
                    status,
                    created_by
                )
                VALUES
                (
                    :task_id,
                    :title,
                    'pending',
                    :created_by
                )
            ");


            $stmt->execute([
                ':task_id' => $task_id,
                ':title' => $subtask_title,
                ':created_by' => $logged_in_user
            ]);


            $subtask_id = (int) $conn->lastInsertId();


            // --------------------------------------------------
            // Activity
            // --------------------------------------------------

            $activity_description =
                'Subtask "' .
                $subtask_title .
                '" was added.';


            $stmt = $conn->prepare("
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


            $stmt->execute([
                ':task_id' => $task_id,
                ':user_id' => $logged_in_user,
                ':activity_type' => 'subtask_added',
                ':description' => $activity_description
            ]);


            // --------------------------------------------------
            // Commit
            // --------------------------------------------------

            $conn->commit();


            // --------------------------------------------------
            // Redirect
            // --------------------------------------------------

            header(
                'Location: view_task.php?id=' . $task_id
            );

            exit;


        } catch (PDOException $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $error =
                'Something went wrong while adding the subtask.';

        }

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

    <title>
        Add Subtask — Admin
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


        <main class="flex flex-1 items-center justify-center p-4 lg:p-8">


            <div class="w-full max-w-2xl">


                <!-- Header -->

                <div class="mb-6">

                    <p
                        class="text-sm font-medium text-brand-600 dark:text-brand-400"
                    >
                        Task Management
                    </p>


                    <h2
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Add Subtask
                    </h2>


                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Add a smaller task under the main task.
                    </p>

                </div>


                <!-- Card -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <!-- Task Information -->

                    <div
                        class="border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                    >

                        <p
                            class="text-xs font-medium uppercase tracking-wide text-slate-400"
                        >
                            Main Task
                        </p>


                        <div
                            class="mt-1 text-base font-semibold text-slate-900 dark:text-white"
                        >

                            <?= htmlspecialchars($task['title']) ?>

                        </div>


                        <div
                            class="mt-1 text-xs text-slate-500"
                        >

                            Task #<?= $task_id ?>

                        </div>

                    </div>


                    <!-- Error -->

                    <?php if ($error !== ''): ?>

                        <div class="px-6 pt-5">

                            <div
                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                            >

                                <?= htmlspecialchars($error) ?>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- Form -->

                    <form
                        method="POST"
                        action="add_subtask.php?task_id=<?= $task_id ?>"
                        class="p-6"
                    >


                        <input
                            type="hidden"
                            name="task_id"
                            value="<?= $task_id ?>"
                        >


                        <div>

                            <label
                                for="title"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >

                                Subtask Title

                                <span class="text-red-500">*</span>

                            </label>


                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="<?= htmlspecialchars($subtask_title) ?>"
                                maxlength="255"
                                required
                                autofocus
                                placeholder="Enter subtask title"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >


                            <p
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400"
                            >

                                Example: Prepare project documentation

                            </p>

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
                                Add Subtask
                            </button>

                        </div>


                    </form>


                </div>


            </div>


        </main>


        <?php include('footer.php'); ?>


    </div>

</div>


<script src="../dist/js/app.js"></script>


</body>

</html>