<?php

    namespace Effortless\View;

    use Effortless\View\Traits\HasSharedVariables;

    class View {

        use HasSharedVariables;

        protected $name;

        protected $variables;

        protected $shouldDie;

        protected $ignoreSharedVariables;

        protected $blade;

        public function __construct($name, $variables = [], $shouldDie = true, $ignoreSharedVariables = false) {
            $this->name = $name;
            $this->variables = $variables;
            $this->shouldDie = $shouldDie;
            $this->ignoreSharedVariables = $ignoreSharedVariables;
            $this->blade = (new Blade(base_path() . '/resources/views', base_path() . '/bucket/framework/views'));
        }

        public function render() {
            echo $this->getContent();
            if($this->shouldDie) die();
        }

        public function getContent() {
            if(! $this->ignoreSharedVariables) {
                $this->variables = array_merge($this->variables, static::$sharedVariables);
            }
            return $this->blade->make($this->name, $this->variables);
        }

    }