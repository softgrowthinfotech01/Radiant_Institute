<?php

include('../check.php');
include('../conn.php');


// --------------------------------------------------
// Get Document ID
// --------------------------------------------------

$document_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : (int) ($_POST['document_id'] ?? 0);


if ($document_id <= 0) {
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
// Get Document
// --------------------------------------------------

$stmt = $conn->prepare("
    SELECT
        td.id,
        td.task_id,
        td.title,
        td.description,
        td.uploaded_by,
        td.created_at,
        t.title AS task_title
    FROM task_documents td

    INNER JOIN tasks t
        ON t.id = td.task_id

    WHERE td.id = :id

    LIMIT 1
");

$stmt->execute([
    ':id' => $document_id
]);

$document = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$document) {
    header('Location: list_tasks.php');
    exit;
}


$task_id = (int) $document['task_id'];


// --------------------------------------------------
// Form Values
// --------------------------------------------------

$title =
    $document['title'];

$description =
    $document['description'] ?? '';

$error = '';


// --------------------------------------------------
// Update Document
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

        $error =
            'Document title is required.';

    } elseif (
        mb_strlen($title) > 255
    ) {

        $error =
            'Document title cannot exceed 255 characters.';

    } elseif (
        mb_strlen($description) > 10000
    ) {

        $error =
            'Description cannot exceed 10000 characters.';

    } else {

        try {

            // --------------------------------------------------
            // Start Transaction
            // --------------------------------------------------

            $conn->beginTransaction();


            // --------------------------------------------------
            // Update Document
            // --------------------------------------------------

            $stmt = $conn->prepare("
                UPDATE task_documents
                SET
                    title = :title,
                    description = :description
                WHERE id = :id
                LIMIT 1
            ");

            $stmt->execute([
                ':title' => $title,
                ':description' =>
                    $description !== ''
                        ? $description
                        : null,
                ':id' => $document_id
            ]);


            // --------------------------------------------------
            // Activity Description
            // --------------------------------------------------

            $activity_description =
                'Task document was updated.';


            // Check what actually changed

            $old_title =
                $document['title'];

            $old_description =
                $document['description'] ?? '';


            $changes = [];


            if ($old_title !== $title) {

                $changes[] =
                    'title changed from "' .
                    $old_title .
                    '" to "' .
                    $title .
                    '"';

            }


            if ($old_description !== $description) {

                $changes[] =
                    'description updated';

            }


            if (!empty($changes)) {

                $activity_description =
                    'Task document "' .
                    $title .
                    '" updated: ' .
                    implode(
                        ', ',
                        $changes
                    ) .
                    '.';

            }


            // --------------------------------------------------
            // Activity
            // --------------------------------------------------

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
                ':task_id'
                    => $task_id,

                ':user_id'
                    => $logged_in_user,

                ':activity_type'
                    => 'document_updated',

                ':description'
                    => $activity_description
            ]);


            // --------------------------------------------------
            // Commit
            // --------------------------------------------------

            $conn->commit();


            // --------------------------------------------------
            // Redirect
            // --------------------------------------------------

            header(
                'Location: view_task_document.php?id=' .
                $document_id
            );

            exit;


        } catch (Throwable $e) {

            if (
                $conn->inTransaction()
            ) {

                $conn->rollBack();

            }


            $error =
                'Unable to update the document. Please try again.';

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
        Edit Task Document — Admin
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


                <!-- ==================================================
                     PAGE HEADER
                =================================================== -->

                <div class="mb-6">

                    <p
                        class="text-sm font-medium text-brand-600 dark:text-brand-400"
                    >
                        Task Document
                    </p>


                    <h1
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Edit Document
                    </h1>


                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Update the document information.
                    </p>

                </div>


                <!-- ==================================================
                     CARD
                =================================================== -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <!-- Document Info -->

                    <div
                        class="border-b border-slate-200 px-6 py-5 dark:border-slate-800"
                    >

                        <p
                            class="text-xs font-medium uppercase tracking-wide text-slate-400"
                        >
                            Task
                        </p>


                        <h2
                            class="mt-1 text-base font-semibold text-slate-900 dark:text-white"
                        >

                            <?= htmlspecialchars(
                                $document['task_title']
                            ) ?>

                        </h2>


                        <p
                            class="mt-1 text-xs text-slate-500"
                        >

                            Document #<?= $document_id ?>

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
                        action="edit_task_document.php?id=<?= $document_id ?>"
                        class="p-6"
                    >

                        <input
                            type="hidden"
                            name="document_id"
                            value="<?= $document_id ?>"
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
                                rows="7"
                                maxlength="10000"
                                placeholder="Describe this document..."
                                class="w-full resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-6 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            ><?= htmlspecialchars($description) ?></textarea>


                            <div
                                class="mt-2 text-right text-xs text-slate-500"
                            >

                                <span id="description-counter">
                                    <?= mb_strlen($description) ?> / 10000
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
                                onclick="window.location.href='view_task_document.php?id=<?= $document_id ?>'"
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
            textarea.value.length +
            ' / 10000';

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