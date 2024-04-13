<?php  if (isset($products) && !empty($products)) : ?>
    <?php
    foreach ($products as $product) : ?>
        <div class="bg-white rounded-lg shadow-lg transform hover:-translate-y-1 transition duration-300 ease-in-out relative">
            <img src="<?= BASE_URL ?>/src/assets/images/albums/<?= htmlspecialchars($product->image_name) ?>" alt="<?= $product->product_title ?>" class="w-full h-40 object-cover rounded-md">
            <!-- Ribbon for Release Date - if exists-->
            
                 <?php if (isset($product->release_date) && !empty($product->release_date)) : ?>
                   
                    <div class="absolute top-0 left-0 h-30 bg-secondary p-4  flex items-center justify-center text-white">
                    <?= $product->release_date ?>
                    
                     </div>
                 <?php endif; ?>
                  

                <div class="p-4">
                <p class="text-lg text-primary font-semibold"><?= htmlspecialchars($product->product_title) ?></p>
                <p class="mt-2 text-lg  text-base-500"><?= htmlspecialchars($product->artist_title) ?></p>
                <p class="mt-2"><?= htmlspecialchars($product->tag_title) ?></p>
                <div class="mt-4">
                    <!-- Display New Price -->
                   <?php     if ($product->new_price > 0) : ?>
                            <div class="text-lg  text-base-500"><?= $product->new_price ?> DKK (New)</div>
                    <?php endif; ?>
                 
                    
                    <!-- Display Used Price -->
                        <?php if ($product->used_price > 0) : ?>
                            <div class="text-lg  text-base-800"><?= $product->used_price ?> DKK (Used)</div>
                        <?php endif; ?>    
              
                    <div class="mt-2">
                        <a href="<?= BASE_URL ?>/product/<?= $product->product_id ?>" class="btn btn-secondary w-full text-white">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

                    

