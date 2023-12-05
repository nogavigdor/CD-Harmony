<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    <?php foreach ($articles as $article) : ?>
        <div class="bg-white rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out relative">
            <?php if (!empty($article->image_name)) : ?>
            <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($article->image_name) ?>" alt="<?= $product->article_title ?>" class="w-full h-40 object-cover rounded-md">
            <?php endif; ?>
            
                 <?php if (isset($article->publish_date) && !empty($article->publish_date)) : ?>
                   
                    <div class="absolute top-0 left-0 h-30 bg-secondary p-4  flex items-center justify-center text-white">
                    <?= $article->publish_date ?>
                    
                     </div>
                 <?php endif; ?>
                <div class="p-4">
                <h2 class="text-lg text-gray-900 font-semibold"><?= htmlspecialchars($article->title) ?></h2>
                <p class="mt-2 text-lg  text-base-500"><?= htmlspecialchars($article->first_name) ?></p>
                <div class="mt-4">
                    <div class="mt-2">
                        <a href="<?= BASE_URL ?>/article/<?= $article->article_id ?>" class="btn btn-secondary w-full text-white">Read Article</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
