// Path: jersey-pro/assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
  // Add any JavaScript functionality here
  console.log('Jersey Pro JS loaded!');
  
  // Example: Auto-hide messages after 5 seconds
  setTimeout(() => {
      const messages = document.querySelectorAll('.message-container, .success-message, .error-message');
      messages.forEach(message => {
          if (message) {
              message.style.display = 'none';
          }
      });
  }, 5000);
});