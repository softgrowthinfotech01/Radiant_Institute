<?php

include('../admin_check.php');
include('../conn.php');

/* CHECK ID */
if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = (int)$_GET['id'];

/* FETCH TITLE */
$stmt = $conn->prepare("
    SELECT * FROM document_titles
    WHERE id=?
");

$stmt->execute([$id]);

$title = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$title) {
    die("Title not found");
}

/* UPDATE */
if (isset($_POST['update'])) {

    $newTitle = trim($_POST['title']);
    $status = $_POST['status'];

    $update = $conn->prepare("
        UPDATE document_titles
        SET title=?, status=?
        WHERE id=?
    ");

    $update->execute([
        $newTitle,
        $status,
        $id
    ]);

    header("Location: list_title.php?msg=Title Updated");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Title</title>

    <link rel="stylesheet" href="../../dist/css/output.css">

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

                                Register User

                            </h2>

                        </div>

                    </div>

                    <form method="POST" class="space-y-5">

                        <!-- TITLE -->
<div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">
                            <div class="space-y-6 p-6">                        
<div>

                            <label class="block mb-2 text-sm font-medium">

                                Title

                            </label>

                            <input
                                type="text"
                                name="title"
                                required
                                value="<?= htmlspecialchars($title['title']) ?>"
                                class="w-full border rounded-xl px-4 py-3">

                        </div>

                        <!-- STATUS -->

                        <div>

                            <label class="block mb-2 text-sm font-medium">

                                Status

                            </label>

                            <select
                                name="status"
                                class="w-full border rounded-xl px-4 py-3">

                                <option
                                    value="active"
                                    <?= $title['status'] == 'active' ? 'selected' : '' ?>>

                                    Active

                                </option>

                                <option
                                    value="inactive"
                                    <?= $title['status'] == 'inactive' ? 'selected' : '' ?>>

                                    Inactive

                                </option>

                            </select>

                        </div>

                        <!-- BUTTON -->
<div class="flex justify-end gap-3 pt-4">
                        <button
                            type="submit"
                            name="update"
                            class="btn btn-primary m-3">

                            Update Title

                        </button>
</div>
</div>
</div>
                    </form>

                </div>
            </main>
        </div>
    </div>

</body>

</html>