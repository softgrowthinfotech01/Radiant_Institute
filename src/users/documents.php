<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$search = $_GET['search'] ?? '';

$page = $_GET['page'] ?? 1;

$limit = 10;

$offset = ($page - 1) * $limit;

/* SEARCH */

if($search != ''){

    $stmt = $conn->prepare("
        SELECT 
            documents.*,
            document_titles.title,
            COUNT(document_files.id) AS total_files

        FROM documents

        LEFT JOIN document_titles
        ON documents.title_id = document_titles.id

        LEFT JOIN document_files
        ON documents.id = document_files.document_id

        WHERE documents.user_id=?
        AND document_titles.title LIKE ?

        GROUP BY documents.id

        ORDER BY documents.id DESC

        LIMIT $limit OFFSET $offset
    ");

    $stmt->execute([
        $user_id,
        "%$search%"
    ]);

    $countStmt = $conn->prepare("
        SELECT COUNT(*)

        FROM documents

        LEFT JOIN document_titles
        ON documents.title_id = document_titles.id

        WHERE documents.user_id=?
        AND document_titles.title LIKE ?
    ");

    $countStmt->execute([
        $user_id,
        "%$search%"
    ]);

}else{

    $stmt = $conn->prepare("
        SELECT 
            documents.*,
            document_titles.title,
            COUNT(document_files.id) AS total_files

        FROM documents

        LEFT JOIN document_titles
        ON documents.title_id = document_titles.id

        LEFT JOIN document_files
        ON documents.id = document_files.document_id

        WHERE documents.user_id=?

        GROUP BY documents.id

        ORDER BY documents.id DESC

        LIMIT $limit OFFSET $offset
    ");

    $stmt->execute([$user_id]);

    $countStmt = $conn->prepare("
        SELECT COUNT(*)
        FROM documents
        WHERE user_id=?
    ");

    $countStmt->execute([$user_id]);
}

$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRecords = $countStmt->fetchColumn();

$totalPages = ceil($totalRecords / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Documents</title>

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

        <div class="mb-6 flex items-center justify-between">

            <h1 class="text-2xl font-bold">
                My Documents
            </h1>

        </div>

        <!-- SEARCH -->

        <form method="GET"
            class="mb-6 flex gap-2 max-w-md">

            <input
                type="search"
                name="search"
                value="<?= $search ?>"
                placeholder="Search documents..."
                class="w-full rounded-xl border px-4 py-3">

            <button
                type="submit"
                class="btn btn-primary">

                Search

            </button>

        </form>

        <!-- TABLE -->

        <div class="overflow-x-auto rounded-2xl bg-white shadow">

            <table class="w-full text-sm">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-4 py-3 text-left">
                            Title
                        </th>

                        <th class="px-4 py-3 text-left">
                            Remarks
                        </th>

                        <th class="px-4 py-3 text-left">
                            Files
                        </th>

                        <th class="px-4 py-3 text-left">
                            Date
                        </th>

                        <th class="px-4 py-3 text-right">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(count($documents) > 0){ ?>

                        <?php foreach($documents as $document){ ?>

                            <tr class="border-t">

                                <td class="px-4 py-3">

                                    <?= $document['title'] ?>

                                </td>

                                <td class="px-4 py-3">

                                    <?= $document['remarks'] ?>

                                </td>

                                <td class="px-4 py-3">

                                    <?= $document['total_files'] ?> Files

                                </td>

                                <td class="px-4 py-3">

                                    <?= date('d M Y', strtotime($document['created_at'])) ?>

                                </td>

                                <td class="px-4 py-3 text-right">

                                    <a
                                        href="view_document.php?id=<?= $document['id'] ?>"
                                        class="btn btn-primary text-xs">

                                        View

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td colspan="5"
                                class="px-4 py-10 text-center text-slate-500">

                                No Documents Found

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>
            </main>
    </div>
    </div>

</body>

</html>