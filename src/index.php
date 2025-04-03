<?php
    require_once "./_utils/config.php";
    require_once "./_utils/helper.php";
    $users = get_user() ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Home | User Registration  </title>
    <link rel="stylesheet" href="./output.css">
</head>
<body>
    <?php
        $current_page = "home";
        include "./_components/header.php";
    ?>

    <section id="home">
        <div class="p-4 grid gap-4">
            <div>
                <h1 class="text-left text-xl md:text-2xl lg:text-3xl text-black dark:text-gray-200 font-bold"> Registered Users in the database </h1>
                <?php if( isset($_SESSION['username']) && !empty($_SESSION['username']) ): ?>
                    <h3 class="text-left text-sm md:text-lg xl:text-xl text-black dark:text-gray-400"> Currently logged in as : <span class="text-purple-400 font-bold hover:text-pink-500 transition-all duration-150 ease-in-out"> <?= $_SESSION['username'] ?> </span> </h3>
                <?php endif ?>
            </div>

            <!-- Display Users if Available -->
            <?php if( isset($users) && count($users) > 0 ): ?>
                <div class="relative border border-gray-500 bg-black/5 dark:bg-white/5 backdrop-blur-3xl rounded-2xl p-4 min-h-[calc(100vh-40vh)] overflow-hidden" >
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-start ">
                        <?php foreach( $users as $user ): ?>
                            <?php
                                extract($user);
                                include "./_components/tile.php";
                            ?>
                        <?php endforeach; ?>
                        <!-- background effects -->
                        <div class="absolute -z-30 p-14 rounded-md bg-gray-700 blur-xl" ></div>
                        <div class="absolute bottom-40 right-14 -z-30 px-56 py-24 rounded-md bg-gray-700/60 blur-2xl" ></div>

                    </div>
                <?php else : ?>
                    <div class="min-h-[calc(100vh-20vh)] grid justify-center items-center">
                        <span class="text-center text-xl md:text-2xl text-black dark:text-white"> No Users present in database </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

</body>
</html>
