<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white shadow-card transition-all duration-200 dark:border-slate-800 dark:bg-slate-950 lg:static lg:z-0 lg:h-screen lg:translate-x-0 lg:shadow-none">

    <!-- Sidebar Header -->

    <div
        class="flex h-16 shrink-0 items-center gap-2 border-b border-slate-200 px-4 dark:border-slate-800">

        <span
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-600 text-sm font-bold text-white shadow-card">
            R
        </span>

        <span
            class="brand-text truncate text-lg font-semibold tracking-tight">
            User
        </span>

        <button
            type="button"
            id="mobile-menu-close"
            class="ml-auto rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800"
            aria-label="Close menu">

            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />

            </svg>

        </button>

    </div>


    <!-- Navigation -->

    <nav class="flex-1 overflow-y-auto p-3">

        <div class="flex flex-col gap-1">


            <!-- Dashboard -->

            <a
                href="dashboard"
                data-nav
                class="sidebar-link">

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />

                </svg>

                <span class="nav-label">
                    Dashboard
                </span>

            </a>


            <!-- Projects -->

            <a
                href="projects"
                data-nav
                class="sidebar-link">

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />

                </svg>

                <span class="nav-label">
                    Projects
                </span>

            </a>


            <!-- My Tasks -->

            <a
                href="tasks"
                data-nav
                class="sidebar-link">

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5a3 3 0 016 0v1H9V5z" />

                </svg>

                <span class="nav-label">
                    My Tasks
                </span>

            </a>


            <!-- Documents -->

            <a
                href="documents"
                data-nav
                class="sidebar-link">

                <svg
                    class="h-5 w-5 shrink-0 opacity-70"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 2h9l5 5v15H6a2 2 0 01-2-2V4a2 2 0 012-2z" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14 2v6h6" />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 13h8M8 17h6" />

                </svg>

                <span class="nav-label">
                    Documents
                </span>

            </a>


        </div>


        <!-- Footer -->

        <div
            class="mt-auto sidebar-footer-inner border-t border-slate-200 p-3 dark:border-slate-800">

            <a
                href="../logout.php"
                class="sidebar-link text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-950/40">

                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                </svg>

                <span class="nav-label">
                    Logout
                </span>

            </a>

        </div>

    </nav>

</aside>