<?php
use Services\SessionManager;
?>
<?php
//SessionManager::startSession();
$successMessage = SessionManager::getSessionVariable('success_message'); ?>
<?php if(!empty($successMessage)): ?>

        <div id="success-modal" class="absolute top-0  z-10 flex justify-center font-bold bg-green-100 w-full md:w-full p-8 rounded shadow-md">
            <span class="cursor-pointer text-gray-500 absolute  z-10 top-2 right-2 text-xl" onclick="closeSuccessModal()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8">
            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
            </svg>
            </span>
            <p class="text-gray-500"><?= $successMessage; ?></p>
        </div>
  
<?php endif; ?>
<?php SessionManager::unsetSessionVariable('success_message'); ?>

<?php $errorMessage = SessionManager::getSessionVariable('error_message'); ?>
<?php if(!empty($errorMessage)): ?>

    <div id="error-modal" class="absolute top-0  z-10 f flex justify-center font-bold bg-red-100 w-full md:w-full p-8 rounded shadow-md">
            <span class="cursor-pointer text-gray-500 absolute  z-10 top-2 right-2 text-xl" onclick="closeErrorModal()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8">
            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
            </svg>
            </span>
            <p class="text-gray-500"><?= $errorMessage; ?></p>
        </div>
   
<?php endif; ?>
<?php SessionManager::unsetSessionVariable('error_message'); ?>
    <script defer>
        // JavaScript function to close the success modal
        function closeSuccessModal() {
            document.getElementById('success-modal').style.display = 'none';
        }

         // JavaScript function to close the error modal
         function closeErrorModal() {
            document.getElementById('error-modal').style.display = 'none';
        }       
    </script>

       