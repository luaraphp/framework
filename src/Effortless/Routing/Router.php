<?php

    namespace Effortless\Routing;

    class Router {

        const INDEX_PATH = '/';

        public function __construct() {
            
        }

        protected function getCurrentPath() {
            return parse_url($_SERVER['REQUEST_URI'])['path'];
        }

        protected function getCurrentRequestMethod() {
            return $_SERVER['REQUEST_METHOD'];
        }

        protected function resolve($path) {
            if($path == static::INDEX_PATH) {
                if(file_exists(base_path() . '/routes/index.php')) {
                    $instance = require(base_path() . '/routes/index.php');
                    $instance();
                }
            }
        }

        public function listen() {
            $currentPath = $this->getCurrentPath();
            $currentMethod = $this->getCurrentRequestMethod();
            $this->resolve($currentPath);
        }
    }