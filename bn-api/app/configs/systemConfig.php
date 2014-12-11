<?php
/* Directory separator short */
define('DS', DIRECTORY_SEPARATOR);
/* Project URI */
define('URI', (isset($_SERVER['HTTPS']))? 'https://'.$_SERVER['HTTP_HOST']: 'http://'.$_SERVER['HTTP_HOST']);
/* Show or stop system errors */
define('DEVELOPMENT_ENVIRONMENT', true);
/* Path to model folder */
define('MODELS_FOLDER', '..' . DS . 'app' . DS . 'models/');