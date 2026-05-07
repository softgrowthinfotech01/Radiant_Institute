<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="../dist/css/output.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js" defer></script>
</head>

<body>
    <div class="bg-green-200 min-h-screen flex items-center">
        <div class="bg-white p-10 md:w-2/3 lg:w-1/2 mx-auto rounded">
            <form id="loginForm">

            <h1 class="text-4xl py-3 text-center font-bold">Login</h1>

                <div class="flex items-center mb-5">
                    <label for="name" class="w-20 inline-block text-right mr-4 text-gray-500 text-gray-500">Username</label>
                    <input name="email" id="email" type="text" placeholder="Your Username..." class="border-b-2 border-gray-400 flex-1 py-2 placeholder-gray-300 outline-none focus:border-green-400">
                </div>

                <div class="flex items-center mb-10">
                    <label for="twitter" class="w-20 inline-block text-right mr-4 text-gray-500 text-gray-500">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter Password..." class="border-b-2 border-gray-400 flex-1 py-2 placeholder-gray-300 outline-none focus:border-green-400">
                </div>
                <div class="text-right">
                    <button class="py-3 px-8 bg-green-500 text-green-100 font-bold rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
<script>
    const API_BASE = "http://127.0.0.1:8000/api";

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            const response = await fetch(`${API_BASE}/login`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    email,
                    password
                })
            });

            const data = await response.json();

            if (!response.ok) {
                alert(data.message || "Login Failed");
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE TOKEN
            |--------------------------------------------------------------------------
            */

            localStorage.setItem("admin_token", data.token);
            localStorage.setItem("admin_user", JSON.stringify(data.user));

            /*
            |--------------------------------------------------------------------------
            | REDIRECT
            |--------------------------------------------------------------------------
            */

            window.location.href = "index.php";

        } catch (error) {
            console.error(error);
            alert("Server Error");
        }
    });

});
</script>