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
    
    /**
     * Add user friends from facebook
     * 
     * @param type $userId 
     * @param type $friendsList Array with user friends
     * @throws boolean  
     */
    public function addFriends($userId, $friendsList) {
        $userFriends = $this->getFriends($userId);
        if(empty($userFriends)){
            try {
                App::$db->beginTransaction();
                    foreach($friendsList as $value) {
                        $firstName = addslashes($value['firstName']);
                        $lastName = addslashes($value['lastName']);
                        $birthday = addslashes($value['birthday']);
                        $userId = (int)$value['userId'];
                        $validator = new \Validator();
                        if(!$validator->validateName($firstName) || !$validator->validateName($lastName)) {
                            continue;
                        }
                        $query = App::$db->prepare('INSERT INTO `friends`(`first_name`, `last_name`, `birthday`, `group_id`, `user_id`) '
                                . 'VALUES ("'. $firstName .'", "'. $lastName .'", "'. $birthday .'", 1, "'. $userId .'")');
                        $query->execute();
                    }
                App::$db->commit();
            } catch (\PDOException $ex) {
                App::$db->rollBack();
                throw $ex;
            }
        } else {
            
        }
    }
    
    /**
     * Return user friends list
     * 
     * @param int $userId User id
     * @return array List with user friends
     */
    public function getFriends($userId) {
        $userId = (int)$userId;
        $query = App::$db->query('SELECT * FROM `friends` WHERE `user_id` = "'.$userId.'"');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $list = array();
        while($result = $query->fetch()){
            array_push($list, $result);
        }
        return $list;
    }
}
