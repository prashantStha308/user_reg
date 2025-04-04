<?php
    global $model;
?>
<div class="absoulte px-4 min-h-screen w-full grid">
    <div class="flex justify-center items-center min-h-full bg-black/5 dark:bg-white/5 backdrop-blur-3xl z-50 shadow-md border border-gray-500 rounded-md overflow-y-hidden">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-900 rounded-lg shadow-xl">
            <h1 class="text-2xl font-bold text-purple-400 mb-4"><?= $model['title'] ?></h1>
            <p class="text-gray-700 dark:text-gray-200 text-left mb-4"><?= $model['message'] ?></p>
            
            <?php if( $model['btnText'] ) : ?>
                <div class="text-right">
                    <a href="<?= $model['href'] ? $model['href'] : "#" ?>"
                    class="inline-block bg-purple-400 hover:bg-purple-500 py-2 px-4 text-white rounded-md font-semibold uppercase text-sm transition-all duration-150 ease-in-out">
                        <?= $model['btnText'] ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <!-- background effects -->
        <div class="absolute -z-30 p-14 rounded-md bg-purple-500 dark:bg-gray-600 blur-xl" ></div>
        <div class="absolute bottom-10 right-14 -z-30 px-56 py-24 rounded-md bg-purple-500 dark:bg-gray-600/90 blur-3xl" ></div>
        <div class="absolute top-5 left-54 -z-30 px-44 py-24 rounded-md bg-purple-500 dark:bg-gray-600/90 blur-3xl" ></div>
    </div>
</div>
