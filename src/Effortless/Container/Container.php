<?php

    namespace Effortless\Container;

    class Container {

        static $instances = [];

        static public function setInstance($key, $instance) {
            static::$instances[$key] = $instance;
        }

        static public function getInstance($key = 'app') {
            return static::$instances[$key];
        }

    }