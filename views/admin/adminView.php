<?php include 'admin-header.php' ?>

<main class='content bg-primary'>
<?php
use Services\SessionManager;

// Get the current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Extract the last part of the path 
$lastPathSegment = basename($currentPath);
echo $lastPathSegment;
?>

<div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="menu bg-base-200 w-56 rounded-box">
            <ul class="space-y-4 mt-8">
            <li>
            <a href="<?= BASE_URL.'/admin/users'; ?>" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 5l3 3m0 0l-3 3m3-3H5"></path>
                </svg>
                Users
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL.'/admin/orders'; ?>" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                Orders
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL.'/admin/products'; ?>" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                Products
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL.'/admin/articles'; ?>" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 8v4m-4-4H8m4 4h8"></path>
                </svg>
                Articles
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL.'/admin/special-offers'; ?>" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Special Offers
            </a>
        </li>
        <?php
        $isActive = ($lastPathSegment === 'company');
        $class = ($isActive) ? 'active' : '';
        ?>
        <li>
            <a href="<?= BASE_URL.'/admin/company'; ?>" class="<?= $class ?> flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Company Details
            </a>
        </li>
            </ul>
        </div>

        <!-- Content Area -->
        <div class="flex-1 p-8">
            <?php
            // Get the path from the URL
            $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            // Define the default content for the root path
            $defaultContent = 'products.php';

            // Map URL paths to their corresponding content files
            $contentMappings = [
                '/admin/products' => 'products.php',
                '/admin/users' => 'users.php',
                '/admin/articles' => 'articles.php',
                '/admin/special-offers' => 'special_offers.php',
                '/admin/company-details' => 'company_details.php',
            ];

            // Include the appropriate content file based on the URL path
            $contentFile = $contentMappings[$urlPath] ?? $defaultContent;
            include $contentFile;
            ?>
        </div>
    </div>
</main>
<?php include 'admin-footer.php' ?>
