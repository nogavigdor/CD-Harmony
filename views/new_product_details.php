<div class="bg-white shadow-lg p-4 flex-col  flex md:flex-row mx-auto  ">
    <!-- ... -->
    <div class="mt-4 md:w-2/3">
        <!-- ... -->
        <div class="text-lg font-semibold">Stock Quantities:</div>
        <div>
            <input type="radio" name="condition" value="new" id="new" class="mr-1">
            <label for="new">New: <?php echo $product->new_stock ?></label>
            <?php if ($product->new_stock <= 1): ?>
                <span class="text-red-500"><?php echo $product->new_stock == 0 ? 'Out of stock' : 'Only one left!' ?></span>
            <?php endif; ?>
        </div>
        <div>
            <input type="radio" name="condition" value="used" id="used" class="mr-1">
            <label for="used">Used: <?php echo $product->used_stock ?></label>
            <?php if ($product->used_stock <= 1): ?>
                <span class="text-red-500"><?php echo $product->used_stock == 0 ? 'Out of stock' : 'Only one left!' ?></span>
            <?php endif; ?>
        </div>
        <!-- ... -->
    </div>
</div>