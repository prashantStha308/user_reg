<?php
    require_once "./_utils/config.php";
    require_once "./_utils/helper.php";

    if( !empty($_SESSION['error']) ){
        $_SESSION['error'] = null;
    }

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if( create_user( $username , $email , $password ) ){
            session_regenerate_id(true);
            $_SESSION['error'] = null;
            header("Location:login.php");
            exit();
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | User Registration</title>
    <link rel="stylesheet" href="./output.css">
</head>
<body>

    <?php
        $current_page = "create user";
        include "./_components/header.php"
    ?>

    <section id="signup" class="md:min-h-screen " >
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-autolg:py-0">

        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Create an account
                </h1>
                <form class="space-y-4 md:space-y-6" action="createUser.php" method="POST">
                    <div>
                    <?php if (!empty($_SESSION['error'])) { echo "<p class='text-red-500'>{$_SESSION['error']}</p>"; } ?>
                    </div>
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username" id="username" class="signup-inputs" placeholder="Enter username" required="">
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Email</label>
                        <input type="email" name="email" id="email" placeholder="name@company.com" class="signup-inputs" required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="signup-inputs" required="">
                    </div>
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea name="description" id="description" placeholder="Describe yourseft" class="signup-inputs resize-none "></textarea>
                    </div>
                    <button type="submit" class="w-full text text-white bg-purple-600 hover:bg-purple-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:">Create an account</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="login.php" class="font hover:underline ">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    </section>


</body>
</html>