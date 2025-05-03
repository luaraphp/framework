<?php

    namespace Effortless\Support\Html;

    class Label {
        
        protected $content;

        protected $for;
        
        public function __construct(string $content = null, string $for = null) {
            $this->content = $content;
            $this->for = $for;
        }

        public function build($content = null) {
            if($content == null) {
                $content = $this->content;
            }

            $element = new \DOMElement('label');
            $element->nodeValue = $this->content;
            if(!is_null($this->for)) {
                $element->setAttribute('for', $this->for);
            }
            return $element;
        }
    }