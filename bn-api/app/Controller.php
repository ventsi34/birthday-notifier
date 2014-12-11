<?php
namespace Core;
/**
 * Define all paths
 *
 * @author Ventsislav Dimitrov
 */
class Controller {

    public function registerUser($params = NULL) {
        $user = new \User();
        App::response(array('msg'=>$user->register()), 201);
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
}
