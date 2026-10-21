<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');
$id = (int)($_GET['id'] ?? 0);

/*
|--------------------------------------------------------------------------
| GET FACULTY
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM notification
    WHERE id = :id
");

$stmt->execute([
    ':id' => $id
]);

$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {

    header('location:notification.php');

    exit;
}

$error = '';

/*
|--------------------------------------------------------------------------
| UPDATE FACULTY
|--------------------------------------------------------------------------
*/

if (isset($_POST['update_faculty'])) {

    // keep what the user typed so the form can be re-shown on error
    foreach (['label', 'url'] as $field) {

        $member[$field] = trim($_POST[$field] ?? '');
    }

    $member['status'] = (($_POST['status'] ?? 'active') === 'active') ? "active" : "inactive";

    if ($member['label'] === '') {

        $error = 'Name is required.';
    }

    /*
    |--------------------------------------------------------------------------
    | PHOTO: REPLACE OR REMOVE
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

// print_r( $member['status']);exit;
        $stmt = $conn->prepare("
            UPDATE notification
            SET
            label = :label,
            url = :url,
            status = :status
            WHERE id = :id
        ");

        $stmt->execute([
            ':label'          => $member['label'],
            ':url'   => $member['url'],
            ':status'        => $member['status'],
            ':id'            => $id,
        ]);

        header('location:notification.php');

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

                                        <?php echo $error; ?>

                                    </div>

                                <?php } ?>




                                <!-- SUBJECT -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Label

                                    </label>

                                    <input
                                        type="text"
                                        name="label"
                                        maxlength="255"
                                        value="<?php echo $member['label']; ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- QUALIFICATION -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        URL

                                    </label>

                                    <input
                                        type="text"
                                        name="url"
                                        maxlength="255"
                                        value="<?php echo $member['url']; ?>"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>



                                <!-- STATUS -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        Status

                                    </label>

                                    <select
                                        name="status"
                                        class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950">

                                        <option value="active" <?php echo $member['status'] == "active" ? 'selected' : ''; ?>>Enabled</option>

                                        <option value="inactive" <?php echo $member['status'] == "inactive" ? 'selected' : ''; ?>>Disabled</option>

                                    </select>

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