<?php

    use Effortless\Container\Container as Container;

    if(! function_exists('app')) {

        function app() {
            return Container::getInstance();
        }

    }

    if(! function_exists('base_path')) {

        function base_path() {
            return app()->getBasePath();
        }

    }

    if(! function_exists('config')) {

        function config($key = null) {
            $value = app()->getConfig(); 
            foreach (explode('.', $key) as $miniKey) {
                $value = $value[$miniKey];
            };
            return $value;
        }
        
    }