<?php

    namespace Effortless\View\Traits;

    trait HasSharedVariables {

        protected static $sharedVariables = [];

        public static function share($key, $value = null) {

            if(is_array($key)) {
                static::$sharedVariables = array_merge(static::$sharedVariables, $key);
            } else {
                static::$sharedVariables[$key] = $value;
            }
            
        }

    }