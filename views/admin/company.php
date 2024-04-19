<?php include 'admin-header.php' ?>

<main class='content bg-primary'>
<?php
use Services\SessionManager;

?>


<div class="flex  bg-gray-100">
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
       
        <div class="container h-screen mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        <h1 class="h1">Company Information</h1>
                <!-- Company Details Form -->
                <div class=" mt-10  grid grid-cols-1 gap-x-8 p-y-8 sm:grid-cols-6">
                        <form  action="<?= BASE_URL.'/admin/company'; ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>"> 
                            <input type="hidden" name="_method" value="PUT">     
                            <input type="hidden" name="company_details_id" value="<?= htmlspecialchars($company->company_details_id) ?>">

                            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name:</label>
                            <input type="text" id="company_name" name="company_name" value="<?= htmlspecialchars($company->company_name) ?>" class="mt-1 p-2 border rounded-md">

                            <label for="street" class="block text-sm font-medium text-gray-700">Street:</label>
                            <input type="text" id="street" name="street" value="<?= htmlspecialchars($company->street) ?>" class="mt-1 p-2 border rounded-md">

                            <label for="postal_code_id" class="block text-sm font-medium text-gray-700">Postal Code:</label>
                            <input type="text" id="postal_code_id" name="postal_code_id" value="<?= htmlspecialchars($company->postal_code_id) ?>" class="mt-1 p-2 border rounded-md">

                            <label for="opening_hours" class="block text-sm font-medium text-gray-700">Opening Hours:</label>
                            <input type="text" id="opening_hours" name="opening_hours" value="<?= htmlspecialchars($company->opening_hours) ?>" class="mt-1 p-2 border rounded-md">

                            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                            <input type="text" id="email" name="email" value="<?= htmlspecialchars($company->email) ?>" class="mt-1 p-2 border rounded-md">

                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                            <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($company->phone_number) ?>" class="mt-1 p-2 border rounded-md">

                            <button type="submit" class="mt-4 bg-base-100 text-gray-900 py-2 px-4 rounded-md transition-all duration-300 ease-in-out transform hover:bg-primary hover:text-white active:scale-95">Update Company Details</button>
                        </form>
                
                </div>
                

        </div>
</div>

</main>
<script>
    // Submit company details form asynchronously
document.querySelector('form').addEventListener('submit', async (e) => {
    e.preventDefault(); // Prevent the default form submission

    const formData = new FormData(e.target); // Create a FormData object from the form

    try {
        const response = await fetch('<?php echo BASE_URL.'/admin/company' ?>', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const responseData = await response.json(); // Parse the JSON response
      

        if (responseData.success) {
            // Show success message
            alert(responseData.message);
        } else {
            // Display validation errors, if any
            if (responseData.errors) {
                let errorMessage = 'Validation failed:\n';
                Object.keys(responseData.errors).forEach((key) => {
                    errorMessage += `${responseData.errors[key]}\n`;
                });
                alert(errorMessage);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while processing your request. Please try again later.');
    }
});
</script>
<?php include 'admin-footer.php' ?>