<?php

    use Effortless\View\View;
    use Effortless\Support\Html\Form;

    if(! function_exists('view')) {

        function view($name, $variables = []) {
            (new View($name, $variables))
                ->setPath()
                ->compile()
                ->render();
        }

    }

    if(! function_exists('form')) {

        function form($method = null, $action = null, $fields = null, $attributes = null, $throwIn = null) {
            return (new Form($method, $action, $fields, $attributes, $throwIn));
        }

    }

    if(! function_exists('redirect')) {

        function redirect($path) {
            header("Location: $path");
        }

    }