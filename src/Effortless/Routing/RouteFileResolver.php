<?php

    namespace Effortless\Routing;

    class RouteFileResolver {

        const INDEX_PATH = '/';

        protected $fileDirectory = null;

        public function resolve($path) {
            if($path == static::INDEX_PATH) {
                if(file_exists(base_path() . '/routes/index.php')) {
                    $this->fileDirectory = base_path() . '/routes/index.php';
                }
            } else {
                if(file_exists(base_path() . "/routes/$path.php")) {
                    $this->fileDirectory = base_path() . "/routes/$path.php";
                }
                if(file_exists(base_path() . "/routes/$path/index.php")) {
                    $this->fileDirectory = base_path() . "/routes/$path/index.php";
                } 
            }
            return $this;
        }

        public function getEndpoint() {
            if($this->fileDirectory == null) {
                return null;
            }
            return require($this->fileDirectory);
        }
    }