<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');
include('faculty-helpers.php');

$error = '';

$old = [
    'name'          => '',
    'designation'   => '',
    'subject'       => '',
    'qualification' => '',
    'experience'    => '',
    'description'   => '',
    'status'        => 1,
];

if (isset($_POST['save_faculty'])) {

    foreach (['name', 'designation', 'subject', 'qualification', 'experience', 'description'] as $field) {

        $old[$field] = trim($_POST[$field] ?? '');
    }

    $old['status'] = (($_POST['status'] ?? '1') === '0') ? 0 : 1;

    if ($old['name'] === '') {

        $error = 'Name is required.';
    }

    /*
    |--------------------------------------------------------------------------
    | PHOTO UPLOAD (optional)
    |--------------------------------------------------------------------------
    */

    $image = '';

    if (
        $error === '' &&
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $image = faculty_save_photo($_FILES['image']);

        if ($image === null) {

            $image = '';

            $error = 'Photo could not be uploaded. Please use a JPG, PNG, WEBP or GIF image.';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT FACULTY
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        $stmt = $conn->prepare("
            INSERT INTO faculty(
                name,
                designation,
                subject,
                qualification,
                experience,
                description,
                image,
                status,
                created_at,
                updated_at
            )
            VALUES(
                :name,
                :designation,
                :subject,
                :qualification,
                :experience,
                :description,
                :image,
                :status,
                CURDATE(),
                CURDATE()
            )
        ");

        $stmt->execute([
            ':name'          => $old['name'],
            ':designation'   => $old['designation'],
            ':subject'       => $old['subject'],
            ':qualification' => $old['qualification'],
            ':experience'    => $old['experience'],
            ':description'   => $old['description'],
            ':image'         => $image,
            ':status'        => $old['status'],
        ]);

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

    <title>Add Faculty — Admin</title>

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

                            Add Faculty

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
                                        value="<?php echo esc($old['name']); ?>"
                                        placeholder="Enter Faculty Name"
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
                                        value="<?php echo esc($old['designation']); ?>"
                                        placeholder="e.g. Senior Lecturer"
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
                                        value="<?php echo esc($old['subject']); ?>"
                                        placeholder="e.g. Physics"
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
                                        value="<?php echo esc($old['qualification']); ?>"
                                        placeholder="e.g. M.Sc., B.Ed."
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
                                        value="<?php echo esc($old['experience']); ?>"
                                        placeholder="e.g. 8 years"
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
                                        placeholder="Short bio"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950"><?php echo esc($old['description']); ?></textarea>

                                </div>

                                <!-- STATUS -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Status

                                    </label>

                                    <select
                                        name="status"
                                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950">

                                        <option value="1" <?php echo $old['status'] == 1 ? 'selected' : ''; ?>>Enabled</option>

                                        <option value="0" <?php echo $old['status'] == 0 ? 'selected' : ''; ?>>Disabled</option>

                                    </select>

                                </div>

                                <!-- PHOTO -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Photo

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
                                        name="save_faculty"
                                        class="btn btn-primary">

                                        Save Faculty

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
