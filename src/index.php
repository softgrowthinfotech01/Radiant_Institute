<?php
session_start();
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
  <link rel="stylesheet" href="../dist/css/output.css" />
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
              <h2 class="text-display-sm text-slate-900 dark:text-white">Welcome back</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Track performance and recent activity.</p>
            </div>
            <div class="flex gap-2">
              <button type="button" class="btn btn-secondary">Export</button>
              <button type="button" class="btn btn-primary">New report</button>
            </div>
          </div>

          <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total revenue</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight">$84,310</p>
              <p class="mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400">+12.4% vs last month</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Active users</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight">12,482</p>
              <p class="mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400">+3.1% vs last week</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Bookings</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight">1,042</p>
              <p class="mt-2 text-xs font-medium text-amber-600 dark:text-amber-400">-2.0% vs last month</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
              <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Conversion</p>
              <p class="mt-2 text-3xl font-semibold tracking-tight">4.8%</p>
              <p class="mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400">+0.6 pts</p>
            </div>
          </div>

          <div class="mt-8 grid gap-6 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900 xl:col-span-2">
              <div class="mb-4 flex items-center justify-between gap-4">
                <h3 class="text-base font-semibold">Revenue</h3>
                <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                  <option>Last 7 days</option>
                  <option>Last 30 days</option>
                </select>
              </div>
              <div class="h-72">
                <canvas id="chart-revenue"></canvas>
              </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900">
              <h3 class="mb-4 text-base font-semibold">Traffic sources</h3>
              <div class="mx-auto h-52 max-w-[14rem]">
                <canvas id="chart-sources"></canvas>
              </div>
              <ul class="mt-6 space-y-3 text-sm">
                <li class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Direct</span><span class="font-medium">42%</span></li>
                <li class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Organic</span><span class="font-medium">33%</span></li>
                <li class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Referral</span><span class="font-medium">25%</span></li>
              </ul>
            </div>
          </div>

          <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
              <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <h3 class="text-base font-semibold">Recent activity</h3>
                <button type="button" class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400">View all</button>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full min-w-[520px] text-left text-sm">
                  <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400">
                    <tr>
                      <th class="px-5 py-3 font-semibold">User</th>
                      <th class="px-5 py-3 font-semibold">Action</th>
                      <th class="px-5 py-3 font-semibold">Status</th>
                      <th class="px-5 py-3 font-semibold text-right">Time</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                      <td class="px-5 py-3 font-medium">Alex Morgan</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">Updated profile</td>
                      <td class="px-5 py-3"><span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">Success</span></td>
                      <td class="px-5 py-3 text-right text-slate-500">2m ago</td>
                    </tr>
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                      <td class="px-5 py-3 font-medium">Sam Wilson</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">New booking #4892</td>
                      <td class="px-5 py-3"><span class="rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700 dark:bg-brand-950/50 dark:text-brand-300">Pending</span></td>
                      <td class="px-5 py-3 text-right text-slate-500">14m ago</td>
                    </tr>
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                      <td class="px-5 py-3 font-medium">Priya Sharma</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">Payment received</td>
                      <td class="px-5 py-3"><span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">Paid</span></td>
                      <td class="px-5 py-3 text-right text-slate-500">1h ago</td>
                    </tr>
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50">
                      <td class="px-5 py-3 font-medium">Chris Lee</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">Exported report</td>
                      <td class="px-5 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">Info</span></td>
                      <td class="px-5 py-3 text-right text-slate-500">3h ago</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">
              <div class="border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <h3 class="text-base font-semibold">Notifications</h3>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Stay on top of alerts</p>
              </div>
              <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                <li class="flex gap-3 px-5 py-4 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/40">
                  <span class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-brand-500"></span>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium">Billing threshold reached</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Review usage limits for the Pro workspace.</p>
                    <p class="mt-2 text-xs text-slate-400">Today · 09:14</p>
                  </div>
                </li>
                <li class="flex gap-3 px-5 py-4 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/40">
                  <span class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-emerald-500"></span>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium">Deploy succeeded</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Production is running build #5821.</p>
                    <p class="mt-2 text-xs text-slate-400">Yesterday · 22:03</p>
                  </div>
                </li>
                <li class="flex gap-3 px-5 py-4 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/40">
                  <span class="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-amber-500"></span>
                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium">Security advisory</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Rotate API keys older than 90 days.</p>
                    <p class="mt-2 text-xs text-slate-400">May 1 · 15:40</p>
                  </div>
                </li>
              </ul>
              <div class="border-t border-slate-200 p-4 dark:border-slate-800">
                <button type="button" class="btn btn-secondary w-full">Notification settings</button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../dist/js/app.js"></script>
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