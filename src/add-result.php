<?php
session_start();
if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('conn.php');

if(isset($_POST['save_result'])){

    $course_id = $_POST['course_id'];

    $year = $_POST['year'];

    $stmt = $conn->prepare("
        INSERT INTO results(
            course_id,
            year,
            created_at,
            updated_at
        )
        VALUES(
            :course_id,
            :year,
            CURDATE(),
            CURDATE()
        )
    ");

    $stmt->execute([
        ':course_id' => $course_id,
        ':year' => $year
    ]);

    $result_id = $conn->lastInsertId();

    if(isset($_FILES['images'])){

        foreach($_FILES['images']['tmp_name'] as $key => $tmp_name){

            if($_FILES['images']['error'][$key] == 0){

                $image_name =
                time().'_'.$_FILES['images']['name'][$key];

                move_uploaded_file(
                    $tmp_name,
                    'uploads/results/'.$image_name
                );

                $stmt = $conn->prepare("
                    INSERT INTO result_images(
                        result_id,
                        image
                    )
                    VALUES(
                        :result_id,
                        :image
                    )
                ");

                $stmt->execute([
                    ':result_id' => $result_id,
                    ':image' => $image_name
                ]);
            }
        }
    }

    header('location:results.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| GET COURSES
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id, title
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

    <title>Add Result — Admin</title>

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
                                Add Result
                            </h2>

                        </div>

                    </div>

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                            <div class="space-y-6 p-6">

                                <!-- COURSE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Select Course

                                    </label>

                                    <select
                                        name="course_id"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950">

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

                                <!-- YEAR -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Year

                                    </label>

                                    <input
                                        type="number"
                                        name="year"
                                        required
                                        placeholder="Enter Year"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

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
                                            required
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
                                        name="save_result"
                                        class="btn btn-primary">

                                        Save Result

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