<?php

    namespace Effortless\View;

    class ViewPathResolver {

        protected $name;

        protected $viewExtensions = [
            '.view.php',
            '.blade.php',
            '.html',
            '.php'
        ];

        protected $viewsDirectory = "/resources/views/"; 
        
        public function __construct(string $name) {
            $this->name = $this->fromDotToSlash($name);
        }

        protected function fromDotToSlash(string $name) {
            return implode('/', explode('.', $name));
        }

        public function getPath() {
            $path = "";
            foreach($this->viewExtensions as $extension) {
                $dir = base_path() . $this->viewsDirectory . $this->name . $extension;
                if(fopen($dir, 'r')) return $dir;
            }
            header("HTTP/1.0 404 Not Found");
        }
    }