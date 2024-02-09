<?php
use Controllers\SpecialOfferController;
// In your homepage code
include 'header.php';

?>

        <!-- Main content section -->
        <main class="container mx-auto p-4">
            <h1 class="text-base-100">Welcome to CD Harmony</h1>
            <!-- Hero section -->
             <div class="grid grid-cols-1 sm:grid-cols-3 h-600 gap-4 " >
             <Section class="col-span-3 sm:col-span-2 pt-4">
                <?php $specialOfferController= new SpecialOfferController(); 
                    $specialOffer=$specialOfferController->showSpecialOffer();
                    $offerEndDate = $specialOffer['special_offer_end_date'];
                    $offerEndDate = timeStampTodDate($offerEndDate);
                    
                ?>

                <h2 class="text-base-100 text-2xl font-bold mb-4 headline   ">Special Offer</h2>
       
                <div class="bg-white relative w-full rounded">
                    <div class="p-4">
                        <p class="text-xl text-secondary font-bold mb-2"><?= $specialOffer['product_title']; ?></p>
                        <p class="text-xl text-secondary font-bold mb-2"></p>
                        <p class="text-xl text-secondary font-bold mb-2">
                        <?= $specialOffer['title'];?>
                        </p>
                        <p class="countdown text-xl text-secondary  font-bold mb-2">Offer ends: <?= $offerEndDate ?></p>
                    </div>
                    <div class="mask mask-star  bg-base-100 w-[500px] border-4 border-secondary h-[500px] shadow-lg absolute top-[-100px] right-[50px] text-white p-4 flex flex-col justify-center items-center">

                        <p class="text-xl text-secondary font-bold mb-2">Only <?=  $specialOffer['price']-$specialOffer['discount'] ?> DKK</p>
                        <p class="text-xl text-secondary text-line font-bold mb-2">Was <?= $specialOffer['price'] ?> DKK</p>
                    </div> 
                    <img src="<?= BASE_URL.IMAGE_PATH.$specialOffer['image_name'];?>" alt="CD Harmony fan" class="w-1/2 object-cover">
                    <a href="<?= BASE_URL.'/product/'.$specialOffer['product_id']; ?>" class="btn btn-accent text-white absolute right-2 bottom-2 w-1/3">View More details</a>
                </div>
            </Section>
                    <!-- Articles section -->
                    <section class="col-span-3 sm:col-span-1 flex flex-col gap-4 ">
                        <h2 class="text-base-100 text-2xl font-bold mb-4">Check out our latest articles</h2>
            
                        <?php
                        // Includes the articles section
                        $controller = new \Controllers\ArticleController();
                        $controller->showRecentArticles();
                
                        ?>
            
                    </section>
            </div>
            
            <section>
                <h2 class="text-base-100 text-2xl font-bold mb-4">Pop CDs</h2>
        
                    <?php
                    // Include the "Pop CDs" section
                    $controller = new \Controllers\ProductController();
                $controller->showProductsByTag('pop');
            
                    ?>
        
            </section>
            <!-- Rock CDs section -->
            <section>
                <h2 class="text-base-100 text-2xl font-bold mb-4">Rock CDs</h2>
        
                    <?php
                    // Include the "Pop CDs" section
                    $controller = new \Controllers\ProductController();
                    $controller->showProductsByTag('rock');
        
                    ?>
        
            </section>
            <!-- Country CDs section -->
            <section>
                <h2 class="text-base-100 text-2xl font-bold mb-4">Country CDs</h2>
        
                    <?php
                        // Include the "CountryCDs" section
                        $controller = new \Controllers\ProductController();
                        $controller->showProductsByTag('country');   
                    ?>
        
            </section>


            <!-- New Releases section -->
            <section>
                <h2 class="text-base-100 text-2xl font-bold mt-8 mb-4">New Releases</h2>
            
                    <?php
                    // Include the "New Releases" section
                    $controller = new \Controllers\ProductController();
                    $controller->showRecentReleases();
                    ?>
            
            </section>

            <!-- Other content of your homepage -->
            <!-- ... -->
        </main>

        <!-- Footer section -->
        <?php
            include 'footer.php';
        ?>
    