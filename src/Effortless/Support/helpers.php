<?php

    use Effortless\View\View;
    use Effortless\Support\Form;

    if(! function_exists('view')) {

        function view($name, $variables = []) {
            (new View($name, $variables))
                ->setPath()
                ->compile()
                ->render();
        }

    }

    if(! function_exists('form')) {

        function form($method = "GET", $action = "", $fields = []) {
            return (new Form($method, $action, $fields))->render();
        }

    }

    if(! function_exists('redirect')) {

        function redirect($path) {
            header("Location: $path");
        }

    }