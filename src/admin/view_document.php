<?php

include('../admin_check.php');
include('../conn.php');

if(!isset($_GET['id'])){

    die("Invalid Request");
}

$id = intval($_GET['id']);

/* FETCH DOCUMENT */

$stmt = $conn->prepare("
    SELECT
        documents.*,
        users.name AS user_name,
        users.email,
        document_titles.title

    FROM documents

    LEFT JOIN users
    ON documents.user_id = users.id

    LEFT JOIN document_titles
    ON documents.title_id = document_titles.id

    WHERE documents.id=?
");

$stmt->execute([$id]);

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
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>View Document</title>

    <link rel="stylesheet"
        href="../../dist/css/output.css" />

</head>

<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white">

<div class="flex min-h-full">

    <?php include('sidebar.php'); ?>

    <div class="flex min-h-screen flex-1 flex-col">

        <?php include('header.php'); ?>

        <main class="flex-1 p-4 lg:p-8">

            <div class="container max-w-6xl">

                <!-- HEADER -->

                <div class="mb-6">

                    <h2 class="text-2xl font-bold">

                        View Document

                    </h2>

                </div>

                <!-- CARD -->

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">

                    <!-- USER -->

                    <div class="mb-6 grid gap-6 md:grid-cols-2">

                        <div>

                            <p class="text-sm text-slate-500">

                                User

                            </p>

                            <h3 class="mt-1 text-lg font-semibold">

                                <?= htmlspecialchars($document['user_name']) ?>

                            </h3>

                        </div>

                        <div>

                            <p class="text-sm text-slate-500">

                                Email

                            </p>

                            <h3 class="mt-1 text-lg font-semibold">

                                <?= htmlspecialchars($document['email']) ?>

                            </h3>

                        </div>

                    </div>

                    <!-- TITLE -->

                    <div class="mb-6">

                        <p class="text-sm text-slate-500">

                            Document Title

                        </p>

                        <h3 class="mt-1 text-lg font-semibold">

                            <?= htmlspecialchars($document['title']) ?>

                        </h3>

                    </div>

                    <!-- REMARKS -->

                    <div class="mb-6">

                        <p class="text-sm text-slate-500">

                            Remarks

                        </p>

                        <div class="mt-2 rounded-xl bg-slate-100 p-4 dark:bg-slate-800">

                            <?= nl2br(htmlspecialchars($document['remarks'])) ?>

                        </div>

                    </div>

                    <!-- FILES -->

                    <div>

                        <p class="mb-4 text-sm text-slate-500">

                            Uploaded Files

                        </p>

                        <div class="grid gap-4 md:grid-cols-3">

                            <?php if(count($files) > 0){ ?>

                                <?php foreach($files as $file){ ?>

                                    <?php

                                    $filePath =
                                    "../uploads/documents/".$file['file_name'];

                                    $extension =
                                    strtolower(pathinfo(
                                        $file['file_name'],
                                        PATHINFO_EXTENSION
                                    ));

                                    ?>

                                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">

                                        <!-- IMAGE PREVIEW -->

                                        <?php if(
                                            in_array(
                                                $extension,
                                                ['jpg','jpeg','png','gif','webp']
                                            )
                                        ){ ?>

                                            <img
                                                src="<?= $filePath ?>"
                                                class="mb-4 h-48 w-full rounded-xl object-cover">

                                        <?php } else { ?>

                                            <div class="mb-4 flex h-48 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">

                                                <span class="text-sm">

                                                    FILE

                                                </span>

                                            </div>

                                        <?php } ?>

                                        <!-- FILE NAME -->

                                        <p class="mb-4 break-all text-sm">

                                            <?= htmlspecialchars($file['file_name']) ?>

                                        </p>

                                        <!-- ACTIONS -->

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

                                            <a
                                                href="delete_document_file.php?id=<?= $file['id'] ?>&document_id=<?= $document['id'] ?>"
                                                onclick="return confirm('Delete this file?')"
                                                class="btn btn-danger text-xs">

                                                Delete

                                            </a>

                                        </div>

                                    </div>

                                <?php } ?>

                            <?php } else { ?>

                                <p>No Files Found</p>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

</body>
</html>