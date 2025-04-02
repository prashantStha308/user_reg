<?php
    require_once "./_utils/config.php";
    require_once "./_utils/helper.php";

    function create_user( $username , $email , $password ){
        try{
            // make a pdo
            $db = new pdo( 'mysql:host=localhost;dbname=user_management' , USER , PASSWORD );
            // enable error handeling
            $db->setAttribute( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );

            $checkQuery = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            bind_param( $checkQuery , ":email" , $email );
            $checkQuery->execute();
            $emailExist = $checkQuery->fetchColumn();

            if( $emailExist > 0 ){
                echo "Error: Email already exists. Please use a different email";
                return false;
            }

            $hashed_password = password_hash($password , PASSWORD_DEFAULT);

            $query = $db->prepare("INSERT INTO users( username , email , password ) VALUES ( :username , :email , :password )");
            bind_param( $query , ":username" , $username );
            bind_param( $query , ":email" , $email );
            bind_param( $query , ":password" , $hashed_password );
            $query->execute();
            return true;
        }catch( PDOException $e ){
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if( create_user( $username , $email , $password ) ){
            session_regenerate_id(true);
            header("Location:login.php");
            exit();
        }else{
            echo "User registration failed.";
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

    <section id="signup">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Create an account
                </h1>
                <form class="space-y-4 md:space-y-6" action="createUser.php" method="POST">
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
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3  dark:bg-gray-700 dark:border-gray-600 dark:ring-offset-gray-800" required="">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font hover:underline " href="#">Terms and Conditions</a></label>
                        </div>
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