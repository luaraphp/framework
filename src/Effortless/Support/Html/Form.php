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

        protected $throwIn = [];

        public function __construct($method = null, $action = null, $fields = null, $attributes = null, $throwIn = null) {
            $this->method ??= $method ?? "get";
            $this->action ??= $action ?? "";
            if($throwIn !== null) $this->throwIn = $throwIn;
            $this->readyFields = $fields;
            $this->readyAttributes = $attributes;
        }

        final protected function fieldsWithNameAttribute($fieldsArray) {
            return array_map(function($fieldNameAttribute, $fieldInstance) {
                return $fieldInstance->setName($fieldNameAttribute)->resolveDirName()->toRawHtml();
            }, array_keys($fieldsArray), array_values($fieldsArray));
        }

        public function fields() {
            return [];
        }

        public function attributes() {
            return [];
        }

        final protected function getFields() {
            $whereToSlice = null;
            if(in_array('fields', $this->throwIn ?? []) === true) {
                if(count($this->readyFields ?? [])  === 0) {
                    $unreadyFields = $this->fields();
                } else {
                    $fieldsToMergeWith = $this->fields();
                    for($i = count($fieldsToMergeWith) - 1; $i >= 0; $i--) {
                        if(in_array(array_values($fieldsToMergeWith)[$i]->getRawType(), ['submit', 'button'])) {
                            $whereToSlice = $i;
                        } else break;
                    }
                    if($whereToSlice == null) {
                        $unreadyFields = array_merge($this->fields(), $this->readyFields);
                    } else {
                        $unreadyFields = array_merge(
                            array_slice($this->fields(), 0, $whereToSlice),
                            $this->readyFields,
                            array_slice($this->fields(), $whereToSlice)
                        );
                    }
                }
                
            } else {
                $unreadyFields = $this->readyFields ?? $this->fields();
            }
            return implode('', $this->fieldsWithNameAttribute($unreadyFields));
        }

        final protected function getAttributes() {
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

            return " $action $method $restOfAttributes ";
        }

        final protected function open() {
            $attributes = $this->getAttributes();
            return " <form $attributes> ";
        }

        final protected function close() {
            return "</form>";
        }

        final public function render() {
            $fields = $this->getFields();
            echo static::open() . "
                $fields
            " . static::close();
        }

        final public static function field($type = 'text', $attributes = []) {
            return (new Input($type, $attributes));
        }

        final public static function make($method = null, $action = null, $fields = null, $attributes = null, $throwIn = null) {
            return new static($method, $action, $fields, $attributes, $throwIn);
        }

    }  