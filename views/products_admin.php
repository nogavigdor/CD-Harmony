<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    <?php foreach ($products as $product) : ?>
        <div class="bg-white rounded-lg shadow-lg w-full sm:w-auto transition transform hover:-translate-y-1">
         
                <?php

                echo $product->product_title;
                echo "<br>";
                echo $product->artist_title;
                echo "<br>";
                echo $product->new_price;
                echo "<br>";
                echo $product->new_quantity;
                echo "<br>";
                echo $product->old_price;
                echo "<br>";
                echo $product->old_quantity;
                echo "<br>";
                ?>
                </div>
    <?php endforeach; ?>
</div>

