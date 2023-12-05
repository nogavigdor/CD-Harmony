<?php
use Services\SessionManager;
?>
<?php $successMessage = SessionManager::getSessionVariable('success_message'); ?>
<?php if(!empty($successMessage)): ?>
    <div id="success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full md:w-1/2 p-8 rounded shadow-md">
            <span class="cursor-pointer text-gray-500 absolute  z-10 top-2 right-2 text-xl" onclick="closeSuccessModal()">X</span>
            <p class="text-gray-500"><?= $successMessage; ?></p>
        </div>
    </div>
<?php endif; ?>
<?php SessionManager::unsetSessionVariable('success_message'); ?>

<?php $errorMessage = SessionManager::getSessionVariable('error_message'); ?>
<?php if(!empty($errorMessage)): ?>
    <div id="error-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full md:w-1/2 p-8 rounded shadow-md">
            <span class="cursor-pointer text-gray-500 absolute  z-10 top-2 right-2 text-xl" onclick="closeErrorModal()">X</span>
            <p class="text-gray-500"><?= $errorMessage; ?></p>
        </div>
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

       