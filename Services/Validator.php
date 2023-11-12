<?php
    
    namespace Services;



    class Validator {
        

        public function validateRecaptchaResponse($recaptchaResponse, $recaptchaSecretKey) {
            // Implementation of reCAPTCHA validation...
        }

        public function validatePasswordForSignup($password) {
            // Minimum length requirement
            $minLength = 8;
            if (strlen($password) < $minLength) {
                return "Password must be at least 8 characters long.";
            }

            // Contains at least one uppercase letter
            if (!preg_match('/[A-Z]/', $password)) {
                return "Password must contain at least one uppercase letter.";
            }

            // Contains at least one lowercase letter
            if (!preg_match('/[a-z]/', $password)) {
                return "Password must contain at least one lowercase letter.";
            }

            // Contains at least one digit
            if (!preg_match('/\d/', $password)) {
                return "Password must contain at least one digit.";
            }

            // Contains at least one special character (e.g., !@#$%^&*)
            if (!preg_match('/[!@#\$%\^&\*\(\)_\+\-=\[\]\{\};:\'",<>\.\?~`]/', $password)) {
                return "Password must contain at least one special character.";
            }

           
            // Passed all checks
            return null;
        }

        public function validateEmail($email) {

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Handle invalid email
                return "The email you've entered is invalid. Please try again.";
            }

              // Passed the check
              return null;

        }

        
        public function validateName($name) {

            if (strlen($name) > 100) {
                // Handle text length exceeds 3000 characters
                return "You name is too long. Please enter a name up to 100 xcharacters.";
            }

              // Passed the check
              return null;

        }

        public function validateMessage($message) {

            if (strlen($message) > 3000) {
                // Handle text length exceeds 3000 characters
                return "The message you've entered is too long. Please enter a message up to 3000 characters. ";
            }

              // Passed the check
              return null;

        }

    
    }

   