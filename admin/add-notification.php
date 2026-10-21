<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header('location:login');

    exit;
}

include('../conn.php');
// include('faculty-helpers.php');

$error = '';

$old = [
    'label' => '',
    'url'   => '',
    'status' => '',
];

if (isset($_POST['save'])) {

    foreach (['label', 'url', 'status'] as $field) {

        $old[$field] = trim($_POST[$field] ?? '');
    }

    $old['status'] = (($_POST['status'] ?? '1') === '0') ? 0 : 1;

    if ($old['label'] === '') {

        $error = 'label is required.';
    }

   


    /*
    |--------------------------------------------------------------------------
    | INSERT FACULTY
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        $stmt = $conn->prepare("
            INSERT INTO notification(
                label,
                url,
                status
            )
            VALUES(
                :label,
                :url,
                :status
            )
        ");

        $stmt->execute([
            ':label'          => $old['label'],
            ':url'            => $old['url'],
        ':status'             => "active",
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

    <title>Add Notification — Admin</title>

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

                            Notification

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

                                <!-- LABEL -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        label

                                    </label>

                                    <input
                                        type="text"
                                        name="label"
                                        required
                                        maxlength="255"
                                        value="<?php echo $old['label']; ?>"
                                        placeholder="Enter label"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <!-- URL -->

                                <div>

                                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">

                                        url

                                    </label>

                                    <input
                                        type="url"
                                        name="url"
                                        maxlength="255"
                                        value="<?php echo $old['url']; ?>"
                                        placeholder="e.g. https//....."
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>


                                <!-- BUTTONS -->

                                <div class="flex justify-end gap-3 pt-4">

                                    <a
                                        href="notification.php"
                                        class="btn btn-secondary">

                                        Cancel

                                    </a>

                                    <button
                                        type="submit"
                                        name="save"
                                        class="btn btn-primary">

                                        Save

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
