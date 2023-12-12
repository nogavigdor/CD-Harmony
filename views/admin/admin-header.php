<?php
use Services\SessionManager;
SessionManager::startSession();
$csrfToken=SessionManager::generateCSRFToken();
if(SessionManager::getSessionVariable('error_message')){
    $errorMessage = SessionManager::getSessionVariable('error_message');
    SessionManager::unsetSessionVariable('error_message');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script defer src="<?php echo BASE_URL; ?>/src/js/app.js"></script>  
  <link href="<?php echo BASE_URL ?>/src/css/output.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-primary font-body min-h-screen">

    <!-- Header section -->
    <header class="bg-secondary text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
             <div>  
              <a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>/src/assets/logo_no_background.png" alt="CD Harmony Logo" class="w-64 h-64"></a>
            </div>


           
            <div>

                <a href="<?= BASE_URL . '/logout'; ?>" class="px-4 py-2 text-white "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Logout</a>
        
            </div>
        </div>
    </header>
    <?php include 'partials/message.php';  ?>