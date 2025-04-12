<?php

    namespace Effortless\Support\Html;

    class Fieldset {

        protected $fields;

        public function __construct($fields = null) {
            $this->fields = $fields;
        }

        final public function getFields() {
            return $this->fields;
        }

    }