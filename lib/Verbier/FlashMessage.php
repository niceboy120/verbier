<?php

namespace Verbier;

/**
 * Class to manage rails-style flash messages
 *
 * @package Melcour
 * @author Hans-Kristian Koren <hanse19@gmail.com>
 */
class FlashMessage {
    
    /**
     * Flash messages indexed by the name
     *
     * @var string
     */
    static protected $messages = array();
    
    /**
     * Initialize the flash messenger. Put current messages into the messages array and clear the session
     *
     * @return void
     * @author Hans-Kristian Koren
     */
    static public function init() {
        if (!empty($_SESSION['flashMessages'])) {
            self::$messages = $_SESSION['flashMessages'];
        }
        $_SESSION['flashMessages'] = array();
    }
    
    /**
     * Set a flash message
     *
     * @param string $name 
     * @param string $message 
     * @return void
     * @author Hans-Kristian Koren
     */
    static public function set($name, $message) {
        $_SESSION['flashMessages'][$name] = $message;
    }
    
    /**
     * Get a flash message
     *
     * @param string $name 
     * @return string
     * @author Hans-Kristian Koren
     */
    static public function get($name) {
        return isset(self::$messages[$name]) ? self::$messages[$name] : NULL;
    }
}