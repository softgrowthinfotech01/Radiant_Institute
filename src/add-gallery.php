<?php
session_start();
if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

if(isset($_POST['save_gallery'])){

    $title = trim($_POST['title']);

    $description = trim($_POST['description']);

    $stmt = $conn->prepare("
        INSERT INTO galleries(
            title,
            description,
            created_at,
            updated_at
        )
        VALUES(
            :title,
            :description,
            CURDATE(),
            CURDATE()
        )
    ");

    $stmt->execute([
        ':title' => $title,
        ':description' => $description
    ]);

    $gallery_id = $conn->lastInsertId();

    if(isset($_FILES['images'])){

        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name){

            if($_FILES['images']['error'][$key] == 0){

                $image_name =
                time().'_'.$_FILES['images']['name'][$key];

                move_uploaded_file(
                    $tmp_name,
                    'uploads/gallery/'.$image_name
                );

                $stmt = $conn->prepare("
                    INSERT INTO gallery_images(
                        gallery_id,
                        image
                    )
                    VALUES(
                        :gallery_id,
                        :image
                    )
                ");

                $stmt->execute([
                    ':gallery_id' => $gallery_id,
                    ':image' => $image_name
                ]);
            }
        }
    }

    header('location:gallery');

    exit;
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Add Gallery — Admin</title>

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

                            <h2 class="text-display-sm text-slate-900 dark:text-white">
                                Add Gallery
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

                                        Gallery Title

                                    </label>

                                    <input
                                        type="text"
                                        name="title"
                                        required
                                        placeholder="Enter Gallery Title"
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
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"></textarea>

                                </div>

                                <!-- IMAGES -->

                                <div>

                                    <div class="mb-3 flex items-center justify-between">

                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">

                                            Images

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

                                    <div id="image-fields"
                                        class="space-y-3">

                                        <input
                                            type="file"
                                            name="images[]"
                                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                    </div>

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
                                        name="save_gallery"
                                        class="btn btn-primary">

                                        Save Gallery

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

    function addImageField(){

        const container =
        document.getElementById('image-fields');

        const wrapper =
        document.createElement('div');

        wrapper.className =
        'flex items-center gap-3';

        wrapper.innerHTML = `
        
            <input
                type="file"
                name="images[]"
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

    function removeImageField(button){

        button.parentElement.remove();
    }

    </script>

</body>

</html>