<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){

    die("Invalid Request");
}

$id = intval($_GET['id']);

/* FETCH DOCUMENT */

$stmt = $conn->prepare("
    SELECT 
        documents.*,
        document_titles.title

    FROM documents

    LEFT JOIN document_titles
    ON documents.title_id = document_titles.id

    WHERE documents.id=?
    AND documents.user_id=?
");

$stmt->execute([
    $id,
    $user_id
]);

$document = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$document){

    die("Document Not Found");
}

/* FETCH FILES */

$fileStmt = $conn->prepare("
    SELECT *
    FROM document_files
    WHERE document_id=?
");

$fileStmt->execute([$id]);

$files = $fileStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>View Document</title>

    <link rel="stylesheet"
        href="../../dist/css/output.css">

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

        <!-- HEADER -->

        <div class="mb-6 flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold">

                    <?= $document['title'] ?>

                </h1>

                <p class="mt-2 text-slate-500">

                    <?= $document['remarks'] ?>

                </p>

            </div>

            <a
                href="documents.php"
                class="btn btn-secondary">

                Back

            </a>

        </div>

        <!-- FILES -->

        <div class="rounded-2xl bg-white shadow p-6">

            <h2 class="mb-5 text-lg font-semibold">

                Files

            </h2>

            <?php if(count($files) > 0){ ?>

                <div class="space-y-4">

                    <?php foreach($files as $file){ ?>

                        <?php
                            $filePath =
                            "../uploads/documents/" .
                            $file['file_name'];
                        ?>

                        <div class="flex items-center justify-between border rounded-xl p-4">

                            <div>

                                <p class="font-medium">

                                    <?= $file['file_name'] ?>

                                </p>

                            </div>

                            <div class="flex gap-2">

                                <a
                                    href="<?= $filePath ?>"
                                    target="_blank"
                                    class="btn btn-secondary text-xs">

                                    View

                                </a>

                                <a
                                    href="<?= $filePath ?>"
                                    download
                                    class="btn btn-primary text-xs">

                                    Download

                                </a>

                            </div>

                        </div>

                    <?php } ?>

                </div>

            <?php } else { ?>

                <div class="text-slate-500">

                    No Files Found

                </div>

            <?php } ?>

        </div>
            </main>
        </div>
    </div>

</body>

</html>