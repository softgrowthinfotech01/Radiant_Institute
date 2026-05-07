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
              <p class="text-sm font-medium text-brand-600 dark:text-brand-400">Forms</p>
              <h2 class="text-display-sm text-slate-900 dark:text-white">Add Course</h2>
              <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Insert a New Course name</p>
            </div>

           <form id="courseForm">
             <div class="space-y-6">
              <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card dark:border-slate-800 dark:bg-slate-900">
                <h3 class="text-base font-semibold">Course</h3>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Course Name<input name="title" type="text" placeholder="Course Name..." class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Course Description<input name="description" type="text" placeholder="Course Description..." class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none ring-brand-500/25 focus:border-brand-400 focus:ring-4 dark:border-slate-700 dark:bg-slate-950" /></label>
                </div>
              </section>
             
              <div class="flex flex-wrap justify-end gap-2 pb-8">
                <button type="reset" class="btn btn-secondary">Discard</button>
                <button type="submit" class="btn btn-primary">Save Course</button>
              </div>
            </div>
           </form>
          </div>
        </main>
      </div>
    </div>

    <script src="../dist/js/app.js"></script>

    <script>

document.getElementById('courseForm').addEventListener('submit', async function(e){

    e.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('http://127.0.0.1:8000/api/courses', {
        method:'POST',
        headers:{
            'Authorization':'Bearer '+localStorage.getItem('admin_token')
        },
        body:formData
    });

    const data = await response.json();

    alert('Course Added Successfully');

    this.reset();
});

</script>
  </body>
</html>
