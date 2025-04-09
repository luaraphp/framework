<?php

    namespace Effortless\Support\Html\Form;

    class PropertyFormater {
        
        protected $name;

        protected $value = "";
        
        public function __construct(string $name, $value = null) {
            $this->name = strtolower($name);
            $this->value = $value;
        }

        public function toHtml($oneTimeValue = null) {
            if($oneTimeValue == null) {
                $oneTimeValue = $this->value;
            }
            if(trim($oneTimeValue) == "") {
                return "";
            }
            return "$this->name=" . '"' . $oneTimeValue . '"';
        }
    }