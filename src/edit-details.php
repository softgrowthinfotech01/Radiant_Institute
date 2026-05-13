<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| UPDATE DETAILS
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_details'])) {

    $course_id = $_POST['course_id'];

    /*
    |--------------------------------------------------------------------------
    | WHY CHOOSE OUR PROGRAM
    |--------------------------------------------------------------------------
    */

    $why_title_1 = trim($_POST['why_title_1']);
    $why_description_1 = trim($_POST['why_description_1']);

    $why_title_2 = trim($_POST['why_title_2']);
    $why_description_2 = trim($_POST['why_description_2']);

    $why_title_3 = trim($_POST['why_title_3']);
    $why_description_3 = trim($_POST['why_description_3']);

    /*
    |--------------------------------------------------------------------------
    | PROGRAM HIGHLIGHT
    |--------------------------------------------------------------------------
    */

    $program_highlight = trim($_POST['program_highlight']);

    /*
    |--------------------------------------------------------------------------
    | COURSE MODULES
    |--------------------------------------------------------------------------
    */

    $module_title_1 = trim($_POST['module_title_1']);
    $module_description_1 = trim($_POST['module_description_1']);

    $module_title_2 = trim($_POST['module_title_2']);
    $module_description_2 = trim($_POST['module_description_2']);

    $module_title_3 = trim($_POST['module_title_3']);
    $module_description_3 = trim($_POST['module_description_3']);

    /*
    |--------------------------------------------------------------------------
    | GET OLD IMAGE
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        SELECT highlight_image
        FROM course_details
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $detailData = $stmt->fetch(PDO::FETCH_ASSOC);

    $highlight_image = $detailData['highlight_image'];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD NEW IMAGE
    |--------------------------------------------------------------------------
    */

    if (
        isset($_FILES['highlight_image']) &&
        $_FILES['highlight_image']['error'] == 0
    ) {

        if (
            $highlight_image &&
            file_exists(
                'uploads/course-details/' . $highlight_image
            )
        ) {

            unlink(
                'uploads/course-details/' . $highlight_image
            );
        }

        $highlight_image =
            time() . '_' . $_FILES['highlight_image']['name'];

        move_uploaded_file(
            $_FILES['highlight_image']['tmp_name'],
            '../uploads/course-details/' . $highlight_image
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DETAILS
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        UPDATE course_details
        SET

        course_id = :course_id,

        why_title_1 = :why_title_1,
        why_description_1 = :why_description_1,

        why_title_2 = :why_title_2,
        why_description_2 = :why_description_2,

        why_title_3 = :why_title_3,
        why_description_3 = :why_description_3,

        program_highlight = :program_highlight,
        highlight_image = :highlight_image,

        module_title_1 = :module_title_1,
        module_description_1 = :module_description_1,

        module_title_2 = :module_title_2,
        module_description_2 = :module_description_2,

        module_title_3 = :module_title_3,
        module_description_3 = :module_description_3,

        updated_at = CURDATE()

        WHERE id = :id
    ");

    $stmt->execute([

        ':course_id' => $course_id,

        ':why_title_1' => $why_title_1,
        ':why_description_1' => $why_description_1,

        ':why_title_2' => $why_title_2,
        ':why_description_2' => $why_description_2,

        ':why_title_3' => $why_title_3,
        ':why_description_3' => $why_description_3,

        ':program_highlight' => $program_highlight,
        ':highlight_image' => $highlight_image,

        ':module_title_1' => $module_title_1,
        ':module_description_1' => $module_description_1,

        ':module_title_2' => $module_title_2,
        ':module_description_2' => $module_description_2,

        ':module_title_3' => $module_title_3,
        ':module_description_3' => $module_description_3,

        ':id' => $id

    ]);

    header('location:details.php');

    exit;
}

/*
|--------------------------------------------------------------------------
| GET DETAILS
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM course_details
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$detail = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| GET COURSES
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM courses
    ORDER BY title ASC
");

$stmt->execute();

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Edit Details — Admin</title>

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

                <div class="container max-w-5xl">

                    <div class="mb-6">

                        <h2 class="text-display-sm text-slate-900 dark:text-white">

                            Edit Course Details

                        </h2>

                    </div>

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="space-y-8">

                            <!-- COURSE -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <label class="mb-3 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                    Select Course

                                </label>

                                <select
                                    name="course_id"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950">

                                    <option value="">
                                        Select Course
                                    </option>

                                    <?php foreach ($courses as $course) { ?>

                                        <option
                                            value="<?php echo $course['id']; ?>"
                                            <?php echo ($course['id'] == $detail['course_id']) ? 'selected' : ''; ?>>

                                            <?php echo $course['title']; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <!-- WHY CHOOSE -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <h3 class="mb-6 text-lg font-semibold">

                                    Why Choose Our Program

                                </h3>

                                <div class="grid gap-5 lg:grid-cols-3">

                                    <?php for ($i = 1; $i <= 3; $i++) { ?>

                                        <div class="space-y-3 rounded-xl bg-slate-50 p-4 dark:bg-slate-950">

                                            <input
                                                type="text"
                                                name="why_title_<?php echo $i; ?>"
                                                value="<?php echo $detail['why_title_' . $i] ?? ''; ?>"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900" />

                                            <textarea
                                                name="why_description_<?php echo $i; ?>"
                                                rows="5"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900"><?php echo $detail['why_description_' . $i] ?? ''; ?></textarea>

                                        </div>

                                    <?php } ?>

                                </div>

                            </div>

                            <!-- PROGRAM HIGHLIGHT -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <h3 class="mb-6 text-lg font-semibold">

                                    Program Highlights

                                </h3>

                                <div class="space-y-5">

                                    <textarea
                                        name="program_highlight"
                                        rows="5"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950"><?php echo $detail['program_highlight'] ?? ''; ?></textarea>

                                    <?php if (!empty($detail['highlight_image'])) { ?>

                                        <div class="space-y-2">

                                            <div class=" mb-2 w-40">

                                                <p class="mb-2 text-sm font-medium text-slate-600 dark:text-slate-300">

                                                    Current Image

                                                </p>

                                                <img
    src="uploads/course-details/<?php echo $detail['highlight_image']; ?>"
    style="width:120px; height:120px;"
    class="object-cover rounded-lg border border-slate-200 dark:border-slate-700" />

                                            </div>

                                            <a
                                                href="delete-image.php?id=<?php echo $detail['id']; ?>&table=course_details&folder=uploads/course-details&redirect=edit-details.php&foreign_key=id"
                                                onclick="return confirm('Delete this image?')"
                                                class="inline-flex mt-1 items-center justify-center rounded-xl bg-red-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-600">

                                                Delete Image

                                            </a>

                                        </div>

                                    <?php } ?>

                                    <input
                                        type="file"
                                        name="highlight_image"
                                        class="block w-full mt-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                </div>

                            </div>

                            <!-- COURSE MODULES -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <h3 class="mb-6 text-lg font-semibold">

                                    Course Modules

                                </h3>

                                <div class="grid gap-5 lg:grid-cols-3">

                                    <?php for ($i = 1; $i <= 3; $i++) { ?>

                                        <div class="space-y-3 rounded-xl bg-slate-50 p-4 dark:bg-slate-950">

                                            <input
                                                type="text"
                                                name="module_title_<?php echo $i; ?>"
                                                value="<?php echo $detail['module_title_' . $i] ?? ''; ?>"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900" />

                                            <textarea
                                                name="module_description_<?php echo $i; ?>"
                                                rows="5"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900"><?php echo $detail['module_description_' . $i] ?? ''; ?></textarea>

                                        </div>

                                    <?php } ?>

                                </div>

                            </div>

                            <!-- BUTTON -->

                            <div class="flex justify-end">

                                <button
                                    type="submit"
                                    name="update_details"
                                    class="btn btn-primary">

                                    Update Details

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </main>

        </div>

    </div>

    <script src="../dist/js/app.js"></script>

</body>

</html>