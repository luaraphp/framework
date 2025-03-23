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