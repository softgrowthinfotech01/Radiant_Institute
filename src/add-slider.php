<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');

$error = '';

if (isset($_POST['save_slider'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_FILES)) {

        $error = 'Image size is too large. Maximum allowed size is 5 MB';
    }

    $title = trim($_POST['title']);

    $description = trim($_POST['description']);

    $imageName = '';

    /*
|--------------------------------------------------------------------------
| IMAGE UPLOAD
|--------------------------------------------------------------------------
*/

    if (isset($_FILES['image'])) {

        /*
    |--------------------------------------------------------------------------
    | FILE SIZE SERVER ERROR
    |--------------------------------------------------------------------------
    */

        if ($_FILES['image']['error'] == 1) {

            $error = 'Image size exceeds server upload limit';
        } elseif ($_FILES['image']['error'] == 2) {

            $error = 'Image size exceeds allowed form limit';
        } elseif ($_FILES['image']['error'] != 0) {

            $error = 'Failed to upload image';
        } else {

            /*
        |--------------------------------------------------------------------------
        | VALIDATE IMAGE SIZE
        |--------------------------------------------------------------------------
        */

            $maxSize = 5 * 1024 * 1024;

            if ($_FILES['image']['size'] > $maxSize) {

                $error = 'Image size must be less than 5 MB';
            } else {

                /*
            |--------------------------------------------------------------------------
            | VALIDATE IMAGE TYPE
            |--------------------------------------------------------------------------
            */

                $allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/jpg'
                ];

                if (
                    !in_array(
                        $_FILES['image']['type'],
                        $allowedTypes
                    )
                ) {

                    $error =
                        'Only JPG, PNG and WEBP images are allowed';
                } else {

                    /*
                |--------------------------------------------------------------------------
                | UPLOAD IMAGE
                |--------------------------------------------------------------------------
                */

                    $imageName =
                        time() . '_' .
                        $_FILES['image']['name'];

                    if (
                        !move_uploaded_file(
                            $_FILES['image']['tmp_name'],
                            'uploads/sliders/' . $imageName
                        )
                    ) {

                        $error = 'Failed to save image';
                    }
                }
            }
        }
    } else {

        $error = 'Please select an image';
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT SLIDER
    |--------------------------------------------------------------------------
    */

    if (empty($error)) {

        $stmt = $conn->prepare("
            INSERT INTO sliders(
                title,
                description,
                image,
                created_at,
                updated_at
            )
            VALUES(
                :title,
                :description,
                :image,
                CURDATE(),
                CURDATE()
            )
        ");

        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':image' => $imageName
        ]);

        header('location:slider.php');

        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Add Slider — Admin</title>

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

                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">

                        <div class="min-w-0 flex-1">

                            <h2 class="text-display-sm text-slate-900 dark:text-white">

                                Add Slider

                            </h2>

                        </div>

                    </div>

                    <!-- ERROR MESSAGE -->

                    <?php if (!empty($error)) { ?>

                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-300">

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>

                    <!-- FORM -->

                    <form method="POST"
                        enctype="multipart/form-data" id="sliderForm">

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
                                        placeholder="Enter slider title"
                                        value="<?php echo $_POST['title'] ?? ''; ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- DESCRIPTION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Description

                                    </label>

                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="5"
                                        placeholder="Enter slider description"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"><?php echo $_POST['description'] ?? ''; ?></textarea>

                                </div>

                                <!-- IMAGE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Slider Image

                                    </label>

                                    <input
                                        id="image"
                                        type="file"
                                        name="image"
                                        accept="image/*"
                                        required
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">

                                        Maximum file size: 5 MB

                                    </p>

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
                                        name="save_slider"
                                        class="btn btn-primary">

                                        Save Slider

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

    <script>
        document
            .getElementById('sliderForm')
            .addEventListener('submit', function(e) {

                const imageInput =
                    document.getElementById('image');

                if (imageInput.files.length > 0) {

                    const file = imageInput.files[0];

                    const maxSize =
                        5 * 1024 * 1024;

                    if (file.size > maxSize) {

                        e.preventDefault();

                        alert(
                            'Image size must be less than 5 MB'
                        );

                        return false;
                    }
                }

            });
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>



</body>

</html>