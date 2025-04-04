
<?php
  require_once "./_utils/config.php";
  require_once "./_utils/helper.php";
  
  $isGuest = false;
  // Sanitize username from GET
  $username = isset($_GET['username']) ? trim(htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8')) : null;

  // handle logout
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout']) ) {
    if (auth_current($username)) {
      logout();
      $isGuest = true;
      header('Location: index.php');
      exit;
    }
  }

  // handle delete account
  if( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete']) ){
    if( validate_form( $_POST['username'] , $_POST['email'] ) && auth_current($username) ){
      if( delete_user($username) ){
        $isGuest = true;
        header('Location:index.php');
        exit();
      }else{
        $model = [
          'status' => true,
          'title' => "Failure in Account Deletion",
          'message' => "Some error occured while deleting account",
          'href' => 'index.php',
          'btnText' => 'Go Home'
        ];
      }
    }
  }

  // handle update
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $formValid = validate_form($_POST['username'], $_POST['email']);
    $isAuthorized = auth_current($username);
    
    if ($formValid && $isAuthorized) {
        if (update_user($_POST['username'], $_POST['email'], $_POST['description'])) {
            header("Location: dashboard.php?username=" . urlencode($_POST['username']));
            exit();
        } else {
            $model = [
                'status' => true,
                'title' => "Update Failed",
                'message' => isset($_SESSION['error']) ? $_SESSION['error'] : "Failed to update account information.",
                'href' => 'dashboard.php?username=' . urlencode($username),
                'btnText' => 'Try again'
            ];
            unset($_SESSION['error']);
        }
    } else {
        $model = [
            'status' => true,
            'title' => "Form Error",
            'message' => "Form validation failed or you're not authorized to edit this profile.",
            'href' => 'dashboard.php?username=' . urlencode($username),
            'btnText' => 'Back to Dashboard'
        ];
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard | User Registration  </title>
    <link rel="stylesheet" href="./output.css">
</head>
<body>
  <?php
      $current_page = "dashboard";
      include "./_components/header.php";

      if (!isset($username)) {
        if (isset($_SESSION['username'])) {
          header("Location:dashboard.php?username={$_SESSION['username']}");
        } else {
          $model = [
            'status' => true,
            'title' => "Login required",
            'message' => "Please login to view your dashboard",
            'href' => 'login.php',
            'btnText' => 'Login'
          ];
        }
      } else {
        $user = get_user($username);
        
        if (!$user || (!isset($_SESSION['username']) || $user['username'] !== $_SESSION['username'])) {
          $isGuest = true;
        }
        
        if (!$user) {
          echo "user not found";
          exit();
        }
      }
  ?>

  <?php if ( isset($model) && $model['status'] ) : ?>
    <?php include "./_components/model.php"; ?>
  <?php else : ?>
    <div class="min-h-screen grid px-4 mb-4">
      <div class="flex border border-gray-500 rounded-md bg-black/5 dark:bg-white/5 backdrop-blur-xl">
        <!-- main form -->
        <main class="flex-1 p-8">
          <div class="max-w-3xl mx-auto ">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200 "><span class="text-purple-500"> <?= htmlspecialchars($username) ?>'s</span> Dashboard</h1>

            <form id="dashboard_form"  method="POST" action="dashboard.php?username=<?= urlencode($username) ?>">
              <!-- User Id -->
              <div class="mb-4">
                <p for="userId" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">User ID: <?= htmlspecialchars($user['user_id']) ?> </p>
              </div>
              <div class="mb-4">
                <label for="username" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" readonly class="dashboard-inputs" />
              </div>
              <div class="mb-4">
                <label for="email" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly class="dashboard-inputs" />
              </div>
              <div class="mb-6">
                <label for="description" class="block text-gray-800 dark:text-gray-200 text-sm mb-2">Description</label>
                <textarea id="description" name="description" readonly class="dashboard-inputs"><?= htmlspecialchars($user['description'] ?? "No description") ?></textarea>
              </div>

              <div class="flex justify-end gap-4">
                <?php if (!$isGuest): ?>
                  <button type="button" id="edit" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600" > Edit </button>
                  <button type="submit" id="finishEdit" name="edit" class="hidden bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600"> Finish Edit </button>
                  <button type="button" id="cancleEdit" class="hidden bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" > Cancle Edit </button>
                  <button type="submit" name="logout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                  <button type="submit" name="delete" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-800">Delete Account</button>
                <?php elseif( isset($_SESSION['username']) ): ?>
                  <a href="dashboard.php?username=<?= $_SESSION['username'] ?>">
                    <button type="button" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-800">View your dashboard</button>
                  </a>
                <?php endif ?>
              </div>
            </form>
          </div>
        </main>
        <!-- background effects -->
        <div class="absolute bottom-10 right-14 -z-30 px-56 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl" ></div>
        <div class="absolute top-5 left-54 -z-30 px-44 py-24 rounded-md bg-purple-400/80 dark:bg-gray-600/90 blur-3xl" ></div>
      </div>
    </div>
  <?php endif; ?> 
  <script src="./_utils/script.js"></script>
</body>
</html>
