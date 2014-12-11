<?php
namespace Core;

class Route {
    
    public static $getRoutes = array();
    public static $postRoutes = array();
    public static $putRoutes = array();
    public static $deleteRoutes = array();
    
    public static function get ($url, $method) {
        self::$getRoutes[$url] = $method;
    }
    
    public static function post ($url, $method) {
        self::$postRoutes[$url] = $method;
    }
    
    public static function put ($url, $method) {
        self::$putRoutes[$url] = $method;
    }
    
    public static function delete ($url, $method) {
        self::$deleteRoutes[$url] = $method;
    }
}
