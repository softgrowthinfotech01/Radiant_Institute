<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');
include('faculty-helpers.php');

$id = (int)($_GET['id'] ?? 0);

/*
|--------------------------------------------------------------------------
| GET FACULTY
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM faculty
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {

    header('location:faculty.php');

    exit;
}

$error = '';

/*
|--------------------------------------------------------------------------
| UPDATE FACULTY
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_faculty'])) {

    $oldImage = $member['image'];

    // keep what the user typed so the form can be re-shown on error
    foreach (['name', 'designation', 'subject', 'qualification', 'experience', 'description'] as $field) {

        $member[$field] = trim($_POST[$field] ?? '');
    }

    $member['status'] = (($_POST['status'] ?? '1') === '0') ? 0 : 1;

    if ($member['name'] === '') {

        $error = 'Name is required.';
    }

    /*
    |--------------------------------------------------------------------------
    | PHOTO: REPLACE OR REMOVE
    |--------------------------------------------------------------------------
    */

    $newImage = null;

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $newImage = faculty_save_photo($_FILES['image']);

        if ($newImage === null) {

            $error = 'Photo could not be uploaded. Please use a JPG, PNG, WEBP or GIF image.';
        }
    }

    if ($error === '') {

        $image = $oldImage;

        if ($newImage !== null) {

            $image = $newImage;
        } elseif (isset($_POST['remove_image'])) {

            $image = '';
        }

        $stmt = $conn->prepare("
            UPDATE faculty
            SET
            name = :name,
            designation = :designation,
            subject = :subject,
            qualification = :qualification,
            experience = :experience,
            description = :description,
            image = :image,
            status = :status,
            updated_at = CURDATE()
            WHERE id = :id
        ");

        $stmt->execute([
            ':name'          => $member['name'],
            ':designation'   => $member['designation'],
            ':subject'       => $member['subject'],
            ':qualification' => $member['qualification'],
            ':experience'    => $member['experience'],
            ':description'   => $member['description'],
            ':image'         => $image,
            ':status'        => $member['status'],
            ':id'            => $id,
        ]);

        // old photo is only removed once the database row no longer points at it
        if ($image !== $oldImage) {

            faculty_delete_photo($oldImage);
        }

        header('location:faculty.php');

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

    <title>Edit Faculty — Admin</title>

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

                            Edit Faculty

                        </h2>

                    </div>

                    <form method="POST"
                        enctype="multipart/form-data">

                        <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                            <div class="space-y-6 p-6">

                                <?php if ($error !== '') { ?>

                                    <div class="rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700">

                                        <?php echo esc($error); ?>

                                    </div>

                                <?php } ?>

                                <!-- NAME -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Name

                                    </label>

                                    <input
                                        type="text"
                                        name="name"
                                        required
                                        maxlength="255"
                                        value="<?php echo esc($member['name']); ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- DESIGNATION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Designation

                                    </label>

                                    <input
                                        type="text"
                                        name="designation"
                                        maxlength="255"
                                        value="<?php echo esc($member['designation']); ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- SUBJECT -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Subject

                                    </label>

                                    <input
                                        type="text"
                                        name="subject"
                                        maxlength="255"
                                        value="<?php echo esc($member['subject']); ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- QUALIFICATION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Qualification

                                    </label>

                                    <input
                                        type="text"
                                        name="qualification"
                                        maxlength="255"
                                        value="<?php echo esc($member['qualification']); ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- EXPERIENCE -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Experience

                                    </label>

                                    <input
                                        type="text"
                                        name="experience"
                                        maxlength="100"
                                        value="<?php echo esc($member['experience']); ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- DESCRIPTION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        About

                                    </label>

                                    <textarea
                                        name="description"
                                        rows="5"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"><?php echo esc($member['description']); ?></textarea>

                                </div>

                                <!-- STATUS -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Status

                                    </label>

                                    <select
                                        name="status"
                                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950">

                                        <option value="1" <?php echo $member['status'] == 1 ? 'selected' : ''; ?>>Enabled</option>

                                        <option value="0" <?php echo $member['status'] == 0 ? 'selected' : ''; ?>>Disabled</option>

                                    </select>

                                </div>

                                <!-- EXISTING PHOTO -->

                                <?php if ($member['image']) { ?>

                                    <div>

                                        <label class="mb-3 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                            Current Photo

                                        </label>

                                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

                                            <div class="space-y-2">

                                                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">

                                                    <img
                                                        src="uploads/faculty/<?php echo esc($member['image']); ?>"
                                                        class="h-40 w-full object-cover" />

                                                </div>

                                                <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">

                                                    <input
                                                        type="checkbox"
                                                        name="remove_image"
                                                        value="1" />

                                                    Remove photo

                                                </label>

                                            </div>

                                        </div>

                                    </div>

                                <?php } ?>

                                <!-- NEW PHOTO -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Change Photo

                                    </label>

                                    <input
                                        type="file"
                                        name="image"
                                        accept="image/*"
                                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-700 dark:bg-slate-950" />

                                </div>

                                <!-- BUTTONS -->

                                <div class="flex justify-end gap-3 pt-4">

                                    <a
                                        href="faculty.php"
                                        class="btn btn-secondary">

                                        Cancel

                                    </a>

                                    <button
                                        type="submit"
                                        name="update_faculty"
                                        class="btn btn-primary">

                                        Update Faculty

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
