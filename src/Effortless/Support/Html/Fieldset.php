<?php

    namespace Effortless\Support\Html;

    class Fieldset {

        protected $fields;

        protected $legend;

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

        final public function setLegend($legend) {
            $this->legend = $legend;
            return $this;
        }

        final public function resolveFieldsNamesBy($callback) {
            $this->fields = $callback($this->fields);
            return $this;
        }

        final public function toRawHtml() {
            $legend = "<legend> $this->legend </legend>";
            $fields = implode('', $this->getFields());
            return "
                <fieldset>
                    $legend
                    $fields
                </fieldset>
            ";
        }

    }