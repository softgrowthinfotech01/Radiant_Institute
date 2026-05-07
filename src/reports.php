<!DOCTYPE html>
<html lang="en" class="h-full">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reports — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../dist/css/output.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" defer></script>
  </head>
  <body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div id="mobile-backdrop" class="fixed inset-0 z-30 hidden bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

    <div class="flex min-h-full">
      <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex h-full w-64 -translate-x-full flex-col border-r border-slate-200 bg-white shadow-card transition-all duration-200 dark:border-slate-800 dark:bg-slate-950 lg:static lg:z-0 lg:h-screen lg:translate-x-0 lg:shadow-none">
        <div class="flex h-16 shrink-0 items-center gap-2 border-b border-slate-200 px-4 dark:border-slate-800">
          <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white shadow-card">A</span>
          <span class="brand-text truncate text-lg font-semibold tracking-tight">Acme Admin</span>
          <button type="button" id="mobile-menu-close" class="ml-auto rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800" aria-label="Close menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-3">
          <a href="index.html" data-nav class="sidebar-link"><svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span class="nav-label">Dashboard</span></a>
          <a href="users.html" data-nav class="sidebar-link"><svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg><span class="nav-label">Users</span></a>
          <a href="bookings.html" data-nav class="sidebar-link"><svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span class="nav-label">Bookings</span></a>
          <a href="reports.html" data-nav class="sidebar-link"><svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><span class="nav-label">Reports</span></a>
          <a href="payments.html" data-nav class="sidebar-link"><svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg><span class="nav-label">Payments</span></a>
          <a href="settings.html" data-nav class="sidebar-link"><svg class="h-5 w-5 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="nav-label">Settings</span></a>
        </nav>
        <div class="sidebar-footer-inner border-t border-slate-200 p-3 dark:border-slate-800">
          <a href="#" class="sidebar-link text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/40"><svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg><span class="nav-label">Logout</span></a>
        </div>
      </aside>

      <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">
        <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-3 border-b border-slate-200 bg-white/90 px-4 backdrop-blur dark:border-slate-800 dark:bg-slate-950/90 lg:px-6">
          <button type="button" id="mobile-menu-open" class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Open menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <button type="button" id="sidebar-collapse" class="hidden rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:inline-flex dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Toggle sidebar">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
          </button>
          <div class="flex min-w-0 flex-1 items-center gap-2">
            <h1 class="truncate text-lg font-semibold tracking-tight">Reports</h1>
          </div>
          <div class="flex items-center gap-2">
            <button type="button" id="theme-toggle" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-600 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800" aria-label="Toggle theme">
              <svg class="hidden h-5 w-5 dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
              <svg class="h-5 w-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
            <button type="button" class="relative rounded-lg border border-slate-200 bg-white p-2 text-slate-600 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              <span class="absolute right-1 top-1 h-2 w-2 rounded-full bg-brand-500 ring-2 ring-white dark:ring-slate-900"></span>
            </button>
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 py-1 pl-1 pr-3 dark:border-slate-700 dark:bg-slate-900">
              <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-900/40 dark:text-brand-300">JD</span>
              <div class="hidden leading-tight sm:block">
                <p class="text-sm font-medium">Jordan Doe</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">Admin</p>
              </div>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-auto p-4 lg:p-8">
          <div class="container">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Analytics</p>
                <h2 class="text-display-sm text-slate-900 dark:text-white">Reports hub</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Exports, snapshots, and scheduled deliveries.</p>
              </div>
              <div class="flex flex-wrap gap-2">
                <button type="button" class="btn btn-secondary">Browse templates</button>
                <button type="button" class="btn btn-primary">Create report</button>
              </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Exports (30d)</p>
                <p class="mt-2 text-2xl font-semibold">218</p>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Avg. generation</p>
                <p class="mt-2 text-2xl font-semibold">1.8s</p>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Failures</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-600 dark:text-emerald-400">0.4%</p>
              </div>
            </div>

            <div class="mt-8 grid gap-6 xl:grid-cols-5">
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900 xl:col-span-3">
                <div class="mb-4 flex items-center justify-between">
                  <h3 class="text-base font-semibold">Export volume</h3>
                  <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                    <option>This quarter</option>
                    <option>Last quarter</option>
                  </select>
                </div>
                <div class="h-64">
                  <canvas id="chart-exports"></canvas>
                </div>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-card dark:border-slate-800 dark:bg-slate-900 xl:col-span-2">
                <h3 class="mb-4 text-base font-semibold">Popular templates</h3>
                <ul class="space-y-3">
                  <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                    <span class="font-medium">Executive summary</span>
                    <button type="button" class="btn btn-secondary px-2 py-1 text-xs">Open</button>
                  </li>
                  <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                    <span class="font-medium">Finance rollup</span>
                    <button type="button" class="btn btn-secondary px-2 py-1 text-xs">Open</button>
                  </li>
                  <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-950/50">
                    <span class="font-medium">Operations KPIs</span>
                    <button type="button" class="btn btn-secondary px-2 py-1 text-xs">Open</button>
                  </li>
                </ul>
              </div>
            </div>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">
              <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                <h3 class="text-base font-semibold">Scheduled reports</h3>
                <button type="button" class="btn btn-secondary text-sm">Manage schedules</button>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                  <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400">
                    <tr>
                      <th class="px-5 py-3 font-semibold">Name</th>
                      <th class="px-5 py-3 font-semibold">Cadence</th>
                      <th class="px-5 py-3 font-semibold">Recipients</th>
                      <th class="px-5 py-3 font-semibold">Next run</th>
                      <th class="px-5 py-3 text-right font-semibold">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                      <td class="px-5 py-3 font-medium">Weekly KPI digest</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">Weekly · Mon 08:00</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">12</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">May 5, 2026</td>
                      <td class="px-5 py-3 text-right"><button type="button" class="btn btn-secondary px-2 py-1 text-xs">Edit</button></td>
                    </tr>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                      <td class="px-5 py-3 font-medium">Finance PDF</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">Monthly · 1st</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">4</td>
                      <td class="px-5 py-3 text-slate-600 dark:text-slate-300">Jun 1, 2026</td>
                      <td class="px-5 py-3 text-right"><button type="button" class="btn btn-secondary px-2 py-1 text-xs">Edit</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <script src="../dist/js/app.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        if (typeof Chart === "undefined") return;
        var grid = document.documentElement.classList.contains("dark") ? "rgba(148,163,184,0.15)" : "rgba(15,23,42,0.08)";
        var tick = document.documentElement.classList.contains("dark") ? "#94a3b8" : "#64748b";
        var el = document.getElementById("chart-exports");
        if (!el) return;
        new Chart(el, {
          type: "bar",
          data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [
              {
                label: "Exports",
                data: [42, 55, 48, 61, 53, 67],
                backgroundColor: "rgba(99,102,241,0.65)",
                borderRadius: 8,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { grid: { display: false }, ticks: { color: tick } },
              y: { grid: { color: grid }, ticks: { color: tick }, beginAtZero: true },
            },
          },
        });
      });
    </script>
  </body>
</html>
