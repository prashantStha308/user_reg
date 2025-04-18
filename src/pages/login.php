<?php
    require_once "../controllers/_login.php";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login | User Registration </title>
    <link rel="stylesheet" href="../output.css">
</head>
<body>
    <?php
        $current_page = "login";
        include "../components/header.php"
    ?>

    <main id="login" >
        <div class="font-[sans-serif] min-h-screen grid px-4 mb-4 ">
            <div class=" flex flex-col items-center justify-center py-6 px-4  border border-gray-500 rounded-md bg-black/5 dark:bg-white/5 backdrop-blur-3xl">
                <!-- main form -->
                <div class="max-w-md w-full">
                    <div class="p-8 rounded-2xl bg-white dark:bg-gray-900 shadow">
                        <h2 class="text-gray-800 dark:text-white text-center text-2xl font-bold">Sign in</h2>
                        <!-- timeout message -->
                        <?php if( isset($_GET['timeout']) ) : ?>
                            <div>
                                <h3 class="text-center text-red-500"> Session timeout. Please login again to continue. </h3>
                            </div>
                        <?php endif; ?>
                        <!-- Form -->
                        <form class="mt-8 space-y-4" action="login.php" method="POST">
                            <!-- Error message -->
                            <?php if( isset($_SESSION['error']) )
                                echo "<p class='text-red-500'>{$_SESSION['error']}</p>";
                            ?>
                            <?php if( isset($_SESSION['password_message']) )
                                echo "<p class='text-red-500'>{$_SESSION['password_message']}</p>";
                            ?>

                            <!-- Email -->
                            <div>
                                <label class="text-gray-800 dark:text-white text-sm mb-2 block"> Email </label>
                                <div class="relative flex items-center">
                                    <input name="email" type="email" required class="login-inputs" placeholder="Enter email" <?= setReadonly() ?>  />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-4 h-4 absolute right-4" viewBox="0 0 24 24">
                                        <circle cx="10" cy="7" r="6" data-original="#000000"></circle>
                                        <path d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z" data-original="#000000"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="text-gray-800 dark:text-white text-sm mb-2 block">Password</label>
                                <div class="relative flex items-center">
                                    <input name="password" id="password" type="password" required class="login-inputs" placeholder="Enter password" <?= setReadonly() ?>   />

                                    <button type="button" id="togglePasswordView" class="flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class=" w-4 h-4 absolute right-4 cursor-pointer" viewBox="0 0 128 128">
                                            <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z" data-original="#000000"></path>
                                            <line x1="-10" y1="64" x2="138" y2="64" stroke="#333" stroke-width="14" stroke-linecap="round" id="pass-line" class="hidden" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                                <!-- forget passowrd and remember me -->
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <!-- Remember me? -->
                                <div class="flex items-center">
                                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 shrink-0 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" />
                                    <label for="remember-me" class="ml-3 block text-sm text-gray-800 dark:text-white">
                                        Remember me
                                    </label>
                                </div>

                                <!-- Forget Your Password? -->
                                <div class="text-sm">
                                    <!-- <a href="#home" class="text-purple-600 hover:underline font-semibold">
                                        Forgot your password?
                                    </a> -->
                                </div>
                            </div>

                            <!-- Sign In -->
                            <div class="!mt-8">
                                <input type="submit" class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none cursor-pointer" value="Sign In" />
                            </div>

                            <!-- Register Here -->
                            <p class="text-gray-800 dark:text-white text-sm !mt-8 text-center">Don't have an account? <a href="createUser.php" class="text-purple-600 hover:underline ml-1 whitespace-nowrap font-semibold">Register here</a></p>
                        </form>
                    </div>
                </div>
                <!-- background effects -->
                <div class="absolute -z-30 p-14 rounded-md bg-purple-400/80 dark:bg-gray-600 blur-xl" ></div>
                <div class="absolute bottom-10 right-14 -z-30 px-56 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl" ></div>
                <div class="absolute top-5 left-4 -z-30 px-44 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl" ></div>
            </div>
        </div>
    </main>

    <!-- scripts -->
    <script src="../controllers/script.js"></script>
</body>
</html>