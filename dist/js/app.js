(function () {
  var STORAGE_THEME = "admin-theme";
  var STORAGE_SIDEBAR = "admin-sidebar-collapsed";

  function qs(sel, root) {
    return (root || document).querySelector(sel);
  }
  function qsa(sel, root) {
    return [].slice.call((root || document).querySelectorAll(sel));
  }

  function currentPageFile() {
    var path = window.location.pathname.split("/").pop();
    if (!path || path === "") return "index.html";
    return path;
  }

  function initSidebar() {
    var sidebar = qs("#sidebar");
    var collapseBtn = qs("#sidebar-collapse");
    var mobileOpen = qs("#mobile-menu-open");
    var mobileClose = qs("#mobile-menu-close");
    var backdrop = qs("#mobile-backdrop");

    function applyCollapsed(collapsed) {
      if (!sidebar) return;
      sidebar.classList.toggle("sidebar-collapsed", collapsed);
      try {
        localStorage.setItem(STORAGE_SIDEBAR, collapsed ? "1" : "0");
      } catch (e) {}
    }

    try {
      applyCollapsed(localStorage.getItem(STORAGE_SIDEBAR) === "1");
    } catch (e) {
      applyCollapsed(false);
    }

    collapseBtn &&
      collapseBtn.addEventListener("click", function () {
        applyCollapsed(!sidebar.classList.contains("sidebar-collapsed"));
      });

    function openMobile() {
      if (sidebar) {
        sidebar.classList.remove("-translate-x-full");
        sidebar.classList.add("translate-x-0");
      }
      if (backdrop) {
        backdrop.classList.remove("hidden");
      }
      document.body.classList.add("overflow-hidden");
    }
    function closeMobile() {
      if (sidebar) {
        sidebar.classList.add("-translate-x-full");
        sidebar.classList.remove("translate-x-0");
      }
      if (backdrop) {
        backdrop.classList.add("hidden");
      }
      document.body.classList.remove("overflow-hidden");
    }

    mobileOpen && mobileOpen.addEventListener("click", openMobile);
    mobileClose && mobileClose.addEventListener("click", closeMobile);
    backdrop && backdrop.addEventListener("click", closeMobile);

    window.addEventListener("resize", function () {
      if (window.innerWidth >= 1024) closeMobile();
    });
  }

  function initDarkMode() {
    var toggle = qs("#theme-toggle");
    var html = document.documentElement;

    function apply(dark) {
      html.classList.toggle("dark", dark);
      try {
        localStorage.setItem(STORAGE_THEME, dark ? "dark" : "light");
      } catch (e) {}
    }

    var saved = null;
    try {
      saved = localStorage.getItem(STORAGE_THEME);
    } catch (e) {}

    var prefersDark =
      window.matchMedia &&
      window.matchMedia("(prefers-color-scheme: dark)").matches;

    if (saved === "dark") apply(true);
    else if (saved === "light") apply(false);
    else apply(prefersDark);

    toggle &&
      toggle.addEventListener("click", function () {
        apply(!html.classList.contains("dark"));
      });
  }

  function initActiveNav() {
    var page = currentPageFile();
    qsa("[data-nav]").forEach(function (el) {
      var href = el.getAttribute("href");
      if (!href) return;
      var target = href.split("/").pop();
      if (target === page) {
        el.classList.add("sidebar-link-active");
      }
    });
  }

  function initTableSearchAndFilters() {
    var input = qs("[data-table-search]");
    var tbody = qs("[data-table-body]");
    if (!tbody) return;

    var roleSel = qs("#filter-role");
    var statusSel = qs("#filter-status");

    function apply() {
      var q = input ? input.value.trim().toLowerCase() : "";
      var role = roleSel ? roleSel.value : "";
      var status = statusSel ? statusSel.value : "";
      tbody.querySelectorAll("tr").forEach(function (row) {
        var text = row.textContent.toLowerCase();
        var okSearch = !q || text.indexOf(q) !== -1;
        var okRole = !role || row.getAttribute("data-role") === role;
        var okStatus = !status || row.getAttribute("data-status") === status;
        row.classList.toggle("hidden", !(okSearch && okRole && okStatus));
      });
    }

    input && input.addEventListener("input", apply);
    roleSel && roleSel.addEventListener("change", apply);
    statusSel && statusSel.addEventListener("change", apply);
  }

  document.addEventListener("DOMContentLoaded", function () {
    initSidebar();
    initDarkMode();
    initActiveNav();
    initTableSearchAndFilters();
  });

  window.AdminPanel = {
    currentPageFile: currentPageFile,
  };
})();
