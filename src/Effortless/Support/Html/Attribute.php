<?php

    namespace Effortless\Support\Html;

    class Attribute {
        
        protected $name;

        protected $value = "";

        protected $noValueAttributes = ['disabled', 'readonly', 'required', 'multiple', 'formnovalidate', 'autofocus'];
        
        public function __construct(string $name, $value = "") {
            $this->name = strtolower($name);
            $this->value = $value;
        }

        public function toRawHtml($oneTimeValue = null) {
            if($oneTimeValue == null) {
                $oneTimeValue = $this->value;
            }
            if(trim($oneTimeValue) == "") {
                return "";
            }
            if($oneTimeValue === true && in_array($this->name, $this->noValueAttributes)) {
                return $this->name;
            }
            return "$this->name=" . '"' . $oneTimeValue . '"';
        }
    }