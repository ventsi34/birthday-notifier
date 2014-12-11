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
        if(empty($fbId)) {
            throw new \Exception('No facebook id!', 401);
        }
        if(mb_strlen($firstName) < 2 || mb_strlen($firstName) > 40
                || mb_strlen($lastName) < 2 || mb_strlen($lastName) > 40) {
            throw new \Exception('The name is not valid!', 401);
        }
        if(!$this->validateDate($userBirthday)) {
            throw new \Exception('Date is not valid!!', 401);
        }
        $user = new \User();
        App::response(array('msg'=>$user->register($fbId, $firstName, $lastName, $userBirthday)), 201);
    }
    
    public function test($params = NULL) {
        //throw new Exception('Test ecxeption!', 301);
        //var_dump(App::$db);
        $res = App::$db->query('SELECT * FROM `users`');
        $res->setFetchMode(\PDO::FETCH_ASSOC);
 
        while($row = $res->fetch()) {
            var_dump($row);
            echo '<br>';
        }
    }
    
    public function validateDate($date) {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }
}
