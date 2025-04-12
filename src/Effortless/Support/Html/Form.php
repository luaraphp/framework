<?php

    namespace Effortless\Support\Html;

    use Effortless\View\View;

    class Form {

        protected $method;

        protected $action;

        protected $target;

        protected $readyFields;

        protected $readyAttributes;

        protected $onSubmitPreventDefault;

        public function __construct($method = null, $action = null, $fields = null, $attributes = null) {
            $this->method ??= $method ?? "get";
            $this->action ??= $action ?? "";
            $this->readyFields = $fields;
            $this->readyAttributes = $attributes;
        }

        final protected function open() {
            $attributes = $this->readyAttributes ?? $this->attributes();
            $method = (new Attribute("method", strtolower($this->method)))->toRawHtml();
            $action = (new Attribute("action"))->toRawHtml($this->action);
            if(!($this->target == null && in_array('target', $attributes))) {
                $attributes['target'] = $this->target;
            } 
            if(!($this->onSubmitPreventDefault == false && in_array('onSubmitPreventDefault', $attributes))) {
                $attributes['onSubmitPreventDefault'] = $this->onSubmitPreventDefault;
            }
            $restOfAttributes = implode(' ', array_map(function($key, $value) {
                if($key == "onSubmitPreventDefault" && $value === true) {
                    return (new Attribute('onsubmit'))->toRawHtml('e.preventDefault()');
                }
                return (new Attribute(strtolower($key)))->toRawHtml($value);
            }, array_keys($attributes), array_values($attributes)));
            return " <form $action $method $restOfAttributes> ";
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

        public function attributes() {
            return [];
        }

        final public function render() {
            $fields = implode('', $this->fieldsWithNameAttribute($this->readyFields ?? $this->fields()));
            echo static::open() . "
                $fields
            " . static::close();
        }

        final public static function make($method = null, $action = null, $fields = null, $attributes = null) {
            return new static($method, $action, $fields, $attributes);
        }

    }  