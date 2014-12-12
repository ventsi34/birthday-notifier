<?php
/**
 * System users
 *
 * @author Ventsislav Dimitrov
 */
use Core\App;
class User {
    
    /**
     * Register new user
     * 
     * @param string $fbId 
     * @param string $firstName 
     * @param string $lastName 
     * @param date $birthDate 
     * @return boolean If the query is succeeded
     */
    public function register($fbId, $firstName, $lastName, $birthDate) {
        $fbId = addslashes($fbId);
        $firstName = addslashes($firstName);
        $lastName = addslashes($lastName);
        $birthDate = addslashes($birthDate);
        
        $query = App::$db->prepare('INSERT INTO `users`(`fb_id`, `first_name`, `last_name`, `birthday_date`, `register_date`) '
                . 'VALUES ("'.$fbId.'", "'.$firstName.'", "'.$lastName.'", "'.$birthDate.'", CURDATE())');
        $query->execute();
        
        return true;
    }
    
    /**
     * Check facebook user for registration and return user id if he is registered
     * 
     * @param string $fbId Facebook Id
     * @return int User id
     */
    public function getUserId($fbId) {
        $fbId = addslashes($fbId);
        $query = App::$db->query('SELECT `user_id` FROM `users` WHERE `fb_id` = "'.$fbId.'" LIMIT 1');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetch();
        if(!isset($result['user_id'])) {
            return FALSE;
        }
        return $result['user_id'];
    }
    
    public function saveFriends($fbId, $friendsList) {
        
    }
}
