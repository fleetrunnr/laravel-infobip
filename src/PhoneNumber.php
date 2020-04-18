<?php

namespace FleetRunnr\Infobip;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberType;

class PhoneNumber
{
    /**
     * Static function to check if a phone number is valid.
     *
     * @param String $phone
     * 
     * @return bool
     */
    public static function isValid($phone)
    {
        // Get a new instance of the phone number utility service
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try {
            // If phone doesn't have a `+` sign, add it before validating
            if(substr($phone, 0, 1) != '+') {
                $phone = '+' . $phone;
            }

            // Parse the phone number
            $phoneNumberObject = $phoneNumberUtil->parse($phone, null);
            if($phoneNumberObject) {
                // Check validity and type
                $isValid = $phoneNumberUtil->isValidNumber($phoneNumberObject);
                $type = $phoneNumberUtil->getNumberType($phoneNumberObject);

                // Return true if the number is valid and belongs to a mobile network
                if($isValid && $type == PhoneNumberType::MOBILE) {
                    return true;
                }
            }

            return false;
        }
        catch(\Exception $e) {
            return false;
        }
    }
}
