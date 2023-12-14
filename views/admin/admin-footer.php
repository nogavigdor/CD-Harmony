
<footer class="flex-col p-10 bg-base-200 text-base-content">
  <div class="footer">
<?php
            
$controller = new \Controllers\CompanyController();
$company=$controller->getCompanyDetails();
?>
            
 <!-- Company Details -->
 <div>
  <header class="footer-title">Company Details</header>
  <p><?php echo $company->company_name; ?></p>
  <p>Address: <?php echo $company->street." ".$company->postal_code_id." ".$company->city; ?></p>
  <p>Email: <a href="mailto:<?php echo $company->email; ?>"><?php echo $company->email; ?></a></p>
  <p>Phone Number: <?php echo $company->phone_number; ?></p>
  <p>Opening Hours: <?php echo $company->opening_hours; ?></p>
  </div>
  <nav>
    <header class="footer-title">Services</header>
    <a class="link link-hover">Branding</a>
    <a class="link link-hover">Design</a>
    <a class="link link-hover">Marketing</a>
    <a class="link link-hover">Advertisement</a>
  </nav>
  <form>
    <header class="footer-title">Newsletter</header>
    <fieldset class="form-control w-80">
      <label class="label">
        <span class="label-text">Enter your email address</span>
      </label>
      <div class="relative">
        <input type="text" placeholder="username@site.com" class="input input-bordered w-full pr-16" />
        <button class="btn btn-primary absolute top-0 right-0 rounded-l-none">Subscribe</button>
      </div>
    </fieldset>
  </form>  
 
</div> <!--End of footer columns -->
  <div class="flex justify-center mt-8 font-headline">&copy; <?php echo date("Y"); ?> <span class="mr-4 ml-4"><?php echo $company->company_name; ?></span>All rights reserved. </div>
 
</footer>
</body>
</html>