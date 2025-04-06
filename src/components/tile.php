<a href="<?= "dashboard.php?username=" . urlencode($username) ?>" >
    <section id="user_tile" class="group relative overflow-hidden rounded-lg">
        <div class="text-2xl text-gray-800 dark:text-white bg-white/55 dark:bg-black/35 backdrop-blur-3xl border border-gray-500 rounded-lg px-8 py-5 grid shadow-lg hover:border-purple-500 transition-all duration-300 ease-in-out z-20 hover:border-l-pink-300 hover:border-r-purple-700 ">
            <div class=" grid gap-4 whitespace-normal break-all max-w-[250px]">
                <h1 class="text-sm" > <span class="font-bold text-purple-600 dark:text-white">Username:</span> <?= htmlspecialchars($username) ?> </h1>
                <h4 class="text-sm"> <span class="font-bold text-purple-600 dark:text-white">Email:</span> <?= htmlspecialchars($email) ?> </h4>
            </div>
        </div>
        <!-- normal effect on dark mode -->
        <div class="absolute hidden dark:block w-full h-full inset-0 -z-10 bg-gradient-to-bl from-neutral-900 to-transparent blur-sm group-hover:translate-x-18 group-hover:-translate-y-18 transition-all duration-500 ease-out "></div>
        <!-- dark hover effect -->
        <div class="absolute hidden dark:block bottom-0 -translate-x-18 translate-y-18 group-hover:translate-x-0 group-hover:translate-y-0 group-active:translate-x-0 group-active:translate-y-0 transition-all duration-500 ease-out p-10 rounded-full bg-gradient-to-tr from-purple-500 via-pink-500 to-yellow-300 dark:blur-md -z-10"></div>
        <!-- Light hover effect -->
        <div class="absolute dark:hidden inset-0 -translate-x-18 translate-y-34 group-hover:translate-x-0 group-hover:translate-y-0 group-active:translate-x-0 group-active:translate-y-0 transition-all duration-500 ease-out bg-gradient-to-tr from-purple-600 via-pink-500 to-yellow-300 blur-sm -z-10"></div>

    </section>
</a>