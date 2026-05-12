<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');

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

if (isset($_POST['save_details'])) {

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

    $highlight_image = '';

    if (
        isset($_FILES['highlight_image']) &&
        $_FILES['highlight_image']['error'] == 0
    ) {

        $highlight_image =
            time() . '_' . $_FILES['highlight_image']['name'];

        move_uploaded_file(
            $_FILES['highlight_image']['tmp_name'],
            'uploads/course-details/' . $highlight_image
        );
    }

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
    | INSERT DETAILS
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        INSERT INTO course_details(

            course_id,

            why_title_1,
            why_description_1,

            why_title_2,
            why_description_2,

            why_title_3,
            why_description_3,

            program_highlight,
            highlight_image,

            module_title_1,
            module_description_1,

            module_title_2,
            module_description_2,

            module_title_3,
            module_description_3,

            created_at,
            updated_at

        )
        VALUES(

            :course_id,

            :why_title_1,
            :why_description_1,

            :why_title_2,
            :why_description_2,

            :why_title_3,
            :why_description_3,

            :program_highlight,
            :highlight_image,

            :module_title_1,
            :module_description_1,

            :module_title_2,
            :module_description_2,

            :module_title_3,
            :module_description_3,

            CURDATE(),
            CURDATE()

        )
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
        ':module_description_3' => $module_description_3

    ]);

    header('location:course-details.php');

    exit;
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Add Details — Admin</title>

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

                            Add Course Details

                        </h2>

                    </div>

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="space-y-8">

                            <!-- COURSE -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div class="mb-4">

                                    <h3 class="text-lg font-semibold">

                                        Select Course

                                    </h3>

                                </div>

                                <select
                                    name="course_id"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950">

                                    <option value="">
                                        Select Course
                                    </option>

                                    <?php foreach($courses as $course){ ?>

                                        <option value="<?php echo $course['id']; ?>">

                                            <?php echo $course['title']; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <!-- WHY CHOOSE -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div class="mb-6">

                                    <h3 class="text-lg font-semibold">

                                        Why Choose Our Program

                                    </h3>

                                </div>

                                <div class="grid gap-5 lg:grid-cols-3">

                                    <?php for ($i = 1; $i <= 3; $i++) { ?>

                                        <div class="space-y-3 rounded-xl bg-slate-50 p-4 dark:bg-slate-950">

                                            <input
                                                type="text"
                                                name="why_title_<?php echo $i; ?>"
                                                placeholder="Title"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900" />

                                            <textarea
                                                name="why_description_<?php echo $i; ?>"
                                                rows="5"
                                                placeholder="Description"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900"></textarea>

                                        </div>

                                    <?php } ?>

                                </div>

                            </div>

                            <!-- PROGRAM HIGHLIGHTS -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div class="mb-6">

                                    <h3 class="text-lg font-semibold">

                                        Program Highlights

                                    </h3>

                                </div>

                                <div class="grid gap-4 lg:grid-cols-2">

                                    <textarea
                                        name="program_highlight"
                                        rows="5"
                                        placeholder="Program Highlight"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950"></textarea>

                                    <input
                                        type="file"
                                        name="highlight_image"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                </div>

                            </div>

                            <!-- COURSE MODULES -->

                            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                                <div class="mb-6">

                                    <h3 class="text-lg font-semibold">

                                        Course Modules

                                    </h3>

                                </div>

                                <div class="grid gap-5 lg:grid-cols-3">

                                    <?php for ($i = 1; $i <= 3; $i++) { ?>

                                        <div class="space-y-3 rounded-xl bg-slate-50 p-4 dark:bg-slate-950">

                                            <input
                                                type="text"
                                                name="module_title_<?php echo $i; ?>"
                                                placeholder="Module Title"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900" />

                                            <textarea
                                                name="module_description_<?php echo $i; ?>"
                                                rows="5"
                                                placeholder="Module Description"
                                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-900"></textarea>

                                        </div>

                                    <?php } ?>

                                </div>

                            </div>

                            <!-- BUTTON -->

                            <div class="flex justify-end">

                                <button
                                    type="submit"
                                    name="save_details"
                                    class="btn btn-primary">

                                    Save Details

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