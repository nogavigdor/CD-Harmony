
<?php
    include_once 'header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 sm:mb-16 sm:mt-16"  >
    <!-- Product Card -->
    <div class="bg-white shadow-lg p-4 mx-auto lg:flex lg:w-2/3">
    <div class="mr-20">
        
    <!-- Article image  place holder -->
    </div>
    <div>   
    <h2 class="text-2xl text-purple-800 font-semibold mt-2"><?= $article->title ?></h2>
    <div class="flex justify-between w-full">
        <div class="text-lg font-semibold"><?= 'Author: '.$article->first_name.' '.$article->last_name ?></div>
        <div class="text-lg font-semibold"><?= 'Published: '.$article->publish_date ?></div>
    </div>
    
        <div class="mt-4">
           <p><?= $article->content ?></p>
        </div>
     
        </div>
    </div>
    <!-- Repeat the above structure for each product -->
</div>
<?php
    include_once 'footer.php';
?>



