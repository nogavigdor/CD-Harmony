<?php
namespace services;


    class Validator {
    
 // reCAPTCHA validation
 public function validateRecaptchaResponse($recaptcha) {
    $recaptcha_url = RECAPTCH_SECRET_KEY;
    $recaptcha_secret = '6LcTWQMpAAAAAEq-qGXtn9Iy_kuAcv8_AEwZxfqH';

    $ch = curl_init($recaptcha_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $recaptchaResponse = curl_exec($ch);
    
    // Check for cURL errors
    if (curl_errno($ch)) {
        return false;
    }

    curl_close($ch);

    // Decode the JSON response with error handling
    $recaptchaResult = json_decode($recaptchaResponse);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    if ($recaptchaResult->success == true && $recaptchaResult->score >= 0.5 && $recaptchaResult->action == "contact") {
        return $recaptchaResult;
    } else {
        return false;
    }
}
/*
    
    // reCAPTCHA validation
   public function validateRecaptchaResponse($recaptcha) {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LcTWQMpAAAAAEq-qGXtn9Iy_kuAcv8_AEwZxfqH';

    $ch = curl_init($recaptcha_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha,
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $recaptchaResponse = curl_exec($ch);
    
    // Check for cURL errors
    if (curl_errno($ch)) {
        return false;
    }

    curl_close($ch);

    // Decode the JSON response with error handling
    $recaptchaResult = json_decode($recaptchaResponse);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return false;
    }

    if ($recaptchaResult->success == true && $recaptchaResult->score >= 0.5 && $recaptchaResult->action == "contact") {
        return $recaptchaResult;
    } else {
        return false;
    }
}
*/
        public function validateEmail($email){
        
            $regexp = "/^[^0-9][A-z0-9_-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/";

            //if not email was entered
            if (empty($email)) 
                return "Please write your email.";
            //if the email is not in a valid format
            elseif (!preg_match($regexp,$email))
                return "This is an invalid Email.";
            // Passed all checks
            else
            return null;
        }

        public function validatePassword($password) {
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

        public function validateName($name) {
            if (strlen($name) > 100) {
                // Handle text length exceeds 100 characters
                return "Your name is too long. Please enter a name up to 100 characters.";
            }

            
            

            // Passed the check
            return null;
        }

        public function validateMessage($message) {
            if (strlen($message) > 3000) {
                // Handle text length exceeds 3000 characters
                return "The message you've entered is too long. Please enter a message up to 3000 characters.";
            } elseif (strlen($message) <1) {
                // Handle text length does not exceed 3000 characters
                return "Please write your message";
            }else {
                // Passed the check
                return null;
            }
            
        }
    }
