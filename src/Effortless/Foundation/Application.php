<?php

    namespace Effortless\Foundation;

    class Application {

        const VERSION = '1.0.0';

        protected $basePath;

        protected $router;

        public function __construct($basePath = null) {
            if($basePath) {
                $this->setBasePath($basePath);
            }
        }

        protected function setBasePath($basePath) {
            $this->basePath = $basePath;
        }

        public function makeRouter($routerClass) {
            $this->router = new $routerClass();
            return $this->router;
        }
    }