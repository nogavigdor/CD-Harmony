
function createSuccessModal(message) {
  // Create the main container
  var modalContainer = document.createElement('div');
  modalContainer.id = 'success-modal';
  modalContainer.className = 'absolute top-2 left-0 right-0 bottom-0 flex items-center justify-center';

  // Create the modal content
  const modalContent = document.createElement('div');
  modalContent.className = 'relative bg-white w-full md:w-1/2 p-8 rounded shadow-md';   

  // Create the close button
  const closeButton = document.createElement('span');
  closeButton.className = 'cursor-pointer text-gray-500 absolute z-10 top-2 right-2 text-xl';
  closeButton.innerHTML = `<img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='w-6 h-6'%3E%3Cpath fill-rule='evenodd' d='M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z' clip-rule='evenodd' /%3E%3C/svg%3E" alt="Close" class="w-6 h-6 ">`;
  closeButton.onclick = closeSuccessModal;

  // Create the message paragraph
  const messageParagraph = document.createElement('p');
  messageParagraph.className = 'text-gray-500';
  messageParagraph.innerHTML = message;

  // Append elements to the DOM
  modalContent.appendChild(closeButton);
  modalContent.appendChild(messageParagraph);
  modalContainer.appendChild(modalContent);
  document.body.appendChild(modalContainer);
}

function closeSuccessModal() {
  const modal = document.getElementById('success-modal');
  if (modal) {
    modal.parentNode.removeChild(modal);
  }
}

let successMessage = "<?php echo SessionManager::getSessionVariable('success_message'); ?>";
if (successMessage) {
    createSuccessModal(successMessage);
  }