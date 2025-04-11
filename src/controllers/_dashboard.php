<?php
    require_once "auth.php";

    // assume user is not guest initialy
    $isGuest = false;
    // Sanitize username from GET
    $username = isset($_GET['username']) ? trim(htmlspecialchars($_GET['username'], ENT_QUOTES, 'UTF-8')) : null;
    $current_user = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    // handle logout
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        global $username;
        if ( logout($username) ) {
            unset_errors();
            $isGuest = true;
            header('Location: login.php');
            exit();
        }
    }

    // handle delete account
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
            if (delete_user($username)) {
                unset_errors();
                $isGuest = true;
                header('Location:index.php');
                exit();
            }
    }

    // handle update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
        $username = trim(htmlspecialchars($_POST['username']));
        $email = trim(htmlspecialchars($_POST['email']));
        $description = !empty(trim($_POST['description'])) ? $_POST['description'] : "No description";
        
        // Validate form data
        if (validate_form($username, $email)) {
            // Attempt to update user info
            $res = update_user($username, $email, $description);
            if ($res) {
                unset_errors();
                // Redirect to the dashboard with the updated username
                header("Location: dashboard.php?username=" . urlencode($username));
                exit();
            } else {
                header("Location:dashboard.php");
            }
        } else {
            $_SESSION['error'] = "Invalid form data. Please check your inputs.";
            $_SESSION['error_time'] = time();
            header("Location: dashboard.php?username=" . urlencode($current_user));
            exit();
        }
    }
?>