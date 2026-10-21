<!DOCTYPE html>
<html lang="en" class="h-full">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../dist/css/output.css" />
  </head>
  <body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
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
          <div class="container max-w-3xl">
            <div class="mb-8">
              <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Workspace</p>
              <h2 class="text-display-sm text-slate-900 dark:text-white">Settings</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Profile, alerts, and security preferences.</p>
            </div>

            <div class="space-y-6">
              <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold">Profile</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Basic details visible to your team.</p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Display name<input type="text" value="Jordan Doe" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Work email<input type="email" value="jordan@example.com" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                  <label class="sm:col-span-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Timezone<select class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm dark:border-slate-700 dark:bg-slate-950"><option>UTC−08:00 Pacific</option><option>UTC−05:00 Eastern</option><option>UTC+00:00 London</option></select></label>
                </div>
              </section>

              <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold">Notifications</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Choose how we reach you.</p>
                <ul class="mt-6 divide-y divide-slate-100 dark:divide-slate-800">
                  <li class="flex flex-wrap items-center justify-between gap-3 py-4 first:pt-0">
                    <div><p class="font-medium">Product updates</p><p class="text-sm text-slate-500 dark:text-slate-400">Release notes and roadmap summaries.</p></div>
                    <input type="checkbox" checked class="h-5 w-5 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-950" />
                  </li>
                  <li class="flex flex-wrap items-center justify-between gap-3 py-4">
                    <div><p class="font-medium">Billing alerts</p><p class="text-sm text-slate-500 dark:text-slate-400">Invoices, failed payments, usage warnings.</p></div>
                    <input type="checkbox" checked class="h-5 w-5 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-950" />
                  </li>
                  <li class="flex flex-wrap items-center justify-between gap-3 py-4">
                    <div><p class="font-medium">Weekly digest</p><p class="text-sm text-slate-500 dark:text-slate-400">Summary email every Monday.</p></div>
                    <input type="checkbox" class="h-5 w-5 rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-950" />
                  </li>
                </ul>
              </section>

              <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold">Security</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Protect your administrator account.</p>
                <div class="mt-6 grid gap-4">
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Current password<input type="password" autocomplete="current-password" placeholder="••••••••" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">New password<input type="password" autocomplete="new-password" placeholder="••••••••" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                </div>
              </section>

              <div class="flex flex-wrap justify-end gap-2 pb-8">
                <button type="button" class="btn btn-secondary">Discard</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <script src="../dist/js/app.js"></script>
  </body>
</html>
