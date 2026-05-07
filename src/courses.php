<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Users — Admin</title>
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
        <div class="container">
          <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
            <div class="min-w-0 flex-1">
              <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Directory</p>
              <h2 class="text-display-sm text-slate-900 dark:text-white">Cources</h2>
            </div>
            <button type="button" onclick="window.location.href='add-course'" class="btn btn-primary shrink-0"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>Add Course</button>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white shadow-card dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-4 border-b border-slate-200 p-4 dark:border-slate-800 lg:flex-row lg:items-center lg:justify-between">
              <div class="relative max-w-md flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="search" data-table-search placeholder="Search name, email, role..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm outline-none ring-brand-500/30 placeholder:text-slate-400 focus:border-brand-300 focus:bg-white focus:ring-4 dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-950" />
              </div>
              <div class="flex flex-wrap gap-2">
                <select id="filter-role" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                  <option value="">All roles</option>
                  <option value="Admin">Admin</option>
                  <option value="Editor">Editor</option>
                  <option value="Viewer">Viewer</option>
                </select>
                <select id="filter-status" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-700 dark:bg-slate-950">
                  <option value="">All statuses</option>
                  <option value="Active">Active</option>
                  <option value="Invited">Invited</option>
                  <option value="Suspended">Suspended</option>
                </select>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400">
                  <tr>
                    <th class="px-4 py-3 font-semibold lg:px-6">Course</th>
                    <th class="px-4 py-3 font-semibold lg:px-6">Description</th>
                    <th class="px-4 py-3 font-semibold lg:px-6">Status</th>
                    <th class="px-4 py-3 font-semibold lg:px-6">Created On</th>
                    <th class="px-4 py-3 text-right font-semibold lg:px-6">Actions</th>
                  </tr>
                </thead>
                <tbody id="coursesTableBody" class="divide-y divide-slate-100 dark:divide-slate-800">
                  <!-- <tr data-role="Admin" data-status="Active" class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/40">
                      <td class="px-4 py-3 lg:px-6"><div class="font-medium">Alex Morgan</div><div class="text-xs text-slate-500 dark:text-slate-400">alex@example.com</div></td>
                      <td class="px-4 py-3 lg:px-6">Admin</td>
                      <td class="px-4 py-3 lg:px-6"><span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">Active</span></td>
                      <td class="px-4 py-3 text-slate-600 dark:text-slate-300 lg:px-6">Jan 12, 2025</td>
                      <td class="px-4 py-3 text-right lg:px-6"><button type="button" class="btn btn-secondary px-2 py-1 text-xs">Edit</button> <button type="button" class="btn btn-secondary px-2 py-1 text-xs">More</button></td>
                    </tr> -->
                </tbody>
              </table>
            </div>

            <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-200 px-4 py-4 dark:border-slate-800 sm:flex-row lg:px-6">
              <p class="text-sm text-slate-500 dark:text-slate-400">Showing <span class="font-medium text-slate-900 dark:text-white">1–5</span> of <span class="font-medium text-slate-900 dark:text-white">48</span></p>
              <nav class="flex items-center gap-1" aria-label="Pagination">
                <button type="button" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 disabled:opacity-40 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" disabled>Previous</button>
                <button type="button" class="rounded-lg bg-brand-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm dark:bg-brand-500">1</button>
                <button type="button" class="rounded-lg border border-slate-200 px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">2</button>
                <button type="button" class="rounded-lg border border-slate-200 px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">3</button>
                <span class="px-2 text-slate-400">…</span>
                <button type="button" class="rounded-lg border border-slate-200 px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">10</button>
                <button type="button" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Next</button>
              </nav>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../dist/js/app.js"></script>


  <script>
    /*
|--------------------------------------------------------------------------
| AUTH CHECK
|--------------------------------------------------------------------------
*/
    if (!localStorage.getItem("admin_token")) {
      window.location.href = "login.php";
    }

    const API_BASE = "http://127.0.0.1:8000/api";

    let currentPage = 1;
    let searchText = "";
    let statusFilter = "";

    /*
    |--------------------------------------------------------------------------
    | AUTH HEADER
    |--------------------------------------------------------------------------
    */
    function getAuthHeaders() {
      return {
        "Accept": "application/json",
        "Authorization": `Bearer ${localStorage.getItem("admin_token")}`
      };
    }

    /*
    |--------------------------------------------------------------------------
    | LOAD COURSES (API)
    |--------------------------------------------------------------------------
    */
    async function loadCourses(page = 1) {

      currentPage = page;

      const params = new URLSearchParams({
        page: currentPage,
        search: searchText,
        status: statusFilter
      });

      try {

        const response = await fetch(
          `${API_BASE}/courses?${params}`, {
            headers: getAuthHeaders()
          }
        );

        if (response.status === 401) {
          localStorage.clear();
          window.location.href = "login.php";
          return;
        }

        const data = await response.json();

        renderCourses(data.data);
        renderPagination(data);

      } catch (err) {
        console.error(err);
        alert("Failed loading courses");
      }
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER COURSES
    |--------------------------------------------------------------------------
    */
    function renderCourses(courses) {

      const tbody = document.getElementById("coursesTableBody");
      tbody.innerHTML = "";

      courses.forEach(course => {

        const statusColor = course.status ? "emerald" : "red";

        tbody.innerHTML += `
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">

            <td class="px-4 py-3 lg:px-6">
                <div class="font-medium">${course.title}</div>
            </td>

            <td class="px-4 py-3 lg:px-6">
                ${course.description ?? "-"}
            </td>

            <td class="px-4 py-3 lg:px-6">
                <span class="rounded-full bg-${statusColor}-50
                px-2.5 py-0.5 text-xs font-medium
                text-${statusColor}-700">
                    ${course.status ? "Active" : "Inactive"}
                </span>
            </td>

            <td class="px-4 py-3 lg:px-6">
                ${new Date(course.created_at).toLocaleDateString()}
            </td>

            <td class="px-4 py-3 text-right lg:px-6">
                <button onclick="editCourse(${course.id})"
                    class="btn btn-secondary px-2 py-1 text-xs">
                    Edit
                </button>

                <button onclick="deleteCourse(${course.id})"
                    class="btn btn-secondary px-2 py-1 text-xs">
                    Delete
                </button>
            </td>

        </tr>`;
      });
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
    function renderPagination(meta) {

      const nav = document.querySelector('[aria-label="Pagination"]');

      let html = "";

      html += `
    <button onclick="loadCourses(${meta.current_page - 1})"
        ${meta.current_page === 1 ? "disabled" : ""}
        class="rounded-lg border px-3 py-2 text-sm">
        Previous
    </button>`;

      for (let i = 1; i <= meta.last_page; i++) {
        html += `
        <button onclick="loadCourses(${i})"
        class="rounded-lg px-3.5 py-2 text-sm
        ${i === meta.current_page ? 'bg-brand-600 text-white' : 'border'}">
        ${i}
        </button>`;
      }

      html += `
    <button onclick="loadCourses(${meta.current_page + 1})"
        ${meta.current_page === meta.last_page ? "disabled" : ""}
        class="rounded-lg border px-3 py-2 text-sm">
        Next
    </button>`;

      nav.innerHTML = html;
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */
    document.querySelector("[data-table-search]")
      .addEventListener("input", e => {
        searchText = e.target.value;
        loadCourses(1);
      });

    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */
    document.getElementById("filter-status")
      .addEventListener("change", e => {
        statusFilter = e.target.value;
        loadCourses(1);
      });

    /*
    |--------------------------------------------------------------------------
    | DELETE COURSE
    |--------------------------------------------------------------------------
    */
    async function deleteCourse(id) {

      if (!confirm("Delete this course?")) return;

      await fetch(`${API_BASE}/courses/${id}`, {
        method: "DELETE",
        headers: getAuthHeaders()
      });

      loadCourses(currentPage);
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    function editCourse(id) {
      window.location.href = `edit-course.php?id=${id}`;
    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */
    document.addEventListener("DOMContentLoaded", () => {
      loadCourses();
    });
  </script>
</body>

</html>