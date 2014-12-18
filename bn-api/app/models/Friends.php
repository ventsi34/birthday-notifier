<?php

/**
 * Manage friends
 *
 * @author Ventsislav Dimitrov
 */
use Core\App;
class Friends {
    
    public function getFriendGroupByFbId($fbId) {
        $query = App::$db->query('SELECT `group_id` '
                . 'FROM `friends` '
                . 'WHERE '
                . '`fb_id` = "'.addslashes($fbId).'"');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetch();
        return $result['group_id'];
    }
}

