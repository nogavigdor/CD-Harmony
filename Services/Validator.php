<?php
    namespace Services;

      class Validator {
        
    // reCAPTCHA validation
    // $recaptcha is the response from the reCAPTCHA service and $action indicated the form that was submitted
    public function validateRecaptchaResponse($captcha, $action) {
        $captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = RECAPTCHA_SECRET_KEY;
        // call curl to POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $captcha_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_secret, 'response' => $captcha)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);
 
        var_dump($arrResponse);
        // verify the response
        if($arrResponse["success"] == true && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
        // valid submission
            return true;
            echo "I'm after the recaptch validation to true";
        
        } else {
        // spam submission
        echo "I'm after the reCAPTCHA validation is false";
        var_dump($arrResponse);
        echo $arrResponse["success"];
        echo $arrResponse["action"];
        echo $arrResponse["score"];
        return false;
        }
    }

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
                if (strlen($name) > 50) {
                    // Handle text length exceeds 100 characters
                    return "Your name is too long. Please enter a name up to 50 characters.";
                }

                // Passed the check
                return null;
            }

            public function validateMessage($message) {
                if (strlen($message) > 1000) {
                    // Handle text length exceeds 3000 characters
                    return "The message you've entered is too long. Please enter a message up to 1000 characters.";
                } elseif (strlen($message) <1) {
                    // Handle text length does not exceed 1000 characters
                    return "Please write your message";
                }else {
                    // Passed the check
                    return null;
                }
                
            }

             // Validation to check if input is a date that is earlier or equal to the current date
            public function releaseDate($date) {
                $currentDate = date('Y-m-d');
                if ($date > $currentDate) {
                    return "Date must be equal to or earlier than the current date.";
                }
                return null; // Passed the check
            }

            // Validation to check if startDate is equal or earlier than endDate
            public function validateDateRange($startDate, $endDate) {
                if ($startDate > $endDate) {
                    return "Start date must be equal to or earlier than end date.";
                }
                return null; // Passed the check
            }

            // Validation to check if two date ranges overlap
            public function dateRangesOverlap($start1, $end1, $start2, $end2) {
                if (($start1 <= $end2) && ($end1 >= $start2)) {
                    return "Date ranges overlap with an existing special offer of this variant";
                }
                return null; // Passed the check
            }
        

            public function validateTitle($title) {
                if (strlen($title) > 100) {
                    // Handle text length exceeds 100 characters
                    return "Title is too long. Please enter a title up to 100 characters.";
                }

                // Passed the check
                return null;
            }

            public function validateText($text) {
                if (strlen($text) > 3000) {
                    // Handle text length exceeds 3000 characters
                    return "Text is too long. Please enter a text up to 3000 characters.";
                }

                // Passed the check
                return null;
            }

            public function validatePositiveNumber($number) {
                if (!is_numeric($number)) {
                    return "Number must be a numeric value.";
                }
                if ($number < 0) {
                    return "Number must be a positive number.";
                }
                return null; // Passed the check
            }

            /*validate Danish phone numbers of the following format:
            
                +4512345678
                004512345678
                4512345678
                12 34 56 78
                1234 5678
                12345678
                12 345678
            */
            public function validatePhoneNumber($phoneNumber) {
                $regexp = "/^(?:\+45|0045|45)?\s?(?:\d{8}|\d{2}\s?\d{2}\s?\d{2}\s?\d{2}|\d{4}\s?\d{4})$/";
                //if not phone number was entered
                if (empty($phoneNumber)) 
                    return "Please write your phone number.";
                //if the phone number is not in a valid format
                elseif (!preg_match($regexp,$phoneNumber))
                    return "This is an invalid phone number.";
                // Passed all checks
                else
                return null;
            }
        }
