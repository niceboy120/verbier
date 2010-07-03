<?php

namespace Verbier;

/**
 * Melcour
 *
 * Copyright (c) 2010, Hans-Kristian Koren <hanse19@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in
 * the documentation and/or other materials provided with the
 * distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright 2010 Hans-Kristian Koren <hanse19@gmail.com>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link http://github.com/Hanse/melcour
 */

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