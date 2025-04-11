<?php
    require_once "../controllers/_dashboard.php";
    if( !isset($_SESSION['username']) ){
        include "../components/loginReq.php";
        return;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard | User Registration </title>
    <link rel="stylesheet" href="../output.css">
</head>

<body>
    <?php
        $current_page = "dashboard";
        include "../components/header.php";

        if (!isset($username)) { //if _GET['username'] doesn't exist
            if (isset($current_user)) { //if a user has logged in
                header("Location:dashboard.php?username={$current_user}");
            } else {
                set_model("Login required", "Please login to view your dashboard", "login.php", "Login");
            }
        } else {
            $user = get_user_data( 'username' , $username );
            $userData = $user['data'] ?? null;

            // if userData is not set, user doesn't exist
            if ( !isset($userData) ) {
                set_model("Invalid User", "This user doesn't exit.", "index.php", "Home");
            }
            // check if current user is guest or not
            if( !isset($current_user) || $userData['username'] !== $current_user ){
                $isGuest = true;
            }else{
                $isGuest = false;
            }
        }
    ?>

    <?php if (isset($model) && $model['status']): ?>
        <?php include "../components/model.php"; ?>
    <?php else: ?>
        <div class="min-h-screen grid px-4 mb-4">
            <div
                class="flex justify-center items-center border border-gray-500 rounded-md bg-black/5 dark:bg-white/5 backdrop-blur-3xl">
                <!-- Main -->
                <main class="flex-1 p-8">
                    <div class="max-w-3xl mx-auto ">
                        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200 "><span class="text-purple-500">
                                <?= htmlspecialchars($username) ?>'s</span> Dashboard</h1>

                        <div>
                            <!-- error indicator -->
                            <?php if( isset($_SESSION['error']) )
                                echo "<p class='text-red-500'>{$_SESSION['error']}</p>";
                            ?>
                        </div>
                        <!-- Main form  -->
                        <form id="dashboard_form" method="POST" action="dashboard.php?username=<?= urlencode($username) ?>">
                            <!-- User Id -->
                            <div class="mb-4">
                                <p class="block text-gray-800 dark:text-gray-200 text-sm mb-2">User ID:
                                    <?= htmlspecialchars($userData['user_id']) ?>
                                </p>
                            </div>
                            <!-- created at -->
                            <div class="mb-4">
                                <p class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Created at:
                                    <?= htmlspecialchars(explode( ' ' , $userData['created_at'] )[0] ) ?>
                                </p>
                            </div>
                            <!-- username -->
                            <div class="mb-4">
                                <label for="username"
                                    class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Username</label>
                                <input type="text" name="username" id="username"
                                    value="<?= htmlspecialchars($userData['username']) ?>" class="dashboard-inputs" pattern="^[a-zA-Z0-9_-]{3,50}$" readonly required/>
                            </div>
                            <!-- email -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Email</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($userData['email']) ?>" class="dashboard-inputs" required readonly />
                            </div>
                            <!-- buttons -->
                            <div class="flex justify-end gap-4">
                                <?php if (!$isGuest): ?>
                                    <button type="button" id="edit"
                                        class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 focus:bg-purple-800 ">
                                        Edit </button>
                                    <button type="submit" id="finishEdit" name="edit"
                                        class="hidden bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 focus:bg-purple-800 "> Finish
                                        Edit </button>
                                    <button type="button" id="cancelEdit"
                                        class="hidden bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"> Cancel Edit
                                    </button>
                                    <button type="submit" name="logout" id="logout"
                                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                                    <button type="button" id="deleteBtn"
                                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-800">Delete Account</button>
                                <?php elseif (isset($current_user)): ?>
                                    <a href="dashboard.php?username=<?= $current_user ?>">
                                        <button type="button"
                                            class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-800">View your
                                            dashboard</button>
                                    </a>
                                <?php endif ?>
                            </div>
                        </form>
                    </div>
                </main>
                <!-- background effects -->
                <div class="absolute bottom-10 right-14 -z-30 px-56 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl"></div>
                <div class="absolute top-5 left-4 -z-30 px-44 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl"></div>
            </div>
        </div>
    <?php endif; ?>
    <!-- scripts -->
    <script src="../controllers/script.js"></script>
</body>

</html>