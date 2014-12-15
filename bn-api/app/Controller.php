<?php
namespace Core;
/**
 * Define all paths
 *
 * @author Ventsislav Dimitrov
 */
class Controller {

    public function registerUser() {
        $fbId = trim(App::$requestBody['fbId']);
        $firstName = trim(App::$requestBody['firstName']);
        $lastName = trim(App::$requestBody['lastName']);
        $userBirthday = trim(App::$requestBody['userBirthday']);
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
        //echo 'test';
        //echo date('Y-m-d');
        /*$testFriends[] = array('firstName' => "Gosho", 'lastName' => "Peshev", 'birthday'=>"", 'userId' => 4);
        $testFriends[] = array('firstName' => "Niki", 'lastName' => "Seksa", 'birthday'=>"1992-12-3", 'userId' => 4);
        
        
        $fbArray[] = array('firstName' => "Gosho", 'lastName' => "Peshev", 'birthday'=>"");
        $fbArray[] = array('firstName' => "Niki", 'lastName' => "Seksa", 'birthday'=>"1992-12-3");
        $fbArray[] = array('firstName' => "Kiro", 'lastName' => "Maxa", 'birthday'=>"1992-08-10");
        //var_dump($testFriends);
        //var_dump($fbArray);
        var_dump(array_merge(array_diff($testFriends,$fbArray),array_diff($fbArray,$testFriends)));*/
        //$user = new \User();
        //var_dump($user->isMyFriend(4, 7));
        //var_dump($user->setFriendToGroup(72, 1));
    }
    //TODO
    public function setFriends() {
        $fbId = trim(App::$requestBody['fbId']);
        $friendsList = App::$requestBody['friendsList'];
        
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
        $user->addFriends($userId, $friendsList);
    }
    
    public function getFriends() {
        $fbId = $_GET['fbId'];
        $validator = new \Validator();
        if(!$validator->validateFbId($fbId)) {
            throw new \Exception('No facebook id!', 400);
        }
        $user = new \User();
        $userId = $user->getUserId($fbId);
        if($userId === FALSE) {
            throw new \Exception('Can not find user id!', 400);
        }
        $friends = $user->getFriends($userId);
        $friends = $user->orderFriendsByGroups($friends);
        App::response($friends, 201);
    }
    
    public function updateGroup() {
        $fbId = App::$requestBody['fbId'];
        $friendId = (int)App::$requestBody['friendId'];
        $groupId = (int)App::$requestBody['groupId'];
        $validator = new \Validator();
        if(!$validator->validateFbId($fbId)) {
            throw new \Exception('No facebook id!', 400);
        }
        $group = new \Group();
        if(!$group->isValidGroupId($groupId)) {
            throw new \Exception('Invalid group id!', 400);
        }
        $user = new \User();
        $userId = $user->getUserId($fbId);
        if(!$user->isMyFriend($userId, $friendId)) {
            throw new \Exception('This user is not my friend!', 400);
        }
        if(!$user->setFriendToGroup($friendId, $groupId)) {
            throw new \Exception('Somethings goes wrong!!', 400);
        }
        App::response(array('moved' => true), 201);
    }
}
