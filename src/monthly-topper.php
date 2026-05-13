<?php

session_start();

if(!isset($_SESSION['admin'])){

    header('location:login');

    exit;
}

include('../conn.php');

$search = $_GET['search'] ?? '';

$page = isset($_GET['page'])
    ? (int)$_GET['page']
    : 1;

$limit = 5;

$offset = ($page - 1) * $limit;

/*
|--------------------------------------------------------------------------
| MAIN QUERY
|--------------------------------------------------------------------------
*/

$query = "
SELECT *
FROM monthly_toppers
WHERE 1
";

$params = [];

if($search != ''){

    $query .= "
    AND (
        student_name LIKE :search
        OR course_name LIKE :search
        OR month LIKE :search
    )
    ";

    $params[':search'] = "%$search%";
}

/*
|--------------------------------------------------------------------------
| COUNT QUERY
|--------------------------------------------------------------------------
*/

$countQuery = "
SELECT COUNT(*) as total
FROM monthly_toppers
WHERE 1
";

if($search != ''){

    $countQuery .= "
    AND (
        student_name LIKE :search
        OR course_name LIKE :search
        OR month LIKE :search
    )
    ";
}

$countStmt = $conn->prepare($countQuery);

$countStmt->execute($params);

$totalRecords =
$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalPages =
ceil($totalRecords / $limit);

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$query .= "
ORDER BY id DESC
LIMIT $limit OFFSET $offset
";

$stmt = $conn->prepare($query);

$stmt->execute($params);

$toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />

    <title>Monthly Topper — Admin</title>

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

                <div class="container">

                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">

                        <div class="min-w-0 flex-1">

                            <h2 class="text-display-sm text-slate-900 dark:text-white">

                                Monthly Topper

                            </h2>

                        </div>

                        <button
                            type="button"
                            onclick="window.location.href='add-topper.php'"
                            class="btn btn-primary shrink-0">

                            <svg class="h-4 w-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4" />

                            </svg>

                            Add Topper

                        </button>

                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">

                        <!-- SEARCH -->

                        <div class="flex flex-col gap-4 border-b border-slate-200 p-4 dark:border-slate-800 lg:flex-row lg:items-center lg:justify-between">

                            <form method="GET"
                                class="flex w-full max-w-md gap-2">

                                <div class="relative flex-1">

                                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />

                                    </svg>

                                    <input
                                        type="search"
                                        name="search"
                                        value="<?php echo $search; ?>"
                                        placeholder="Search topper..."
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    Search

                                </button>

                            </form>

                        </div>

                        <!-- TABLE -->

                        <div class="overflow-x-auto">

                            <table class="w-full min-w-[900px] text-left text-sm">

                                <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400">

                                    <tr>

                                        <th class="px-4 py-3 font-semibold lg:px-6">
                                            Image
                                        </th>

                                        <th class="px-4 py-3 font-semibold lg:px-6">
                                            Student Name
                                        </th>

                                        <th class="px-4 py-3 font-semibold lg:px-6">
                                            Course
                                        </th>

                                        <th class="px-4 py-3 font-semibold lg:px-6">
                                            Date
                                        </th>

                                        <th class="px-4 py-3 font-semibold lg:px-6">
                                            Created On
                                        </th>

                                        <th class="px-4 py-3 font-semibold lg:px-6">
                                            Updated On
                                        </th>

                                        <th class="px-4 py-3 text-right font-semibold lg:px-6">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if(count($toppers) > 0){ ?>

                                        <?php foreach($toppers as $topper){ ?>

                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">

                                                <td class="px-4 py-3 lg:px-6">

                                                    <?php if($topper['image']){ ?>

                                                        <img
                                                            src="uploads/monthly-toppers/<?php echo $topper['image']; ?>"
                                                            class="h-14 w-14 rounded-lg border border-slate-200 object-cover dark:border-slate-700" />

                                                    <?php } ?>

                                                </td>

                                                <td class="px-4 py-3 lg:px-6">

                                                    <div class="font-medium">

                                                        <?php echo $topper['student_name']; ?>

                                                    </div>

                                                </td>

                                                <td class="px-4 py-3 lg:px-6">

                                                    <?php echo $topper['course_name']; ?>

                                                </td>

                                                <td class="px-4 py-3 lg:px-6">

    <?php
    echo date(
        'd M Y',
        strtotime($topper['topper_date'])
    );
    ?>

</td>

                                                <td class="px-4 py-3 lg:px-6">

                                                    <?php
                                                    echo date(
                                                        'd M Y',
                                                        strtotime($topper['created_at'])
                                                    );
                                                    ?>

                                                </td>

                                                <td class="px-4 py-3 lg:px-6">

                                                    <?php
                                                    echo date(
                                                        'd M Y',
                                                        strtotime($topper['updated_at'])
                                                    );
                                                    ?>

                                                </td>

                                                <td class="px-4 py-3 text-right lg:px-6">

                                                    <a
                                                        href="edit-topper.php?id=<?php echo $topper['id']; ?>"
                                                        class="btn btn-secondary px-2 py-1 text-xs">

                                                        Edit

                                                    </a>

                                                    <a
                                                        href="delete-topper.php?id=<?php echo $topper['id']; ?>"
                                                        onclick="return confirm('Delete this topper?')"
                                                        class="btn btn-secondary px-2 py-1 text-xs">

                                                        Delete

                                                    </a>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    <?php } else { ?>

                                        <tr>

                                            <td colspan="7"
                                                class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">

                                                No Topper Found

                                            </td>

                                        </tr>

                                    <?php } ?>

                                </tbody>

                            </table>

                        </div>

                        <!-- PAGINATION -->

                        <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-200 px-4 py-4 dark:border-slate-800 sm:flex-row lg:px-6">

                            <p class="text-sm text-slate-500 dark:text-slate-400">

                                Showing

                                <span class="font-medium text-slate-900 dark:text-white">

                                    <?php echo count($toppers); ?>

                                </span>

                                of

                                <span class="font-medium text-slate-900 dark:text-white">

                                    <?php echo $totalRecords; ?>

                                </span>

                                toppers

                            </p>

                            <div class="flex items-center gap-2">

                                <?php if($page > 1){ ?>

                                    <a
                                        href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">

                                        Previous

                                    </a>

                                <?php } ?>

                                <?php for($i = 1; $i <= $totalPages; $i++){ ?>

                                    <a
                                        href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"
                                        class="
                                        rounded-lg px-3.5 py-2 text-sm font-medium

                                        <?php

                                        if($page == $i){

                                            echo '
                                            bg-brand-600
                                            text-white
                                            ';

                                        }else{

                                            echo '
                                            border border-slate-200
                                            text-slate-700
                                            hover:bg-slate-50
                                            dark:border-slate-700
                                            dark:text-slate-200
                                            dark:hover:bg-slate-800
                                            ';
                                        }

                                        ?>
                                        ">

                                        <?php echo $i; ?>

                                    </a>

                                <?php } ?>

                                <?php if($page < $totalPages){ ?>

                                    <a
                                        href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">

                                        Next

                                    </a>

                                <?php } ?>

                            </div>

                        </div>

                    </div>

                </div>

            </main>

        </div>

    </div>

    <script src="../dist/js/app.js"></script>

</body>

</html>