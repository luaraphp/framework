<?php

    if(! function_exists('app')) {

        function app() {
            return $app;
        }

    }

    if(! function_exists('base_path')) {

        function base_path() {
            return app()->basePath;
        }

    }