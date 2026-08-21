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

$title = '';

$description = '';


// --------------------------------------------------
// Add Task Document
// --------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim(
        $_POST['title'] ?? ''
    );

    $description = trim(
        $_POST['description'] ?? ''
    );


    // --------------------------------------------------
    // Validation
    // --------------------------------------------------

    if ($title === '') {

        $error = 'Document title is required.';

    } elseif (mb_strlen($title) > 255) {

        $error = 'Document title cannot exceed 255 characters.';

    } elseif (mb_strlen($description) > 10000) {

        $error = 'Description cannot exceed 10000 characters.';

    } else {

        try {

            // --------------------------------------------------
            // Start Transaction
            // --------------------------------------------------

            $conn->beginTransaction();


            // --------------------------------------------------
            // Insert Document
            // --------------------------------------------------

            $stmt = $conn->prepare("
                INSERT INTO task_documents
                (
                    task_id,
                    title,
                    description,
                    uploaded_by
                )
                VALUES
                (
                    :task_id,
                    :title,
                    :description,
                    :uploaded_by
                )
            ");

            $stmt->execute([
                ':task_id' => $task_id,
                ':title' => $title,
                ':description' => $description !== ''
                    ? $description
                    : null,
                ':uploaded_by' => $logged_in_user
            ]);


            $document_id = (int) $conn->lastInsertId();


            // --------------------------------------------------
            // Activity
            // --------------------------------------------------

            $activity_description =
                'Task document "' .
                $title .
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
                ':activity_type' => 'document_added',
                ':description' => $activity_description
            ]);


            // --------------------------------------------------
            // Commit
            // --------------------------------------------------

            $conn->commit();


            // --------------------------------------------------
            // Redirect to Document
            // --------------------------------------------------

            header(
                'Location: view_task_document.php?id=' .
                $document_id
            );

            exit;


        } catch (PDOException $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }


            $error =
                'Something went wrong while creating the document.';

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
        Add Task Document — Admin
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


                    <h1
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Add Task Document
                    </h1>


                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Create a document for this task.
                    </p>

                </div>


                <!-- ==================================================
                     CARD
                =================================================== -->

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


                        <h2
                            class="mt-1 text-base font-semibold text-slate-900 dark:text-white"
                        >

                            <?= htmlspecialchars(
                                $task['title']
                            ) ?>

                        </h2>


                        <p
                            class="mt-1 text-xs text-slate-500"
                        >

                            Task #<?= $task_id ?>

                        </p>

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
                        action="add_task_document.php?task_id=<?= $task_id ?>"
                        class="p-6"
                    >


                        <input
                            type="hidden"
                            name="task_id"
                            value="<?= $task_id ?>"
                        >


                        <!-- Document Title -->

                        <div>

                            <label
                                for="title"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >

                                Document Title

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
                                placeholder="Enter document title"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >

                        </div>


                        <!-- Description -->

                        <div class="mt-5">

                            <label
                                for="description"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >

                                Description

                                <span
                                    class="font-normal text-slate-400"
                                >
                                    (Optional)
                                </span>

                            </label>


                            <textarea
                                id="description"
                                name="description"
                                rows="6"
                                maxlength="10000"
                                placeholder="Describe this document..."
                                class="w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            ><?= htmlspecialchars($description) ?></textarea>


                            <div
                                class="mt-2 text-right text-xs text-slate-500"
                            >

                                <span id="description-counter">
                                    0 / 10000
                                </span>

                            </div>

                        </div>


                        <!-- ==================================================
                             BUTTONS
                        =================================================== -->

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
                                Create Document
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


<script>

document.addEventListener('DOMContentLoaded', function () {

    const textarea =
        document.getElementById('description');

    const counter =
        document.getElementById('description-counter');


    if (!textarea || !counter) {
        return;
    }


    function updateCounter() {

        counter.textContent =
            textarea.value.length + ' / 10000';

    }


    textarea.addEventListener(
        'input',
        updateCounter
    );


    updateCounter();

});

</script>


</body>

</html>