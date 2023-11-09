<?php error_reporting(E_ALL);
ini_set('display_errors', 1); ?>
</body>
</html>
<footer class="footer p-10 bg-base-200 text-base-content">
  <nav>
    <header class="footer-title">Services</header>
    <a class="link link-hover">Branding</a>
    <a class="link link-hover">Design</a>
    <a class="link link-hover">Marketing</a>
    <a class="link link-hover">Advertisement</a>
  </nav>
  <nav>
    <header class="footer-title">Company</header>
    <a class="link link-hover">About us</a>
    <a class="link link-hover">Contact</a>
    <a class="link link-hover">Jobs</a>
    <a class="link link-hover">Press kit</a>
  </nav>
  <nav>
    <header class="footer-title">Legal</header>
    <a class="link link-hover">Terms of use</a>
    <a class="link link-hover">Privacy policy</a>
    <a class="link link-hover">Cookie policy</a>
  </nav>
  <div class="tabs">
    <a class="tab">Tab 1</a>
    <a class="tab tab-active">Tab 2</a>
    <a class="tab">Tab 3</a>
  </div>

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
    <div>
    <?php
                // Include the "Pop CDs" section
                $controller = new \Controllers\CompanyController();
               $company=$controller->getCompanyDetails();
             ?>
      &copy; <?php echo date("Y"); ?> <span><?php echo $company->company_name; ?></span>. All rights reserved.
      <!-- Company Details -->
      
      <span>Street: <?php echo $company->street; ?></span>
      <span>Email: <?php echo $company->email; ?></span>
      <span>Phone Number: <?php echo $company->phone_number; ?></span>
      <span>Opening Hours: <?php echo $company->opening_hours; ?></span>
      <span>Postal Code: <?php echo $company->postal_code_id; ?></span>
      
    </div>
  </form>
</footer>
</body>
</html>
