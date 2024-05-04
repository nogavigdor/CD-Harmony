<div class="flex flex-col gap-4">
    <?php foreach ($articles as $article) : ?>
        <div class="bg-white rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out relative h-[180px] ">
            <?php if (!empty($article->image_name)) : ?>
            <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($article->image_name) ?>" alt="<?= $product->article_title ?>" class="w-full h-40 object-cover rounded-md">
            <?php endif; ?>
            
                 <?php if (isset($article->publish_date) && !empty($article->publish_date)) : ?>
                   
                    <div class="absolute top-0 left-0 h-[20px] bg-secondary p-4  flex items-center justify-center text-white">
                    <?= $article->publish_date ?>
                    
                     </div>
                 <?php endif; ?>
                <div class="p-2">
                <h2 class="text-lg text-gray-900 font-semibold"><?= htmlspecialchars_decode($article->title) ?></h2>
                
                <div class="mt-2">
                    <div class="mt-2 flex justify-start absolute bottom-0 right-2    w-1/3">
                        <a href="<?= BASE_URL ?>/article/<?= $article->article_id ?>" class=" btn btn-secondary text-white">Read Article</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
