<?php

/**
 * Manage friend groups
 *
 * @author Ventsislav Dimitrov
 */
use Core\App;
class Group {
    
    public function isValidGroupId($id) {
        $query = App::$db->query('SELECT COUNT(*) as `cnt` '
                . 'FROM `groups` '
                . 'WHERE '
                    . '`group_id` = "'.(int)$id.'"');
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $result = $query->fetch();
        if($result['cnt'] != 1){
            return FALSE;
        }
        return TRUE;
    }
    
}
