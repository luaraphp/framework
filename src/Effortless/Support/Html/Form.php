<?php

    namespace Effortless\Support\Html;

    use Effortless\View\View;

    class Form {

        protected $method;

        protected $action;

        protected $target;

        protected $readyFields;

        public function __construct($method = null, $action = null, $fields = null, $target = null) {
            $this->method ??= $method ?? "GET";
            $this->action ??= $action ?? "";
            $this->readyFields = $fields;
            $this->target ??= $target ?? "";
        }

        final protected function open() {
            $method = (new Attribute("method", strtolower($this->method)))->toRawHtml();
            $action = (new Attribute("action"))->toRawHtml($this->action);
            $target = (new Attribute('target'))->toRawHtml($this->target);
            return " <form $action $method $target> ";
        }

        final protected function close() {
            return "</form>";
        }

        final protected function fieldsWithNameAttribute($fieldsArray) {
            return array_map(function($fieldNameAttribute, $fieldInstance) {
                return $fieldInstance->setName($fieldNameAttribute)->toRawHtml();
            }, array_keys($fieldsArray), array_values($fieldsArray));
        }

        final public static function field($type = 'text', $attributes = []) {
            return (new Input($type, $attributes));
        }

        public function fields() {
            return [];
        }

        final public function render() {
            $fields = implode('', $this->fieldsWithNameAttribute($this->readyFields ?? $this->fields()));
            echo static::open() . "
                $fields
            " . static::close();
        }

        final public static function make($method = null, $action = null, $fields = null, $target = null) {
            return new static($method, $action, $fields, $target);
        }

    }  