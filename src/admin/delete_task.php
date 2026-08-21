<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Task ID
// --------------------------------------------------

$task_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


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
// Check Task Exists
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
// Delete Task
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$logged_in_user) {

        header('Location: list_tasks.php');
        exit;

    }


    // Safety check

    $confirm_task_id = (int) ($_POST['task_id'] ?? 0);


    if ($confirm_task_id !== $task_id) {

        header('Location: list_tasks.php');
        exit;

    }


    try {

        // --------------------------------------------------
        // Start Transaction
        // --------------------------------------------------

        $conn->beginTransaction();


        // --------------------------------------------------
        // Delete Task Document Files
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_document_files
            WHERE task_document_id IN (
                SELECT id
                FROM task_documents
                WHERE task_id = :task_id
            )
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Delete Task Documents
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_documents
            WHERE task_id = :task_id
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Delete Activities
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_activities
            WHERE task_id = :task_id
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Delete Comments
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_comments
            WHERE task_id = :task_id
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Delete Subtasks
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_subtasks
            WHERE task_id = :task_id
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Delete Assignees
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_assignees
            WHERE task_id = :task_id
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Delete Task
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM tasks
            WHERE id = :task_id
        ");

        $stmt->execute([
            ':task_id' => $task_id
        ]);


        // --------------------------------------------------
        // Commit
        // --------------------------------------------------

        $conn->commit();


        header('Location: list_tasks.php?deleted=1');
        exit;


    } catch (PDOException $e) {

        if ($conn->inTransaction()) {
            $conn->rollBack();
        }


        header(
            'Location: view_task.php?id='
            . $task_id
            . '&delete_error=1'
        );

        exit;

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
        Delete Task — Admin
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


            <div class="w-full max-w-lg">


                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <!-- Header -->

                    <div
                        class="border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                    >

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"
                                    />

                                </svg>

                            </div>


                            <div>

                                <h2 class="text-lg font-semibold">

                                    Delete Task

                                </h2>


                                <p
                                    class="text-sm text-slate-500 dark:text-slate-400"
                                >

                                    This action cannot be undone.

                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- Body -->

                    <div class="p-6">


                        <p
                            class="text-sm leading-6 text-slate-600 dark:text-slate-300"
                        >

                            Are you sure you want to permanently delete this
                            task?

                        </p>


                        <div
                            class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950"
                        >

                            <p
                                class="text-xs font-medium uppercase tracking-wide text-slate-400"
                            >
                                Task
                            </p>


                            <p
                                class="mt-1 font-semibold text-slate-900 dark:text-white"
                            >

                                <?= htmlspecialchars($task['title']) ?>

                            </p>


                            <p
                                class="mt-1 text-xs text-slate-500"
                            >

                                Task ID:
                                #<?= $task_id ?>

                            </p>

                        </div>


                        <div
                            class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                        >

                            Deleting this task will also remove its:

                            <ul class="mt-2 list-disc pl-5">

                                <li>Assigned users</li>

                                <li>Subtasks</li>

                                <li>Comments</li>

                                <li>Activity history</li>

                                <li>Task documents</li>

                                <li>Task document file records</li>

                            </ul>

                        </div>


                        <!-- Form -->

                        <form
                            method="POST"
                            action="delete_task.php?id=<?= $task_id ?>"
                            class="mt-6"
                        >


                            <input
                                type="hidden"
                                name="task_id"
                                value="<?= $task_id ?>"
                            >


                            <div
                                class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
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
                                    class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30"
                                >
                                    Delete Task
                                </button>

                            </div>


                        </form>


                    </div>

                </div>


            </div>


        </main>


        <?php include('footer.php'); ?>


    </div>

</div>


<script src="../dist/js/app.js"></script>


</body>

</html>