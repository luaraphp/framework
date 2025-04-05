<?php

    use Effortless\View\View;

    if(! function_exists('view')) {

        function view($name, $variables = []) {
            (new View($name, $variables))
                ->setPath()
                ->compile()
                ->render();
        }

    }

    if(! function_exists('redirect')) {

        function redirect($path) {
            header("Location: $path");
        }

    }