<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$comment_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($comment_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Comment
|--------------------------------------------------------------------------
| User can edit only their own comment.
| User must also be assigned to the task.
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        c.id,
        c.task_id,
        c.user_id,
        c.comment,

        t.title AS task_title,
        t.project_id,

        p.name AS project_name

    FROM task_comments c

    INNER JOIN tasks t
        ON c.task_id = t.id

    INNER JOIN projects p
        ON t.project_id = p.id

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    WHERE c.id = ?
      AND c.user_id = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $comment_id,
    $user_id,
    $user_id
]);

$commentData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commentData) {
    header("Location: tasks.php");
    exit;
}


$error = '';


/*
|--------------------------------------------------------------------------
| Update Comment
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $comment = trim($_POST['comment'] ?? '');

    if ($comment === '') {

        $error = "Comment is required.";

    } else {

        try {

            $conn->beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | Update Comment
            |--------------------------------------------------------------------------
            */

            $stmt = $conn->prepare("
                UPDATE task_comments

                SET comment = ?

                WHERE id = ?
                  AND user_id = ?
            ");

            $stmt->execute([
                $comment,
                $comment_id,
                $user_id
            ]);


            /*
            |--------------------------------------------------------------------------
            | Activity
            |--------------------------------------------------------------------------
            */

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
                    ?,
                    ?,
                    ?,
                    ?
                )
            ");

            $stmt->execute([
                $commentData['task_id'],
                $user_id,
                'comment_updated',
                'Updated a task comment.'
            ]);


            $conn->commit();


            header(
                "Location: view_task.php?id=" .
                (int) $commentData['task_id'] .
                "&success=comment_updated"
            );

            exit;

        } catch (Exception $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $error = "Unable to update comment. Please try again.";
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

    <title>Edit Comment — User</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

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


<div
    id="mobile-backdrop"
    class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"
></div>


<div class="flex min-h-full">


    <?php include('sidebar.php'); ?>


    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">


        <?php include('header.php'); ?>


        <main class="flex-1 overflow-auto p-4 lg:p-8">

            <div class="container max-w-2xl">


                <!-- Header -->

                <div class="mb-8">

                    <p
                        class="text-sm font-medium text-brand-600 dark:text-brand-400"
                    >
                        Task Management
                    </p>

                    <h2
                        class="mt-1 text-display-sm text-slate-900 dark:text-white"
                    >
                        Edit Comment
                    </h2>

                    <p
                        class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                    >
                        Update your comment on this task.
                    </p>

                </div>


                <!-- Task Information -->

                <div
                    class="mb-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <p
                        class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                    >
                        Task
                    </p>

                    <h3
                        class="mt-1 text-lg font-semibold text-slate-900 dark:text-white"
                    >
                        <?= htmlspecialchars($commentData['task_title']) ?>
                    </h3>

                    <p
                        class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                    >
                        Project:
                        <?= htmlspecialchars($commentData['project_name']) ?>
                    </p>

                </div>


                <!-- Error -->

                <?php if (!empty($error)): ?>

                    <div
                        class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400"
                    >
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endif; ?>


                <!-- Form -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >

                    <form
                        method="POST"
                        class="p-5"
                    >

                        <div>

                            <label
                                for="comment"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Comment
                                <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                name="comment"
                                id="comment"
                                rows="7"
                                required
                                autofocus
                                placeholder="Write your comment..."
                                class="w-full resize-y rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            ><?= htmlspecialchars($_POST['comment'] ?? $commentData['comment']) ?></textarea>

                        </div>


                        <!-- Buttons -->

                        <div
                            class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                        >

                            <button
                                type="button"
                                onclick="window.location.href='view_task.php?id=<?= (int) $commentData['task_id'] ?>'"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Update Comment
                            </button>

                        </div>

                    </form>

                </div>


            </div>

        </main>

    </div>

</div>


<script src="../../dist/js/app.js"></script>

</body>

</html>