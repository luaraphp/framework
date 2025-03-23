<?php

    namespace Effortless\View;

    use Effortless\View\Traits\HasSharedVariables;

    class View {

        use HasSharedVariables;

        protected $name;
        protected $variables;
        protected $path;
        protected $content;

        public function __construct($name, $variables = []) {
            $this->name = $name;
            $this->variables = $variables;
        }
        
        public function resolvePath() {
            return (new ViewPathResolver($this->name))->getPath();
        }

        public function compile() {
            $this->setContent(
                (new Buffering\Buffer($this->getPath()))
                    ->setVariables(array_merge($this->variables, static::$sharedVariables))
                    ->getContent()
                );
            return $this;
        }

        public function render() {
            echo $this->getContent();
            die();
        }

        public function getPath() {
            return $this->path;
        }

        public function setPath() {
            $this->path = $this->resolvePath();
            return $this;
        }

        public function getContent() {
            return $this->content;
        }

        public function setContent(string $content) {
            $this->content = $content;
            return $this;
        }
    }