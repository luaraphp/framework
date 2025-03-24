<?php

    namespace Effortless\Foundation;

    class Application {

        const VERSION = '1.0.0';

        protected $basePath;

        protected $router;

        protected $config;

        protected $providers;

        public function __construct($basePath = null) {
            if($basePath) {
                $this->setBasePath($basePath);
            }
        }

        public function getBasePath() {
            return $this->basePath;
        }

        protected function setBasePath($basePath) {
            $this->basePath = $basePath;
        }

        public function makeRouter($routerClass) {
            $this->router = new $routerClass();
            return $this->router;
        }

        public function getConfig() {
            return $this->config;
        }

        public function setConfig($config) {
            $this->config = $config;
        }

        public function bootProviders() {
            foreach (config('providers') as $provider) {
                (new $provider)->boot();
            } 
        }

    }