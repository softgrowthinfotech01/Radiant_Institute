<?php

include('../check.php');
include('../conn.php');

$user_id = $_SESSION['user_id'];

$select = $conn->prepare("SELECT * FROM users WHERE id = $user_id");
$select->execute();
$ret = $select->fetch(PDO::FETCH_ASSOC);
/* TOTAL DOCUMENTS */
$stmt = $conn->prepare("
    SELECT COUNT(*) 
    FROM documents
    WHERE user_id=?
");

$stmt->execute([$user_id]);

$totalDocuments = $stmt->fetchColumn();

/* TOTAL FILES */
$stmt = $conn->prepare("
    SELECT COUNT(document_files.id)
    FROM document_files

    LEFT JOIN documents
    ON document_files.document_id = documents.id

    WHERE documents.user_id=?
");

$stmt->execute([$user_id]);

$totalFiles = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard — Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="../../dist/css/output.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" defer></script>
</head>

<body
  class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
  <div id="mobile-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

  <div class="flex min-h-full">
    <?php
    include('sidebar.php');
    ?>

    <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">

      <?php
      include('header.php');
      ?>

      <main class="flex-1 overflow-auto p-4 lg:p-8">
        <div class="container">
          <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
              <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Overview</p>
              <h2 class="text-display-sm text-slate-900 dark:text-white">Welcome back - <?= $ret['name'] ?></h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Giving you best services.</p>
            </div>
            <div class="flex gap-2">
              <!-- <button type="button" onclick="window.location.href='extra-curricular'" class="btn btn-secondary">Extra Curricular Activity</button> -->
              <button type="button" onclick="window.location.href='documents'" class="btn btn-primary">Documents</button>
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Documents</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight"> <?= $totalDocuments ?></p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Files</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight"><?= $totalFiles; ?></p>
            </div>
            </div>
          </div>


        </div>
      </main>
    </div>
  </div>

  <script src="../../dist/js/app.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      if (typeof Chart === "undefined") return;
      var grid = document.documentElement.classList.contains("dark") ? "rgba(148,163,184,0.15)" : "rgba(15,23,42,0.08)";
      var tick = document.documentElement.classList.contains("dark") ? "#94a3b8" : "#64748b";
      var lineDataset = {
        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        datasets: [{
          label: "Revenue ($k)",
          data: [12, 19, 14, 22, 18, 26, 24],
          borderColor: "#6366f1",
          backgroundColor: "rgba(99,102,241,0.12)",
          fill: true,
          tension: 0.35,
          borderWidth: 2,
        }, ],
      };
      var ctxR = document.getElementById("chart-revenue");
      if (ctxR) {
        new Chart(ctxR, {
          type: "line",
          data: lineDataset,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              x: {
                grid: {
                  color: grid
                },
                ticks: {
                  color: tick
                }
              },
              y: {
                grid: {
                  color: grid
                },
                ticks: {
                  color: tick
                }
              },
            },
          },
        });
      }
      var ctxS = document.getElementById("chart-sources");
      if (ctxS) {
        new Chart(ctxS, {
          type: "doughnut",
          data: {
            labels: ["Direct", "Organic", "Referral"],
            datasets: [{
              data: [42, 33, 25],
              backgroundColor: ["#6366f1", "#22c55e", "#f59e0b"],
              borderWidth: 0,
            }, ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              }
            },
            cutout: "62%",
          },
        });
      }
    });
  </script>
</body>

</html>