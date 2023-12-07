
<!-- views/company.php -->

<?php



// Including the admin header
include_once __DIR__ . '/admin-header.php';
?>

<?php
use Services\SessionManager;
$csrfToken=SessionManager::generateCSRFToken();
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
                <li>
                <a href="<?= BASE_URL.'/admin/company'; ?>" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Company Details
                </a>
                </li>
                </ul>
        </div>

        <!-- Content Area -->
       
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <h1 class="h1">Company Information</h1>
                <!-- Company Details Form -->
                <div class=" mt-10  grid grid-cols-1 gap-x-8 p-y-8 sm:grid-cols-6">
                        <form  action="" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">      
                        <?php $controller = new \controllers\CompanyController(); 
                        $company = $controller->getCompanyDetails(); ?>
                        <input  type="hidden" name="company_details_id" value="<?php echo $company->company_details_id; ?>">

                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name:</label>
                        <input   type="text" id="company_name" name="company_name" value="<?php echo $company->company_name; ?>" class="mt-1 p-2 border rounded-md">

                        <label for="street" class="block text-sm font-medium text-gray-700">Street:</label>
                        <input type="text" id="street" name="street" value="<?php echo $company->street; ?>" class="mt-1 p-2 border rounded-md">

                        <label for="postal_code_id" class="block text-sm font-medium text-gray-700">Postal Code:</label>
                        <input type="text" id="postal_code_id" name="postal_code_id" value="<?php echo $company->postal_code_id; ?>" class="mt-1 p-2 border rounded-md">

                        <label for="city" class="block text-sm font-medium text-gray-700">City:</label>
                        <input type="text" id="city" name="city" value="<?php echo $company->city; ?>" class="mt-1 p-2 border rounded-md">

                        <label for="opening_hours" class="block text-sm font-medium text-gray-700">Opening Hours:</label>
                        <input type="text" id="opening_hours" name="opening_hours" value="<?php echo $company->opening_hours; ?>" class="mt-1 p-2 border rounded-md">

                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo $company->email; ?>" class="mt-1 p-2 border rounded-md">

                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?php echo $company->phone_number; ?>" class="mt-1 p-2 border rounded-md">

                        <!-- Add similar fields for other attributes -->

                        <button type="submit" class="mt-4 bg-base-100 text-gray-900 py-2 px-4 rounded-md transition-all duration-300 ease-in-out transform hover:bg-primary hover:text-white active:scale-95">Update Company Details</button>
                         </form>
                
                </div>
                

        </div>
</div>



<?php
// Including the admin footer
include_once __DIR__ . './admin-footer.php';
?>  