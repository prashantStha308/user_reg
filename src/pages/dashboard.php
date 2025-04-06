<?php
    require_once "../controllers/auth.php";

    // unset error on page reload
    if( isset($model) || isset($_SESSION['error']) ){
        unset_errors();
    }

    // assume user is not guest initialy
    $isGuest = false;
    // Sanitize username from GET
    $username = isset($_GET['username']) ? trim(htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8')) : null;
    $current_user = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    // handle logout
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        global $username;
        if ( isset($username) && auth_user($username) ) {
            unset_errors();
            logout();
            $isGuest = true;
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Authentication Error: You are not the owner of this account";
        }
    }

    // handle delete account
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
        if (validate_form($_POST['username'], $_POST['email']) && auth_user($username)) {
            if (delete_user($username)) {
                unset_errors();
                $isGuest = true;
                header('Location:index.php');
                exit();
            } else {
                $_SESSION['error'] = "Failed to delete your account. Please try again";
            }
        } else {
            $_SESSION['error'] = "Failed to delete your account. Please try again";
        }
    }

    // handle update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
        if( validate_form($_POST['username'], $_POST['email']) ){
            if (update_user($_POST['username'], $_POST['email'], $_POST['description'])) {
                unset_errors();
                header("Location: dashboard.php?username=" . urlencode($_POST['username']));
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
    <title> Dashboard | User Registration </title>
    <link rel="stylesheet" href="../output.css">
</head>

<body>
    <?php
    $current_page = "dashboard";
    include "../components/header.php";

    if (!isset($username)) {
        if (isset($current_user)) {
            unset_errors();
            header("Location:dashboard.php?username={$current_user}");
        } else {
            set_model("Login required", "Please login to view your dashboard", "login.php", "Login");
        }
    } else {
        $user = get_user($username);
        if (!$user || (!isset($current_user) || $user['username'] !== $current_user)) {
            $isGuest = true;
        }else{
            $isGuest = false;
        }
        if ( $user === false ) {
            set_model("Invalid User", "This user doesn't exit.", "index.php", "Home");
        }
    }
    ?>

    <?php if (isset($model) && $model['status']): ?>
        <?php include "../components/model.php"; ?>
    <?php else: ?>
        <div class="min-h-screen grid px-4 mb-4">
            <div
                class="flex justify-center items-center border border-gray-500 rounded-md bg-black/5 dark:bg-white/5 backdrop-blur-3xl">
                <!-- main form -->
                <main class="flex-1 p-8">
                    <div class="max-w-3xl mx-auto ">
                        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200 "><span class="text-purple-500">
                                <?= htmlspecialchars($username) ?>'s</span> Dashboard</h1>

                        <div>
                            <?php if (!empty($_SESSION['error'])) {
                                echo "<p class='text-red-500'>{$_SESSION['error']}</p>";
                            } ?>
                        </div>

                        <form id="dashboard_form" method="POST" action="dashboard.php?username=<?= urlencode($username) ?>">
                            <!-- User Id -->
                            <div class="mb-4">
                                <p for="userId" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">User ID:
                                    <?= htmlspecialchars($user['user_id']) ?>
                                </p>
                            </div>
                            <!-- username -->
                            <div class="mb-4">
                                <label for="username"
                                    class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Username</label>
                                <input type="text" name="username" id="username"
                                    value="<?= htmlspecialchars($user['username']) ?>" readonly class="dashboard-inputs" />
                            </div>
                            <!-- email -->
                            <div class="mb-4">
                                <label for="email" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Email</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
                                    readonly class="dashboard-inputs" />
                            </div>
                            <!-- description -->
                            <div class="mb-6">
                                <label for="description"
                                    class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Description</label>
                                <textarea id="description" name="description" readonly
                                    class="dashboard-inputs resize-none"><?= htmlspecialchars($user['description'] ?? "No description") ?></textarea>
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
                                    <button type="submit" name="logout"
                                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                                    <button type="submit" name="delete"
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
                <div
                    class="absolute bottom-10 right-14 -z-30 px-56 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl">
                </div>
                <div class="absolute top-5 left-4 -z-30 px-44 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl"></div>
            </div>
        </div>
    <?php endif; ?>
    <!-- scripts -->
    <script src="../controllers/script.js"></script>
</body>

</html>