<?php

/**
 * Validate all user data
 *
 * @author Ventsislav Dimitrov
 */
class Validator {
    
    public function validateFbId($fbId) {
        if(empty($fbId)) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function validateName($name) {
        if(mb_strlen($name) < 2 || mb_strlen($name) > 40) {
            return FALSE;
        }
        return TRUE;
    }

    public function validateDate($date) {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }
    
}
