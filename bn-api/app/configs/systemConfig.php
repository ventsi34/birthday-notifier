<?php
/* Show or stop system errors */
define('DEVELOPMENT_ENVIRONMENT', true);
/* Directory separator short */
define('DS', DIRECTORY_SEPARATOR);
/* Project URI */
define('URI', (isset($_SERVER['HTTPS']))? 'https://'.$_SERVER['HTTP_HOST']: 'http://'.$_SERVER['HTTP_HOST']);
/* Path to model folder */
define('MODELS_FOLDER', '..' . DS . 'app' . DS . 'models/');
//Root directory
define('__ROOT__', dirname(dirname(dirname(__FILE__))));
//Path for error logs.
define("__ERROR_LOG_PATH__", "logs");
//File name for error logs.
define("__ERROR_LOG_FILE__", "error.log");