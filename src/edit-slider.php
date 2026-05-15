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
| UPDATE SLIDER
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_slider'])) {

    $title = trim($_POST['title']);

    $description = trim($_POST['description']);

    /*
    |--------------------------------------------------------------------------
    | GET OLD IMAGE
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        SELECT image
        FROM sliders
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id
    ]);

    $oldSlider = $stmt->fetch(PDO::FETCH_ASSOC);

    $imageName = $oldSlider['image'];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD NEW IMAGE
    |--------------------------------------------------------------------------
    */

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] == 0
    ) {

        /*
        |--------------------------------------------------------------------------
        | DELETE OLD IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            !empty($oldSlider['image']) &&
            file_exists(
                'uploads/sliders/' .
                $oldSlider['image']
            )
        ) {

            unlink(
                'uploads/sliders/' .
                $oldSlider['image']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE NEW IMAGE
        |--------------------------------------------------------------------------
        */

        $imageName =
            time() . '_' . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            'uploads/sliders/' . $imageName
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE SLIDER
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        UPDATE sliders
        SET
        title = :title,
        description = :description,
        image = :image,
        updated_at = CURDATE()
        WHERE id = :id
    ");

    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':image' => $imageName,
        ':id' => $id
    ]);

    header('location:sliders.php');

    exit;
}

/*
|--------------------------------------------------------------------------
| GET SLIDER
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM sliders
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$slider = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Edit Slider — Admin</title>

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

                    <!-- PAGE HEADER -->

                    <div class="mb-6">

                        <p class="text-sm font-medium text-brand-600 dark:text-brand-400">

                            Homepage

                        </p>

                        <h2 class="text-display-sm text-slate-900 dark:text-white">

                            Edit Slider

                        </h2>

                    </div>

                    <!-- FORM -->

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                            <div class="space-y-6 p-6">

                                <!-- TITLE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Slider Title

                                    </label>

                                    <input
                                        type="text"
                                        name="title"
                                        required
                                        value="<?php echo $slider['title']; ?>"
                                        placeholder="Enter slider title"
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
                                        placeholder="Enter slider description"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"><?php echo $slider['description']; ?></textarea>

                                </div>

                                <!-- EXISTING IMAGE -->

                                <div>

                                    <label class="mb-3 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Current Image

                                    </label>

                                    <div class="max-w-md overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">

                                        <img
                                            src="uploads/sliders/<?php echo $slider['image']; ?>"
                                            class="h-60 w-full object-cover" />

                                    </div>

                                </div>

                                <!-- NEW IMAGE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Replace Image

                                    </label>

                                    <input
                                        type="file"
                                        name="image"
                                        accept="image/*"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                </div>

                                <!-- BUTTON -->

                                <div class="flex justify-end gap-3 pt-4">

                                    <button
                                        type="submit"
                                        name="update_slider"
                                        class="btn btn-primary">

                                        Update Slider

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