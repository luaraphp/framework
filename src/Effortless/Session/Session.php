<?php

    namespace Effortless\Session;

    class Session {

        protected static $started = false;

        static protected function setStarted($started = false) {
            static::$started = $started;
        }

        static protected function unsureSessionStarted() {
            if(! static::$started) {
                session_start();
                static::setStarted(true);
            }
        }

        static public function __callStatic($method, $args) {
            if(in_array($method, ['all', 'get', 'set', 'forget'])) {
                static::unsureSessionStarted();
            }

            if(! method_exists(__CLASS__, $method)) {
                throw new \BadMethodCallException("Method $method does not exist.");
            }
            return forward_static_call_array([__CLASS__, $method], $args);
        }

        static public function all() {
            return $_SESSION;
        }

        static public function exists($key) {
            return array_key_exists($key, static::all());
        }

        static public function has($key) {
            return static::exists($key) && !(static::get($key) == null); 
        }

        static public function get($key, $default = null) {
            return $_SESSION[$key] ?? $default;
        }

        static public function set($key, $value) {
            $_SESSION[$key] = $value;
            return true;
        }

        static public function setIfNotExist($key, $value) {
            if(! static::exists($key)) {
                static::set($key, $value);
            }
        }

        static public function forget($key) {
            unset($_SESSION[$key]);
        }

        static public function pull($key, $default = null) {
            $value = static::get($key, $default);
            static::forget($key);
            return $value;
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