
<!-- views/company.php -->

<?php
// Displaying an error message for debugging purposes
echo "Including company.php";

// Setting up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Including the admin header
include_once __DIR__ . '/admin-header.php';
?>

<main class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
<h1 class="h1">Company Information</h1>
        <!-- Company Details Form -->
        <div class=" mt-10  grid grid-cols-1 gap-x-8 p-y-8 sm:grid-cols-6">
                <form  action="/cdharmony/admin/company/" method="POST">
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
        

</main>

<?php
// Including the admin footer
include_once __DIR__ . './admin-footer.php';
?>  