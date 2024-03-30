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
                    <div class="bg-gradient-to-tr from-red-300 to-orange-100  p-4">
                        <p class="text-xl text-secondary font-bold mb-2"><?= $specialOffer['product_title']; ?></p>
                        <p class="text-xl text-secondary font-bold mb-2"></p>
                        <p class="text-xl text-secondary font-bold mb-2">
                        <?= $specialOffer['title'];?>
                        </p>
                        <p class="countdown text-xl text-secondary  font-bold mb-2">Offer ends: <?= $offerEndDate ?></p>
                    </div>
                    <div class="mask mask-star bg-base-100 w-[500px] border-4 border-secondary h-[500px] shadow-lg absolute top-[-100px] right-[50px] text-white p-4 flex flex-col justify-center items-center">

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
             <!-- Pop CDs section -->
             <section data-tag='pop' data-offset=0>
                <h2 class="text-base-100 text-2xl font-bold mb-4">Pop CDs</h2>
        
                    <?php 
                    // Include the "Pop CDs" section
                    $controller = new \Controllers\ProductController();
                    ?>
                    <div data-tag='pop' class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
    
                        <?php
                          $controller->showProductsByTag('pop', 0, 4);
                          ?>
                     
                    </div>
                  

                    
         <button class="prev-button btn" data-tag="pop">Previous</button>
        <button class="next-button btn" data-tag="pop">Next</button>
            </section>
          
            <!-- Rock CDs section -->
            <section data-tag='rock' data-offset=0>
                <h2 class="text-base-100 text-2xl font-bold mb-4">Rock CDs</h2>
        
                    <?php 
                    // Include the "Pop CDs" section
                    $controller = new \Controllers\ProductController();
                    ?>
                    <div data-tag='rock' class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
                 
                        <?php
                          $controller->showProductsByTag('rock', 0, 4);
                          ?>
                    
                    </div>
                  

                    
         <button class="prev-button btn" data-tag="rock">Previous</button>
        <button class="next-button btn" data-tag="rock">Next</button>
            </section>
            <!-- Country CDs section -->
            <section data-tag='country' data-offset=0>
                <h2 class="text-base-100 text-2xl font-bold mb-4">Country CDs</h2>
        
                    <?php 
                    // Include the "Country CDs" section
                    $controller = new \Controllers\ProductController();
                    ?>
                    <div data-tag='country' class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">

                        <?php
                          $controller->showProductsByTag('country', 0, 4);
                          ?>
                    </div>
                  

                    
         <button class="prev-button btn" data-tag="country">Previous</button>
        <button class="next-button btn" data-tag="country">Next</button>
            </section>

            <!-- New Releases section -->
            <section>
                <h2 class="text-base-100 text-2xl font-bold mt-8 mb-4 ">New Releases</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
                    <?php
                    // Include the "New Releases" section
                    $controller = new \Controllers\ProductController();
                    $controller->showRecentReleases();
                    ?>
            </div>
            </section>

            <!-- Other content of your homepage -->
            <!-- ... -->
        </main>
        <script>
    document.addEventListener('DOMContentLoaded', function(){
        const prevButtons = document.querySelectorAll('.prev-button');
        const nextButtons = document.querySelectorAll('.next-button');

        prevButtons.forEach(button => {
            button.addEventListener('click', function(){
                const tag = button.dataset.tag;
                const section = document.querySelector(`section[data-tag="${tag}"]`);
                const div= document.querySelector(`div[data-tag="${tag}"]`);
                let offset = parseInt(div.dataset.offset) || 0; // Parse offset as integer or default to 0
                let limit = 4;
                if(window.innerWidth < 768){
                    limit = 2;
                }
                section.dataset.offset = Math.max(0, offset - limit); // Ensure offset is not negative

                // Call the API to get the products
                fetchProducts(tag, section.dataset.offset, limit)
                    .then(data => {
                        // Update the section with the new products
                        div.innerHTML = data;
                    });
            });
        });

        nextButtons.forEach(button => {
            button.addEventListener('click', function(){
                const tag = button.dataset.tag;
                const section = document.querySelector(`section[data-tag="${tag}"]`);
                const div = document.querySelector(`div[data-tag="${tag}"]`);
                let offset = parseInt(section.dataset.offset) || 0; // Parse offset as integer or default to 0
                let limit = 4;
                if(window.innerWidth < 768){
                    limit = 2;
                }
                section.dataset.offset = offset + limit;

                // Call the API to get the products
                fetchProducts(tag, section.dataset.offset, limit)
            .then(data => {
                if (data === 'no products') {
                    // No more products to show, so reset the offset to 0 and show products from the start
                    return fetchProducts(tag, 0, limit); // Fetch products from the beginning
                } else {
                    return data;
                }
            })
            .then(data => {
                // Update the section with the new products
                div.innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    });
});
        

        function fetchProducts(tag, offset, limit) {
            const data = new URLSearchParams();
            data.append('tag', tag);
            data.append('offset', offset);
            data.append('limit', limit);
         return fetch(`${BASE_URL}/products/section`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.text())
    .catch(error => console.error('Error:', error));
}

    });


</script>

        <!-- Footer section -->
        <?php
            include 'footer.php';
        ?>
    