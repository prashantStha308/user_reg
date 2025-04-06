<?php
    require_once "../controllers/auth.php";

    // unset session errors and $model on new page visit or page reload
    if( isset($model) || isset($_SESSION['error']) ){
        unset_errors();
    }

    // handle user creation
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $description = !empty($_POST['description']) ? $_POST['description'] : "No description";
        if( validate_form( $username , $email , $password ) ){
            if( create_user( $username , $email , $password , $description ) ){
                session_regenerate_id(true);
                unset_errors();
                header("Location:login.php");
                exit();
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | User Registration</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body>

    <?php
        $current_page = "create user";
        include "../components/header.php"
    ?>

    <section id="signup" class="md:min-h-screen grid px-4 mb-4" >
    <div class="flex flex-col items-center justify-center px-6 py-8 border border-gray-500 rounded-md bg-black/5 dark:bg-white/5 backdrop-blur-3xl">
        <!-- main form -->
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-900 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Create an account
                </h1>
                <form class="space-y-4 md:space-y-6" action="createUser.php" method="POST">
                    <!-- error messages -->
                    <div>
                        <?php if (isset($_SESSION['error'])) { echo "<p class='text-red-500'>{$_SESSION['error']}</p>"; } ?>
                    </div>
                    <!-- username -->
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="username" class="signup-inputs" placeholder="Enter username" required="">
                    </div>
                    <!-- email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Email</label>
                        <input type="email" name="email" id="email" placeholder="name@company.com" class="signup-inputs" required="">
                    </div>
                    <!-- passowrd -->
                    <div>
                        <label class="text-gray-800 dark:text-white text-sm mb-2 block">Password</label>
                        <div class="relative flex items-center">
                            <input type="password" name="password" id="password" placeholder="••••••••" class="signup-inputs" required="">

                            <button type="button" id="togglePasswordView"  class="flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class=" line-through w-4 h-4 absolute right-4 cursor-pointer" viewBox="0 0 128 128">
                                    <path d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z" data-original="#000000"></path>
                                    <line x1="-10" y1="64" x2="138" y2="64" stroke="#333" stroke-width="14" stroke-linecap="round" id="pass-line" class="hidden" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- description -->
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea name="description" id="description" placeholder="Describe yourself" class="signup-inputs resize-none "></textarea>
                    </div>
                    <!-- footer -->
                    <button type="submit" class="w-full text text-white bg-purple-600 hover:bg-purple-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:">Create an account</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="login.php" class="font hover:underline ">Login here</a>
                    </p>
                </form>
            </div>
        </div>
        <!-- background effects -->
        <div class="absolute -z-30 p-14 rounded-md bg-purple-400/80 dark:bg-gray-600 blur-xl" ></div>
        <div class="absolute bottom-10 right-14 -z-30 px-56 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl" ></div>
        <div class="absolute top-5 left-2 -z-30 px-44 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl" ></div>
    </div>
    </section>

    <!-- scripts -->
    <script src="../controllers/script.js"></script>

</body>
</html>