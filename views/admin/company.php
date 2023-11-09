<!-- views/company.php -->

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once __DIR__ . '/../header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Company Details Form -->
    <form action="/cdharmony/admin/company/" method="POST">
        <?php
        $controller = new \controllers\CompanyController();
        $company = $controller->showCompanyDetails();
        ?>

        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo $company->company_name; ?>">

        <label for="street">Street:</label>
        <input type="text" id="street" name="street" value="<?php echo $company->street; ?>">

        <!-- Add similar fields for other attributes -->

        <button type="submit">Update Company Details</button>
    </form>
</div>

<?php
include_once __DIR__ . '/../footer.php';
?>
