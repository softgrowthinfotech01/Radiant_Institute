<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white shadow-card transition-all duration-200 dark:border-slate-800 dark:bg-slate-950 lg:static lg:z-0 lg:h-screen lg:translate-x-0 lg:shadow-none"
>

    <!-- =========================================================
         SIDEBAR HEADER
    ========================================================== -->

    <div
        class="flex h-16 shrink-0 items-center gap-2 border-b border-slate-200 px-4 dark:border-slate-800"
    >

        <span
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white shadow-card"
        >
            R
        </span>

        <span
            class="brand-text truncate text-lg font-semibold tracking-tight"
        >
            Admin
        </span>

        <button
            type="button"
            id="mobile-menu-close"
            class="ml-auto rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800"
            aria-label="Close menu"
        >

            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />

            </svg>

        </button>

    </div>


    <!-- =========================================================
         NAVIGATION
    ========================================================== -->

    <nav class="flex-1 overflow-y-auto p-3">

        <div class="flex flex-col gap-1">


            <!-- =================================================
                 DASHBOARD
            ================================================== -->

            <a
                href="dashboard"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />

                </svg>

                <span class="nav-label">
                    Dashboard
                </span>

            </a>


            <!-- =================================================
                 USERS
            ================================================== -->

            <a
                href="list_user"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"
                    />

                </svg>

                <span class="nav-label">
                    Users
                </span>

            </a>


            <!-- =================================================
                 TITLES
            ================================================== -->

            <a
                href="list_title"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h10"
                    />

                </svg>

                <span class="nav-label">
                    Titles
                </span>

            </a>


            <!-- =================================================
                 DOCUMENTS
            ================================================== -->

            <a
                href="list_documents"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14 3v6h6"
                    />

                </svg>

                <span class="nav-label">
                    Documents
                </span>

            </a>


            <!-- =================================================
                 TASK MANAGEMENT SECTION
            ================================================== -->

            <div
                class="mt-5 mb-2 px-3"
            >

                <span
                    class="nav-label text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500"
                >
                    Task Management
                </span>

            </div>


            <!-- =================================================
                 PROJECTS
            ================================================== -->

            <a
                href="list_projects.php"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"
                    />

                </svg>

                <span class="nav-label">
                    Projects
                </span>

            </a>


            <!-- =================================================
                 TASKS
            ================================================== -->

            <a
                href="list_tasks.php"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 5h6M9 9h6M9 13h6M9 17h6"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 5h.01M5 9h.01M5 13h.01M5 17h.01"
                    />

                </svg>

                <span class="nav-label">
                    Tasks
                </span>

            </a>


            <!-- =================================================
                 TASK ACTIVITY
            ================================================== -->

            <a
                href="task_activities.php"
                data-nav
                class="sidebar-link"
            >

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 8v4l3 2"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />

                </svg>

                <span class="nav-label">
                    Task Activity
                </span>

            </a>


        </div>


        <!-- =========================================================
             LOGOUT
        ========================================================== -->

        <div
            class="mt-auto sidebar-footer-inner border-t border-slate-200 p-3 dark:border-slate-800"
        >

            <a
                href="../logout"
                class="sidebar-link text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/40"
            >

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                    />

                </svg>

                <span class="nav-label">
                    Logout
                </span>

            </a>

        </div>

    </nav>

</aside>