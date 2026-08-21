<?php

include('../admin_check.php');
include('../conn.php');

$msg = '';
$error = '';
/* FETCH USERS */
$userStmt = $conn->prepare("
    SELECT id,name
    FROM users
    WHERE role='user'
    ORDER BY name ASC
");

$userStmt->execute();

$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

/* FETCH TITLES */
$titleStmt = $conn->prepare("
    SELECT *
    FROM document_titles
    WHERE status='active'
    ORDER BY title ASC
");

$titleStmt->execute();

$titles = $titleStmt->fetchAll(PDO::FETCH_ASSOC);

/* SAVE DOCUMENT */
if (isset($_POST['save'])) {

    try {

        $user_id = $_POST['user_id'];
        $title_id = $_POST['title_id'];
        $remarks = trim($_POST['remarks']);

        /* INSERT DOCUMENT */
        $stmt = $conn->prepare("
            INSERT INTO documents(
                user_id,
                title_id,
                remarks
            )
            VALUES(?,?,?)
        ");

        $stmt->execute([
            $user_id,
            $title_id,
            $remarks
        ]);

        $document_id = $conn->lastInsertId();

        /* FILES */
        if (!empty($_FILES['files']['name'][0])) {

            foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {

                if ($_FILES['files']['error'][$key] == 0) {

                    $originalName = $_FILES['files']['name'][$key];

                    $extension = pathinfo(
                        $originalName,
                        PATHINFO_EXTENSION
                    );

                    $fileName = time() . '_' . $key . '.' . $extension;

                    $uploadPath =
                        "../uploads/documents/" . $originalName;

                    /* MOVE FILE */
                    if (move_uploaded_file($tmp_name, $uploadPath)) {

                        $fileStmt = $conn->prepare("
                            INSERT INTO document_files(
                                document_id,
                                file_name
                            )
                            VALUES(?,?)
                        ");

                        $fileStmt->execute([
                            $document_id,
                            $originalName
                        ]);
                    } else {

                        $error = "Failed to upload file";
                    }
                } else {

                    $error = "File upload error";
                }
            }
        }

        if ($error == '') {

            $msg = "Document Uploaded Successfully";
        }
    } catch (PDOException $e) {

        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Documents — Admin</title>

    <link rel="preconnect"
        href="https://fonts.googleapis.com" />

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet"
        href="../../dist/css/output.css" />

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

                                Documents

                            </h2>

                        </div>

                    </div>

                    <!-- ERROR MESSAGE -->
                    <?php if ($msg != '') { ?>

                        <div class="mb-5 rounded-xl bg-green-100 border border-green-300 text-green-700 px-4 py-3">

                            <?= $msg ?>

                        </div>

                    <?php } ?>

                    <?php if ($error != '') { ?>

                        <div class="mb-5 rounded-xl bg-red-100 border border-red-300 text-red-700 px-4 py-3">

                            <?= $error ?>

                        </div>

                    <?php } ?>

                    <!-- FORM -->

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                            <div class="space-y-6 p-6">

                                <!-- TITLE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Select User

                                    </label>
                                    <select
                                        name="user_id"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950">

                                        <option value="">
                                            Choose User
                                        </option>

                                        <?php foreach ($users as $user) { ?>

                                            <option value="<?= $user['id'] ?>">

                                                <?= htmlspecialchars($user['name']) ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <!-- DESCRIPTION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Select Title

                                    </label>

                                    <select
                                        name="title_id"
                                        required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950">

                                        <option value="">
                                            Choose Title
                                        </option>

                                        <?php foreach ($titles as $title) { ?>

                                            <option value="<?= $title['id'] ?>">

                                                <?= htmlspecialchars($title['title']) ?>

                                            </option>

                                        <?php } ?>

                                    </select>
                                </div>

                                <!-- IMAGE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Remarks

                                    </label>
                                    <textarea
                                        name="remarks"
                                        rows="4"
                                        class="w-full border rounded-xl px-4 py-3"></textarea>


                                </div>
                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Upload Files


                                    </label>
                                    <input
                                        type="file"
                                        name="files[]"
                                        multiple
                                        required
                                        class="w-full border rounded-xl px-4 py-3">

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
                                    name="save"
                                    class="btn btn-primary">

                                    Upload Document

                                </button>

                            </div>
                           </div>

                        </div>

                </div>

                </form>

        </div>

        </main>

    </div>

    </div>

    <script src="../dist/js/app.js"></script>

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