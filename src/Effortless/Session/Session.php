<?php

    namespace Effortless\Session;

    class Session {

        const FLASH_REGISTER_PREFIX = "__flash_";

        static protected $started = false;

        static protected function setStarted($started = false) {
            static::$started = $started;
        }

        static protected function unsureSessionStarted() {
            if(! (static::$started || isset($_SESSION))) {
                session_start();
                static::setStarted(true);
            }
        }

        static public function all() {
            static::unsureSessionStarted();
            return $_SESSION;
        }

        static public function exists($key) {
            return array_key_exists($key, static::all());
        }

        static public function has($key) {
            return static::exists($key) && !(static::get($key) == null); 
        }

        static public function get($key, $default = null) {
            static::unsureSessionStarted();
            $value = $_SESSION[$key] ?? $default;
            if(static::isFlashed($key)) {
                static::unregisterFlash($key);
            }
            return $value;
        }

        static public function set($key, $value = null) {
            static::unsureSessionStarted();
            $_SESSION[$key] = $value;
            return true;
        }

        static public function setIfNotExist($key, $value) {
            if(! static::exists($key)) {
                static::set($key, $value);
            }
        }

        static public function forget($key) {
            static::unsureSessionStarted();
            unset($_SESSION[$key]);
        }

        static public function pull($key, $default = null) {
            $value = static::get($key, $default);
            static::forget($key);
            return $value;
        }

        static protected function resolveFlashRegisterKey($key) {
            return Session::FLASH_REGISTER_PREFIX . $key;
        }

        static protected function isFlashed($key) {
            return static::exists(static::resolveFlashRegisterKey($key));
        }

        static protected function registerFlash($key) {
            static::set(static::resolveFlashRegisterKey($key), true);
        }

        static protected function unregisterFlash($key) {
            static::forget($key);
            static::forget(static::resolveFlashRegisterKey($key));
        }

        static public function flash($key, $value) {
            static::set($key, $value);
            static::registerFlash($key);
        }

        static public function increment($key, $incrementBy = 1) {
            $keyToIncrement = static::get($key);
            if(is_integer($keyToIncrement)) {
                static::set($key, $keyToIncrement + $incrementBy);
            }
        }

        static public function decrement($key, $decrementBy = 1) {
            $keyToDecrement = static::get($key);
            if(is_integer($keyToDecrement)) {
                $static::set($key, $keyToDecrement + $decrementBy);
            }
        }
    } 