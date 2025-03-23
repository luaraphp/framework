<?php

    namespace Effortless\Routing;

    class Router {

        public function __construct() {
            
        }

        protected function getCurrentPath() {
            return parse_url($_SERVER['REQUEST_URI'])['path'];
        }

        protected function getCurrentRequestMethod() {
            return $_SERVER['REQUEST_METHOD'];
        }

        public function listen() {
            $currentPath = $this->getCurrentPath();
            $currentMethod = $this->getCurrentRequestMethod();
            var_dump($currentMethod, $currentPath);
        }
    }