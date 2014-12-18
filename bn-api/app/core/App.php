<?php
namespace Core;
/**
 * Starting new aplication
 *
 * @author Ventsislav Dimitrov
 */
class App {
    
    public static $db = NULL;
    public static $urlPath = NULL;
    public static $requestBody = NULL;

    public function __construct() {
        $this->logErrors();
        if(!empty($_GET['action'])){
            $this->readParams();
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method) {
                case 'PUT':
                    if(array_key_exists(self::$urlPath, Route::$putRoutes)) {
                        $this->loadController(Route::$putRoutes[self::$urlPath]);
                    } else {
                        App::response('Do not have this page!', 404);
                    }
                    break;
                case 'POST':
                    if(array_key_exists(self::$urlPath, Route::$postRoutes)) {
                        $this->loadController(Route::$postRoutes[self::$urlPath]);
                    } else {
                        App::response('Do not have this page!', 404);
                    }
                    break;
                case 'GET':
                    if(array_key_exists(self::$urlPath, Route::$getRoutes)) {
                        $this->loadController(Route::$getRoutes[self::$urlPath]);
                    } else {
                        App::response('Do not have this page!', 404);
                    }
                    break;
                case 'DELETE':
                    if(array_key_exists(self::$urlPath, Route::$deleteRoutes)) {
                        $this->loadController(Route::$deleteRoutes[self::$urlPath]);
                    } else {
                        App::response('Do not have this page!', 404);
                    }
                    break;
                default:
                    App::response('Wrong request method!', 500);
                    break;
            }
        }
        else {
            App::response('Do not have selected method!', 404);
        }
    }
    
    /**
     * Load right controller for this request
     * 
     * @param string Method name
     */
    protected function loadController($methodName) {
        $controller = new Controller();
        if(method_exists($controller, $methodName)){
            self::$db = DB::init()->getDB();
            $this->autoloader();
            try {
                $controller->$methodName();
            }catch (\PDOException $e) {
                if(DEVELOPMENT_ENVIRONMENT){
                    App::response($e->getMessage(), $e->getCode());
                } else {
                    App::response("Database error!");
                }
            } catch (\Exception $e) {
                App::response($e->getMessage(), $e->getCode());
            }
        }
        else {
           App::response('This method does not exist!', 404); 
        }
    }

    /**
     * Make json response
     * 
     * @param array Response data
     */
    public static function response($response, $code = 200) {
        header('Content-Type: application/json');
        http_response_code($code);
        if(is_array($response)){
            echo json_encode($response);
        } else {
            echo json_encode(array('msg'=>$response));
        }
    }
    
    /**
     * Autoload project models
     */
    private function autoloader() {
        spl_autoload_register(function($class_name) {
            if(file_exists(MODELS_FOLDER . $class_name . '.php')){
                require_once MODELS_FOLDER . $class_name . '.php';
            }
        });
    }
    /**
    *   Error reporting for developers or in log file
    */
    private function logErrors() {
        if (DEVELOPMENT_ENVIRONMENT) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
            if(!is_dir(__ROOT__ . DS . __ERROR_LOG_PATH__)) {
                mkdir(__ROOT__ . DS . __ERROR_LOG_PATH__, 0777, true);
            }
            if(!file_exists(__ROOT__ . DS . __ERROR_LOG_PATH__ . DS . __ERROR_LOG_FILE__)) {
                fopen(__ROOT__ . DS . __ERROR_LOG_PATH__ . DS . __ERROR_LOG_FILE__, "");
            }
            ini_set('error_log', __ROOT__ . DS . __ERROR_LOG_PATH__ . DS . __ERROR_LOG_FILE__);
        }
    }
    /**
     * Read url params
     */
    private function readParams() {
        self::$requestBody = json_decode(http_get_request_body(), true);
        $params = array();
        if(!empty($_GET['get'])) {
            $parts = explode('/', $_GET['get']);
            $lastKey = NULL;
            foreach ($parts as $key => $value) {
                if($value != ''){
                    if($key == 0 || $key%2 == 0) {
                        $params[$value] = NULL;
                        $lastKey = $value;
                    } else {
                        $params[$lastKey] = $value;
                    }
                }
            }
        }
        self::$urlPath = $_GET['action'];
        $_GET = $params;
    }
    
    /**
     * Create connection with database
     */
    private function loadDB() {
        $dbInstance = DB::init()->getDB();
    }
}
