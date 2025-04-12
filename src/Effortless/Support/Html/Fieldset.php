<?php

    namespace Effortless\Support\Html;

    class Fieldset {

        protected $fields;

        public function __construct($fields = null) {
            $this->fields = $fields;
        }

        final public function merge($fieldsToMerge) {
            $this->fields = array_merge($this->fields, $fieldsToMerge);
            return $this;
        }

        final public function getFields() {
            return $this->fields;
        }

    }