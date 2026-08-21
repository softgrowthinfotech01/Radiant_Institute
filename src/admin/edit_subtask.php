<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Subtask ID
// --------------------------------------------------

$subtask_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : (int) ($_POST['subtask_id'] ?? 0);


if ($subtask_id <= 0) {
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
// Get Subtask
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        ts.*,
        t.title AS task_title
    FROM task_subtasks ts
    INNER JOIN tasks t
        ON t.id = ts.task_id
    WHERE ts.id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $subtask_id
]);

$subtask = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$subtask) {
    header('Location: list_tasks.php');
    exit;
}


$task_id = (int) $subtask['task_id'];


// --------------------------------------------------
// Variables
// --------------------------------------------------

$error = '';

$title = $subtask['title'];

$status = $subtask['status'];


// --------------------------------------------------
// Update Subtask
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');

    $status = $_POST['status'] ?? 'pending';


    // --------------------------------------------------
    // Validation
    // --------------------------------------------------

    if ($title === '') {

        $error = 'Subtask title is required.';

    } elseif (mb_strlen($title) > 255) {

        $error = 'Subtask title cannot exceed 255 characters.';

    } elseif (!in_array($status, ['pending', 'completed'], true)) {

        $error = 'Invalid subtask status.';

    } else {

        try {

            // --------------------------------------------------
            // Start Transaction
            // --------------------------------------------------

            $conn->beginTransaction();


            // --------------------------------------------------
            // Check Changes
            // --------------------------------------------------

            $old_title = $subtask['title'];

            $old_status = $subtask['status'];


            $changes = [];


            if ($old_title !== $title) {

                $changes[] =
                    'title changed from "' .
                    $old_title .
                    '" to "' .
                    $title .
                    '"';

            }


            if ($old_status !== $status) {

                $changes[] =
                    'status changed from "' .
                    ucfirst(str_replace('_', ' ', $old_status)) .
                    '" to "' .
                    ucfirst(str_replace('_', ' ', $status)) .
                    '"';

            }


            // --------------------------------------------------
            // Update Subtask
            // --------------------------------------------------

            $stmt = $conn->prepare("
                UPDATE task_subtasks
                SET
                    title = :title,
                    status = :status
                WHERE id = :id
            ");

            $stmt->execute([
                ':title' => $title,
                ':status' => $status,
                ':id' => $subtask_id
            ]);


            // --------------------------------------------------
            // Activity
            // --------------------------------------------------

            if (count($changes) > 0) {

                $activity_description =
                    'Subtask "' .
                    $old_title .
                    '" updated: ' .
                    implode(', ', $changes) .
                    '.';


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
                    ':activity_type' => 'subtask_updated',
                    ':description' => $activity_description
                ]);

            }


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
                'Something went wrong while updating the subtask.';

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
        Edit Subtask — Admin
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >


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


<div class="flex min-h-full">


    <?php include('sidebar.php'); ?>


    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


        <?php include('header.php'); ?>


        <main class="flex flex-1 items-center justify-center p-4 lg:p-8">


            <div class="w-full max-w-2xl">


                <!-- ==================================================
                     PAGE HEADER
                =================================================== -->

                <div class="mb-6">

                    <p
                        class="text-sm font-medium text-brand-600 dark:text-brand-400"
                    >
                        Task Management
                    </p>


                    <h2
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Edit Subtask
                    </h2>


                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Update the subtask information and status.
                    </p>

                </div>


                <!-- ==================================================
                     CARD
                =================================================== -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <!-- Main Task -->

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

                            <?= htmlspecialchars($subtask['task_title']) ?>

                        </div>


                        <div
                            class="mt-1 text-xs text-slate-500"
                        >

                            Task #<?= $task_id ?>

                            ·

                            Subtask #<?= $subtask_id ?>

                        </div>

                    </div>


                    <!-- ==================================================
                         ERROR
                    =================================================== -->

                    <?php if ($error !== ''): ?>

                        <div class="px-6 pt-5">

                            <div
                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                            >

                                <?= htmlspecialchars($error) ?>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- ==================================================
                         FORM
                    =================================================== -->

                    <form
                        method="POST"
                        action="edit_subtask.php?id=<?= $subtask_id ?>"
                        class="p-6"
                    >


                        <input
                            type="hidden"
                            name="subtask_id"
                            value="<?= $subtask_id ?>"
                        >


                        <!-- Title -->

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
                                value="<?= htmlspecialchars($title) ?>"
                                maxlength="255"
                                required
                                autofocus
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >


                            <p
                                class="mt-2 text-xs text-slate-500 dark:text-slate-400"
                            >

                                Maximum 255 characters.

                            </p>

                        </div>


                        <!-- Status -->

                        <div class="mt-6">

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
                                    <?= $status === 'pending' ? 'selected' : '' ?>
                                >
                                    Pending
                                </option>


                                <option
                                    value="completed"
                                    <?= $status === 'completed' ? 'selected' : '' ?>
                                >
                                    Completed
                                </option>

                            </select>

                        </div>


                        <!-- ==================================================
                             BUTTONS
                        =================================================== -->

                        <div
                            class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-between dark:border-slate-800"
                        >


                            <button
                                type="button"
                                onclick="window.location.href='delete_subtask.php?id=<?= $subtask_id ?>'"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
                            >
                                Delete Subtask
                            </button>


                            <div
                                class="flex flex-col-reverse gap-3 sm:flex-row"
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
                                    Save Changes
                                </button>

                            </div>


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