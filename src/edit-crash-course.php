<?php
session_start();
include('conn.php');

if (!isset($_SESSION['admin'])) {
    header('location:login');
    exit;
}

if (isset($_POST['update_crash_course'])) {

    $id          = $_POST['id'];
    $title       = trim($_POST['title']);
    $start_date  = $_POST['start_date'];
    $duration    = trim($_POST['duration']);
    $description = trim($_POST['description']);
    $status      = trim($_POST['status']);

    try {

        $conn->beginTransaction();

        /*
        |--------------------------------------------------------------------------
        | UPDATE CRASH COURSE
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare("
            UPDATE crash_courses
            SET
                title = :title,
                start_date = :start_date,
                duration = :duration,
                description = :description,
                status = :status,
                updated_at = CURDATE()
            WHERE id = :id
        ");

        $stmt->execute([
            ':title' => $title,
            ':start_date' => $start_date,
            ':duration' => $duration,
            ':description' => $description,
            ':status' => $status,
            ':id' => $id
        ]);


        /*
        |--------------------------------------------------------------------------
        | DELETE OLD HIGHLIGHTS
        |--------------------------------------------------------------------------
        */

        $deleteHighlight = $conn->prepare("
            DELETE FROM crash_course_highlights
            WHERE crash_course_id = :id
        ");

        $deleteHighlight->execute([':id' => $id]);


        /*
        |--------------------------------------------------------------------------
        | INSERT NEW HIGHLIGHTS
        |--------------------------------------------------------------------------
        */

        if (!empty($_POST['highlights'])) {

            $highlightStmt = $conn->prepare("
                INSERT INTO crash_course_highlights
                (crash_course_id, highlight)
                VALUES
                (:crash_course_id, :highlight)
            ");

            foreach ($_POST['highlights'] as $highlight) {

                $highlight = trim($highlight);

                if ($highlight != '') {

                    $highlightStmt->execute([
                        ':crash_course_id' => $id,
                        ':highlight' => $highlight
                    ]);
                }
            }
        }

        $conn->commit();

        header('location:crash-course');
        exit;
    } catch (Exception $e) {

        $conn->rollBack();
        echo $e->getMessage();
    }
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("
    SELECT *
    FROM crash_courses
    WHERE id = :id
");

$stmt->execute([':id' => $id]);

$course = $stmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| FETCH HIGHLIGHTS
|--------------------------------------------------------------------------
*/

$highlightStmt = $conn->prepare("
    SELECT *
    FROM crash_course_highlights
    WHERE crash_course_id = :id
");

$highlightStmt->execute([':id' => $id]);

$highlights = $highlightStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Edit Social Link — Admin</title>

    <link rel="preconnect"
        href="https://fonts.googleapis.com" />

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet"
        href="../dist/css/output.css" />

</head>

<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">

    <div id="mobile-backdrop"
        class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden">
    </div>

    <div class="flex min-h-full">

        <?php include('sidebar.php'); ?>

        <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">

            <?php include('header.php'); ?>

            <main class="flex-1 overflow-auto p-4 lg:p-8">

                <div class="container max-w-4xl">

                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">

                        <div class="min-w-0 flex-1">

                            <p class="text-sm font-medium text-brand-600 dark:text-brand-400">
                                Crash Course
                            </p>

                            <h2 class="text-display-sm text-slate-900 dark:text-white">
                                Edit Crash Course
                            </h2>

                        </div>

                    </div>

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                            <div class="space-y-6 p-6">

                                <!-- TITLE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Title

                                    </label>

                                    <input
                                        type="text"
                                        name="title"
                                        required
                                        value="<?= htmlspecialchars($course['title']) ?>"
                                        placeholder="Enter Title"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- date -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Starting Date
                                    </label>

                                    <input
                                        type="Date"
                                        name="start_date"
                                        required
                                        value="<?= $course['start_date'] ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- duration -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Duration
                                    </label>

                                    <input
                                        type="text"
                                        name="duration"
                                        required
                                        value="<?= $course['duration'] ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- DESCRIPTION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Description

                                    </label>

                                    <textarea
                                        name="description"
                                        rows="5"
                                        required
                                        placeholder="Enter Description"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"><?= $course['description'] ?></textarea>

                                </div>

                                <!-- status -->

                                <div>
                                    <label class="mb-2 block text-sm font-medium">
                                        Status
                                    </label>

                                    <select name="status"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950">
                                        <option value="1" <?= $course['status'] == 1 ? 'selected' : '' ?>>Enable</option>

                                        <option value="0" <?= $course['status'] == 0 ? 'selected' : '' ?>>Disable</option>

                                    </select>
                                </div>

                                <!-- highlites -->

                                <div>

                                    <div class="mb-3 flex items-center justify-between">

                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">

                                            Course Highlights

                                        </label>

                                        <button
                                            type="button"
                                            onclick="addImageField()"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white shadow-sm transition hover:bg-brand-700">

                                            <svg class="h-5 w-5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4v16m8-8H4" />

                                            </svg>

                                        </button>

                                    </div>

                                    <div id="image-fields" class="space-y-3">

                                        <?php foreach ($highlights as $h): ?>

                                            <div class="flex items-center gap-3">

                                                <input
                                                    type="text"
                                                    name="highlights[]"
                                                    value="<?= htmlspecialchars($h['highlight']) ?>"
                                                    class="block w-full rounded-xl border px-4 py-3 text-sm">

                                                <button type="button"
                                                    onclick="removeImageField(this)"
                                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500 text-white">

                                                    ×
                                                </button>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                </div>

                                <!-- BUTTONS -->

                                <div class="flex justify-end gap-3 pt-4">
                                    <input type="hidden" name="id" value="<?= $course['id'] ?>">

                                    <button
                                        type="reset"
                                        class="btn btn-secondary">

                                        Reset

                                    </button>

                                    <button
                                        type="submit"
                                        name="update_crash_course"
                                        class="btn btn-primary">

                                        Update Crash course

                                    </button>

                                </div>

                            </div>

                    </form>

                </div>

            </main>

        </div>

    </div>

    <script src="../dist/js/app.js"></script>

    <script>
        function addImageField() {

            const container =
                document.getElementById('image-fields');

            const wrapper =
                document.createElement('div');

            wrapper.className =
                'flex items-center gap-3';

            wrapper.innerHTML = `
        
            <input
                type="text"
                name="highlights[]"
                placeholder="Enter Highlightes..."
                class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

            <button
                type="button"
                onclick="removeImageField(this)"
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500 text-white">

                ×

            </button>
        `;

            container.appendChild(wrapper);
        }

        function removeImageField(button) {

            button.parentElement.remove();
        }
    </script>

</body>

</html>