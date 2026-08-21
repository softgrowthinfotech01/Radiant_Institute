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
        ts.id,
        ts.task_id,
        ts.title,
        ts.status,
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
// Delete Subtask
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $confirm_subtask_id =
        (int) ($_POST['subtask_id'] ?? 0);


    if ($confirm_subtask_id !== $subtask_id) {
        header('Location: view_task.php?id=' . $task_id);
        exit;
    }


    try {

        // --------------------------------------------------
        // Start Transaction
        // --------------------------------------------------

        $conn->beginTransaction();


        // --------------------------------------------------
        // Delete Subtask
        // --------------------------------------------------

        $stmt = $conn->prepare("
            DELETE FROM task_subtasks
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $subtask_id
        ]);


        // --------------------------------------------------
        // Activity
        // --------------------------------------------------

        $activity_description =
            'Subtask "' .
            $subtask['title'] .
            '" was deleted.';


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
            ':activity_type' => 'subtask_deleted',
            ':description' => $activity_description
        ]);


        // --------------------------------------------------
        // Commit
        // --------------------------------------------------

        $conn->commit();


        header(
            'Location: view_task.php?id=' . $task_id
        );

        exit;


    } catch (PDOException $e) {

        if ($conn->inTransaction()) {
            $conn->rollBack();
        }


        header(
            'Location: edit_subtask.php?id=' .
            $subtask_id .
            '&delete_error=1'
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
        Delete Subtask — Admin
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
                                    Delete Subtask
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
                            subtask?

                        </p>


                        <!-- Subtask -->

                        <div
                            class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950"
                        >

                            <p
                                class="text-xs font-medium uppercase tracking-wide text-slate-400"
                            >
                                Subtask
                            </p>


                            <p
                                class="mt-1 font-semibold text-slate-900 dark:text-white"
                            >

                                <?= htmlspecialchars(
                                    $subtask['title']
                                ) ?>

                            </p>


                            <p
                                class="mt-2 text-xs text-slate-500"
                            >

                                Main Task:
                                <?= htmlspecialchars(
                                    $subtask['task_title']
                                ) ?>

                            </p>


                            <p
                                class="mt-1 text-xs text-slate-500"
                            >

                                Subtask ID:
                                #<?= $subtask_id ?>

                            </p>

                        </div>


                        <!-- Warning -->

                        <div
                            class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-700 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-400"
                        >

                            The subtask will be permanently removed from this
                            task.

                        </div>


                        <!-- Form -->

                        <form
                            method="POST"
                            action="delete_subtask.php?id=<?= $subtask_id ?>"
                            class="mt-6"
                        >

                            <input
                                type="hidden"
                                name="subtask_id"
                                value="<?= $subtask_id ?>"
                            >


                            <div
                                class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                            >

                                <button
                                    type="button"
                                    onclick="window.location.href='edit_subtask.php?id=<?= $subtask_id ?>'"
                                    class="btn btn-secondary"
                                >
                                    Cancel
                                </button>


                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/30"
                                >
                                    Delete Subtask
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