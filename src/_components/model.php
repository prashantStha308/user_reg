<?php
    global $model;
?>
<div class=" inset-0 flex justify-center items-center p-8 min-h-screen w-full bg-black/10 dark:bg-white/15 z-50 backdrop-blur-xl overflow-y-hidden">
    <div class="w-full max-w-md p-6 bg-gray-200 dark:bg-gray-800 rounded-lg shadow-xl">
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
</div>
