<?php

session_start();

if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| UPDATE TOPPER
|--------------------------------------------------------------------------
*/

if(isset($_POST['update_topper'])){

    $student_name = trim($_POST['student_name']);

    $course_name = trim($_POST['course_name']);

    $topper_date = $_POST['topper_date'];

    $description = trim($_POST['description']);

    /*
    |--------------------------------------------------------------------------
    | GET OLD IMAGE
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        SELECT image
        FROM monthly_toppers
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $topperData = $stmt->fetch(PDO::FETCH_ASSOC);

    $image = $topperData['image'];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD NEW IMAGE
    |--------------------------------------------------------------------------
    */

    if($_FILES['image']['error'] == 0){

        if(
            $image &&
            file_exists('uploads/monthly-toppers/'.$image)
        ){

            unlink('uploads/monthly-toppers/'.$image);
        }

        $image =
        time().'_'.$_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            'uploads/monthly-toppers/'.$image
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TOPPER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        UPDATE monthly_toppers
        SET
        student_name = :student_name,
        course_name = :course_name,
        topper_date = :topper_date,
        description = :description,
        image = :image,
        updated_at = CURDATE()
        WHERE id = :id
    ");

    $stmt->execute([
        ':student_name' => $student_name,
        ':course_name' => $course_name,
        ':topper_date' => $topper_date,
        ':description' => $description,
        ':image' => $image,
        ':id' => $id
    ]);

    header('location:monthly-topper.php');

    exit;
}

/*
|--------------------------------------------------------------------------
| GET TOPPER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM monthly_toppers
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$topper = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Edit Topper — Admin</title>

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

                            Edit Monthly Topper

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
                                        value="<?php echo $topper['student_name']; ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

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
                                        value="<?php echo $topper['course_name']; ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

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
                                        value="<?php echo $topper['topper_date']; ?>"
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
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"><?php echo $topper['description']; ?></textarea>

                                </div>

                                <!-- EXISTING IMAGE -->

                                <div>

                                    <label class="mb-3 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Existing Image

                                    </label>

                                    <?php if($topper['image']){ ?>

                                        <img
                                            src="uploads/monthly-toppers/<?php echo $topper['image']; ?>"
                                            class="h-40 rounded-xl border border-slate-200 object-cover dark:border-slate-700" />

                                    <?php } ?>

                                </div>

                                <!-- NEW IMAGE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Change Image

                                    </label>

                                    <input
                                        type="file"
                                        name="image"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                </div>

                                <!-- BUTTON -->

                                <div class="flex justify-end pt-4">

                                    <button
                                        type="submit"
                                        name="update_topper"
                                        class="btn btn-primary">

                                        Update Topper

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