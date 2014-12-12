<?php
namespace Core;
/**
 * Define all paths
 *
 * @author Ventsislav Dimitrov
 */
class Controller {

    public function registerUser() {
        $fbId = trim($_POST['fbId']);
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $userBirthday = trim($_POST['userBirthday']);
        $validator = new \Validator();
        
        if(!$validator->validateFbId($fbId)) {
            throw new \Exception('No facebook id!', 400);
        }
        
        if(!$validator->validateName($firstName)|| !$validator->validateName($lastName)) {
            throw new \Exception('The name is not valid!', 400);
        }
        if(!$validator->validateDate($userBirthday)) {
            throw new \Exception('Date is not valid!', 400);
        }
        $user = new \User();
        if($user->getUserId($fbId) === FALSE) {
            if($user->register($fbId, $firstName, $lastName, $userBirthday) != true) {
                throw new \Exception('Database error!', 400);
            }
            App::response(array('is_registered' => false, 'created' => true), 201);
        } else {
            App::response(array('is_registered' => true), 201);
        }
    }
    
    public function test($params = NULL) {
        //throw new Exception('Test ecxeption!', 301);
        //var_dump(App::$db);
        /*$res = App::$db->query('SELECT * FROM `users`');
        $res->setFetchMode(\PDO::FETCH_ASSOC);
 
        while($row = $res->fetch()) {
            var_dump($row);
            echo '<br>';
        }*/
        //$user = new \User();
        //var_dump($user->checkRegistration('asdasf23424tda12'));
        echo 'test';
        //echo date('Y-m-d');
    }
    
    public function setFriends() {
        $fbId = trim($_POST['fbId']);
        $friendsList = $_POST['friendsList'];
        
        if(!$validator->validateFbId($fbId)) {
            throw new \Exception('No facebook id!', 400);
        }
        
        $user = new \User();
        $userId = $user->getUserId($fbId);
        if($userId === FALSE) {
            throw new \Exception('Invalid facebook user!', 402);
        }
        
        if(!is_array($friendsList)) {
            throw new \Exception('Invalid friends list!', 400);
        }
        
        
    }
    
    public function getFriends() {
        
    }
    
    public function updateGroup() {
        
    }
}
