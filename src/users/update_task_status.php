<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$task_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($task_id <= 0) {
    header("Location: tasks.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Check that the logged-in user is assigned to this task
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        t.id,
        t.title,
        t.status,
        t.project_id,
        p.name AS project_name

    FROM tasks t

    INNER JOIN task_assignees ta
        ON t.id = ta.task_id

    INNER JOIN projects p
        ON t.project_id = p.id

    WHERE t.id = ?
      AND ta.user_id = ?

    LIMIT 1
");

$stmt->execute([
    $task_id,
    $user_id
]);

$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: tasks.php");
    exit;
}


$allowedStatuses = [
    'pending',
    'in_progress',
    'completed',
    'on_hold',
    'cancelled'
];

$error = '';


/*
|--------------------------------------------------------------------------
| Update Status
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $new_status = $_POST['status'] ?? '';

    if (!in_array($new_status, $allowedStatuses, true)) {

        $error = "Invalid task status.";

    } elseif ($new_status === $task['status']) {

        $error = "Please select a different status.";

    } else {

        try {

            $conn->beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | Update task
            |--------------------------------------------------------------------------
            */

            $stmt = $conn->prepare("
                UPDATE tasks
                SET status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $new_status,
                $task_id
            ]);


            /*
            |--------------------------------------------------------------------------
            | Activity Description
            |--------------------------------------------------------------------------
            */

            $statusLabels = [
                'pending' => 'Pending',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'on_hold' => 'On Hold',
                'cancelled' => 'Cancelled'
            ];

            $oldStatusLabel = $statusLabels[$task['status']] ?? $task['status'];
            $newStatusLabel = $statusLabels[$new_status] ?? $new_status;

            $activityDescription =
                "changed task status from "
                . $oldStatusLabel
                . " to "
                . $newStatusLabel;


            /*
            |--------------------------------------------------------------------------
            | Insert Activity
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
                $task_id,
                $user_id,
                'status_changed',
                $activityDescription
            ]);


            $conn->commit();


            /*
            |--------------------------------------------------------------------------
            | Redirect back to task
            |--------------------------------------------------------------------------
            */

            header(
                "Location: view_task.php?id=" . $task_id . "&status=updated"
            );

            exit;

        } catch (Exception $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $error = "Unable to update task status. Please try again.";
        }
    }
}


/*
|--------------------------------------------------------------------------
| Status Helpers
|--------------------------------------------------------------------------
*/

function statusClass($status)
{
    switch ($status) {

        case 'pending':
            return 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-400';

        case 'in_progress':
            return 'border-brand-200 bg-brand-50 text-brand-700 dark:border-brand-900 dark:bg-brand-950/40 dark:text-brand-400';

        case 'completed':
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-400';

        case 'on_hold':
            return 'border-orange-200 bg-orange-50 text-orange-700 dark:border-orange-900 dark:bg-orange-950/40 dark:text-orange-400';

        case 'cancelled':
            return 'border-red-200 bg-red-50 text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-400';

        default:
            return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300';
    }
}


function statusLabel($status)
{
    switch ($status) {

        case 'pending':
            return 'Pending';

        case 'in_progress':
            return 'In Progress';

        case 'completed':
            return 'Completed';

        case 'on_hold':
            return 'On Hold';

        case 'cancelled':
            return 'Cancelled';

        default:
            return ucfirst($status);
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
        Update Task Status
    </title>

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
                        Update Task Status
                    </h2>

                    <p
                        class="mt-1 text-sm text-slate-600 dark:text-slate-400"
                    >
                        Update the current progress of this task.
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


                <!-- Form Card -->

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900"
                >


                    <!-- Task -->

                    <div
                        class="border-b border-slate-200 px-5 py-5 dark:border-slate-800"
                    >

                        <p
                            class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400"
                        >
                            Task
                        </p>

                        <h3
                            class="mt-1 text-lg font-semibold text-slate-900 dark:text-white"
                        >
                            <?= htmlspecialchars($task['title']) ?>
                        </h3>

                        <p
                            class="mt-1 text-sm text-slate-500 dark:text-slate-400"
                        >
                            Project:
                            <?= htmlspecialchars($task['project_name']) ?>
                        </p>

                    </div>


                    <form
                        method="POST"
                        class="p-5"
                    >


                        <div>

                            <label
                                for="status"
                                class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Task Status
                            </label>


                            <select
                                name="status"
                                id="status"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white"
                            >

                                <?php foreach ($allowedStatuses as $status): ?>

                                    <option
                                        value="<?= htmlspecialchars($status) ?>"
                                        <?= $task['status'] === $status ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars(
                                            statusLabel($status)
                                        ) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- Current Status -->

                        <div class="mt-5">

                            <p
                                class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300"
                            >
                                Current Status
                            </p>

                            <span
                                class="inline-flex rounded-full border px-3 py-1.5 text-sm font-medium <?= statusClass($task['status']) ?>"
                            >
                                <?= htmlspecialchars(
                                    statusLabel($task['status'])
                                ) ?>
                            </span>

                        </div>


                        <!-- Actions -->

                        <div
                            class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"
                        >

                            <button
                                type="button"
                                onclick="window.location.href='view_task.php?id=<?= (int) $task_id ?>'"
                                class="btn btn-secondary"
                            >
                                Cancel
                            </button>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Update Status
                            </button>

                        </div>


                    </form>

                </div>


                <!-- Information -->

                <div
                    class="mt-5 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900"
                >

                    <div class="flex gap-3">

                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                            />

                        </svg>


                        <p
                            class="text-sm leading-6 text-slate-500 dark:text-slate-400"
                        >
                            Changing the status will automatically add an
                            activity entry to the task history.
                        </p>

                    </div>

                </div>


            </div>

        </main>

    </div>

</div>


<script src="../../dist/js/app.js"></script>

</body>

</html>