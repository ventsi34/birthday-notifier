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
        try {
            App::$db->beginTransaction();
                foreach($friendsList as $value) {
                    $fbId = addslashes($value['fbId']);
                    $firstName = addslashes($value['firstName']);
                    $lastName = addslashes($value['lastName']);
                    $birthday = addslashes($value['birthday']);
                    $validator = new \Validator();
                    if(!$validator->validateName($firstName) || !$validator->validateName($lastName)) {
                        continue;
                    }
                    $query = App::$db->prepare('REPLACE INTO `friends`(`fb_id`, `first_name`, `last_name`, `birthday`, `user_id`) '
                            . 'VALUES ("'.$fbId.'", "'. $firstName .'", "'. $lastName .'", "'. $birthday .'", "'. (int)$userId .'")');
                    $query->execute();
                }
            App::$db->commit();
            return true;
        } catch (\PDOException $ex) {
            App::$db->rollBack();
            throw $ex;
        }
    }
    
    /**
     * Return user friends list
     * 
     * @param int $userId User id
     * @return array List with user friends
     */
    public function getFriends($userId) {
        $query = 'SELECT 
                        `fr`.*,
                        `g`.`group_name`
                FROM 
                        `friends` as `fr`
                LEFT JOIN
                        `groups` AS `g`
                ON `g`.`group_id` = `fr`.`group_id`
                WHERE 
                        `user_id` = "'.(int)$userId.'"
                ORDER BY `group_id`';
        $query = App::$db->query($query);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $list = array();
        while($result = $query->fetch()){
            array_push($list, $result);
        }
        return $list;
    }
    
    public function setFriendToGroup($friendId, $groupId) {
        $query = App::$db->prepare('UPDATE `friends` '
                . 'SET `group_id`="'.(int)$groupId.'" '
                . 'WHERE `friend_id` = "'.(int)$friendId.'"');
        $rs = $query->execute();
        if (!$rs) {
            return FALSE;
        }
        return TRUE;
    }
    
    public function isMyFriend($userId, $friendId) {
        $query = App::$db->query('SELECT COUNT(*) as `cnt` '
                . 'FROM `friends` '
                . 'WHERE '
                    . '`friend_id` = "'.(int)$friendId.'" '
                    . 'AND `user_id` = "'.(int)$userId.'" ');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetch();
        if($result['cnt'] != 1){
            return FALSE;
        }
        return TRUE;
    }
    
    public function orderFriendsByGroups($friendsList) {
        $groupId = NULL;
        $list = array();
        foreach ($friendsList as $value) {
            if($groupId == NULL || $groupId != $value['group_id']) {
                $groupId = $value['group_id'];
                $list[$groupId] = array();
            }
            array_push($list[$groupId], $value);
        }
        return $list;
    }
    
    public function diffFriends($oldFriends, $newFriends) {
        $different = array();
        foreach ($newFriends as $newFriend) {
            $flag = false;
            $nameFlag = false;
            foreach ($oldFriends as $oldFriend) {
                if($oldFriend['fb_id'] == $newFriend['fbId']) {
                    $flag = true;
                    if($oldFriend['first_name'] != $newFriend['firstName'] || 
                            $oldFriend['last_name'] != $newFriend['lastName'] ||
                            $oldFriend['birthday'] != $newFriend['birthday']) {
                        $flag = false;
                    }
                    break;
                }
            }
            if(!$flag) {
                $different[] = $newFriend;
            }
        }
        return $different;
    }
}
