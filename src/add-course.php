<?php
session_start();
if (!isset($_SESSION['admin'])) {

  header('location:login');

  exit;
}

include('../conn.php');

if (isset($_POST['add_course'])) {

  $title = trim($_POST['title']);

  $description = trim($_POST['description']);

  $status = $_POST['status'];

  $stmt = $conn->prepare("
    INSERT INTO courses(
        title,
        description,
        status,
        created_at,
        updated_at
    )
    VALUES(
        :title,
        :description,
        :status,
        CURDATE(),
        CURDATE()
    )
");

  $stmt->execute([
    ':title' => $title,
    ':description' => $description,
    ':status' => $status
  ]);

  $course_id = $conn->lastInsertId();

  if (isset($_FILES['images'])) {

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

      if ($_FILES['images']['error'][$key] == 0) {

        $image_name =
          time() . '_' . $_FILES['images']['name'][$key];

        move_uploaded_file(
          $tmp_name,
          'uploads/courses/' . $image_name
        );

        $stmt = $conn->prepare("
                    INSERT INTO course_images(
                        course_id,
                        image
                    )
                    VALUES(
                        :course_id,
                        :image
                    )
                ");

        $stmt->execute([
          ':course_id' => $course_id,
          ':image' => $image_name
        ]);
      }
    }
  }


  header('location:courses.php');
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings — Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../dist/css/output.css" />
</head>

<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
  <div id="mobile-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

  <div class="flex min-h-full">
    <?php
    include('sidebar.php');
    ?>

    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">
      <?php
      include('header.php');
      ?>

      <main class="flex-1 overflow-auto p-4 lg:p-8">
        <div class="container max-w-3xl">
          <div class="mb-8">
            <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Forms</p>
            <h2 class="text-display-sm text-slate-900 dark:text-white">Add Course</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Insert a New Course name</p>
          </div>

          <form method="POST" enctype="multipart/form-data">
            <div class="space-y-6">
              <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold">Course</h3>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Course Name<input name="title" type="text" placeholder="Course Name..." class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>

                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Course Description<input name="description" type="text" placeholder="Course Description..." class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                </div>
              </section>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">

                Status

                <select
                  name="status"
                  class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950">

                  <option value="1">Active</option>
                  <option value="0">Inactive</option>

                </select>

              </label>

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

              <div class="flex flex-wrap justify-end gap-2 pb-8">
                <button type="reset" class="btn btn-secondary">Discard</button>
                <button
                  type="submit"
                  name="add_course"
                  class="btn btn-primary">Save Course
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