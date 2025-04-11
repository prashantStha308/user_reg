<?php
    function is_active( $page ){
        global $current_page;
        return $page === $current_page ? "text-purple-500" : '';
    }
?>
<script>
    const handleClick = () => {
        const menu = document.getElementById("menu");

        if( menu.classList.contains("hidden") ){
            menu.classList.remove("hidden");
        }else{
            menu.classList.add("hidden");
        }
    }
</script>
<header class="sticky top-0 z-50 lg:px-16 px-4 bg-black/5 dark:bg-white/5 backdrop-blur-xl flex flex-wrap items-center py-4 shadow-lg my-4 mx-2 border border-gray-500 rounded-md">
    <div class="flex-1 flex justify-between items-center ">
        <a href="index.php" class="text-xl font-bold text-purple-500 hover:text-pink-400 transition-all duration-200 ease-in-out"> User Registration </a>
    </div>

    <label for="menu-toggle" class="pointer-cursor md:hidden block" onclick="handleClick()">
        <svg class="fill-current text-gray-900 dark:text-white"
            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
            <title>menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
        </svg>
    </label>
    <input class="hidden" type="checkbox" id="menu-toggle" />

    <div class="hidden md:flex md:items-center md:w-auto w-full" id="menu">
        <nav>
            <ul class="sm:grid md:flex items-center justify-between text-base text-gray-700 dark:text-gray-200 pt-4 md:pt-0">
                <li><a class="list-text <?= is_active("home") ?> " href="index.php">Home</a></li>
                <li><a class="list-text <?= is_active("dashboard") ?> " href="dashboard.php">Dashboard</a></li>
                <li><a class="list-text <?= is_active("login") ?>" href="login.php"> Log in </a></li>
                <li><a class="list-text <?= is_active("register") ?> " href="register.php"> Register </a></li>
            </ul>
        </nav>
    </div>
</header>