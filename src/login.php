<?php

session_start();

include('conn.php');

if(isset($_SESSION['admin'])){

    header('location:index');

    exit;
}

$error = '';

if(isset($_POST['login'])){

    $email = trim($_POST['email']);

    $password = trim($_POST['password']);

    $stmt = $conn->prepare("
        SELECT *
        FROM admins
        WHERE email = :email
    ");

    $stmt->execute([
        ':email' => $email
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if($admin){

        if($password == $admin['password']){

            $_SESSION['admin'] = $admin;

            header('location:index');

            exit;

        }else{

            $error = 'Invalid Password';
        }

    }else{

        $error = 'Invalid Email';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="preconnect"
        href="https://fonts.googleapis.com" />

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet"
        href="../dist/css/output.css" />

</head>

<body class="min-h-screen bg-neutral-950 flex items-center justify-center relative overflow-hidden">

    <!-- Background Layer 1 -->

    <div class="absolute inset-0 bg-gradient-to-tr from-neutral-900 via-neutral-950 to-black"></div>

    <!-- Background Accent Strip -->

    <div class="absolute left-0 top-0 h-full w-1/3 bg-gradient-to-b from-indigo-600/20 to-transparent"></div>

    <!-- Background Light Beam -->

    <div class="absolute -top-40 right-[-200px] w-[600px] h-[600px] bg-indigo-500/10 blur-[160px] rounded-full"></div>

    <!-- Main Layout Container -->

    <div class="relative w-full max-w-5xl mx-auto grid md:grid-cols-2 rounded-2xl overflow-hidden shadow-[0_40px_120px_-20px_rgba(0,0,0,0.6)]">

        <!-- Left Panel -->

        <div class="hidden md:flex flex-col justify-center px-16 py-20 bg-neutral-900/60 backdrop-blur-xl border-r border-white/5">

            <h1 class="text-4xl font-semibold text-white leading-tight">

                Welcome back.

            </h1>

            <p class="mt-6 text-neutral-400 text-sm max-w-sm">

                Access your workspace and continue building without interruption.

            </p>

            <div class="mt-12 h-[1px] w-16 bg-indigo-500"></div>

        </div>

        <!-- Right Panel -->

        <div class="bg-neutral-900/80 backdrop-blur-xl p-12 md:p-16">

            <h2 class="text-2xl font-medium text-white mb-10 tracking-tight">

                Sign in

            </h2>

            <?php if($error != ''){ ?>

                <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">

                    <?php echo $error; ?>

                </div>

            <?php } ?>

            <form method="POST"
                class="space-y-8">

                <div>

                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="Email"
                        class="w-full bg-transparent border-b border-neutral-700 text-white placeholder-neutral-500 py-3 focus:outline-none focus:border-indigo-500 transition">

                </div>

                <div>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Password"
                        class="w-full bg-transparent border-b border-neutral-700 text-white placeholder-neutral-500 py-3 focus:outline-none focus:border-indigo-500 transition">

                </div>

                <button
                    type="submit"
                    name="login"
                    class="w-full py-3 mt-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 active:scale-[0.99] transition duration-200">

                    Login

                </button>

            </form>

        </div>

    </div>

</body>

</html>