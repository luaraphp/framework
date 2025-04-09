<?php

    namespace Effortless\View;

    use Effortless\View\Traits\HasSharedVariables;

    class View {

        use HasSharedVariables;

        protected $name;

        protected $variables;

        protected $path;
        
        protected $content;

        protected $shouldDie;

        protected $ignoreSharedVariables;

        public function __construct($name, $variables = [], $shouldDie = true, $ignoreSharedVariables = false) {
            $this->name = $name;
            $this->variables = $variables;
            $this->shouldDie = $shouldDie;
            $this->ignoreSharedVariables = $ignoreSharedVariables;
        }
        
        public function resolvePath() {
            return (new ViewPathResolver($this->name))->getPath();
        }

        public function compile() {
            if(! $this->ignoreSharedVariables) {
                $variables = array_merge($this->variables, static::$sharedVariables);
            } else {
                $variables = $this->variables;
            }
            $this->setContent(
                (new Buffering\Buffer($this->getPath()))
                    ->setVariables($variables)
                    ->getContent()
                );
            return $this;
        }

        public function render() {
            echo $this->getContent();
            if($this->shouldDie) die();
        }

        public function getPath() {
            return $this->path;
        }

        public function setPath($staticPath = null) {
            if($staticPath == null) {
                $this->path = $this->resolvePath();
            } else {
                $this->path = $staticPath;
            }
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