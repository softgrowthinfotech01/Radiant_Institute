<?php

session_start();

if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

if(isset($_POST['save_topper'])){

    $student_name = trim($_POST['student_name']);

    $course_name = trim($_POST['course_name']);

    $topper_date = $_POST['topper_date'];

    $description = trim($_POST['description']);

    $image = '';

    /*
    |--------------------------------------------------------------------------
    | IMAGE UPLOAD
    |--------------------------------------------------------------------------
    */

    if($_FILES['image']['error'] == 0){

        $image =
        time().'_'.$_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            'uploads/monthly-toppers/'.$image
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT TOPPER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        INSERT INTO monthly_toppers(
            student_name,
            course_name,
            topper_date,
            description,
            image,
            created_at,
            updated_at
        )
        VALUES(
            :student_name,
            :course_name,
            :topper_date,
            :description,
            :image,
            CURDATE(),
            CURDATE()
        )
    ");

    $stmt->execute([
        ':student_name' => $student_name,
        ':course_name' => $course_name,
        ':topper_date' => $topper_date,
        ':description' => $description,
        ':image' => $image
    ]);

    header('location:monthly-topper.php');

    exit;
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Add Topper — Admin</title>

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

                    <div class="mb-6">

                        <h2 class="text-display-sm text-slate-900 dark:text-white">

                            Add Monthly Topper

                        </h2>

                    </div>

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                            <div class="space-y-6 p-6">

                                <!-- STUDENT NAME -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Student Name

                                    </label>

                                    <input
                                        type="text"
                                        name="student_name"
                                        required
                                        placeholder="Enter Student Name"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- COURSE NAME -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Course Name

                                    </label>

                                    <input
                                        type="text"
                                        name="course_name"
                                        required
                                        placeholder="Enter Course Name"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- TOPPER DATE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Topper Date

                                    </label>

                                    <input
                                        type="date"
                                        name="topper_date"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- DESCRIPTION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Description

                                    </label>

                                    <textarea
                                        name="description"
                                        rows="5"
                                        placeholder="Enter Description"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"></textarea>

                                </div>

                                <!-- IMAGE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Image

                                    </label>

                                    <input
                                        type="file"
                                        name="image"
                                        required
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                </div>

                                <!-- BUTTONS -->

                                <div class="flex justify-end gap-3 pt-4">

                                    <button
                                        type="reset"
                                        class="btn btn-secondary">

                                        Reset

                                    </button>

                                    <button
                                        type="submit"
                                        name="save_topper"
                                        class="btn btn-primary">

                                        Save Topper

                                    </button>

                                </div>

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