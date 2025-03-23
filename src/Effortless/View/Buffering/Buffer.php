<?php

    namespace Effortless\View\Buffering;

    class Buffer {

        protected $path;
        protected $variables = [];

        public function __construct(string $path) {
            $this->path = $path;
        }

        public function getContent() {
            ob_start();
            extract($this->getVariables());
            include_once $this->path;
            return ob_get_clean();
        }

        public function getVariables() {
            return $this->variables;
        }

        public function setVariables($variables) {
            $this->variables = $variables;
            return $this;
        }
        
    }