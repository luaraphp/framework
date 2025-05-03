<?php

    namespace Effortless\Support\Html;

    class Form {

        protected $method;

        protected $action;

        protected $target;

        protected $readyFields;

        protected $readyAttributes;

        protected $onSubmitPreventDefault;

        protected $throwIn = [];

        protected $grouped = false;

        public function __construct($method = null, $action = null, $fields = null, $attributes = null, $throwIn = null) {
            $this->method ??= $method ?? "get";
            $this->action ??= $action ?? "";
            if($throwIn !== null) $this->throwIn = $throwIn;
            $this->readyFields = $fields;
            $this->readyAttributes = $attributes;
        }

        final protected function resolveFieldName($field, $name) {
            return $field->setName($name)->resolveDirName()->toRawHtml();
        }

        final protected function resolveFieldsNames($fieldsArray) {
            return array_map(function($fieldNameAttribute, $fieldInstance) {
                return $this->resolveFieldName($fieldInstance, $fieldNameAttribute);
            }, array_keys($fieldsArray), array_values($fieldsArray));
        }

        final protected function resolveGrouped($groupedFieldsArray) {
            return array_map(function($legendOrName, $fieldOrGroup) {
                return $fieldOrGroup instanceof Fieldset === true 
                    ? $fieldOrGroup->setLegend($legendOrName)->resolveFieldsNamesBy(fn($fields) => $this->resolveFieldsNames($fields))->toRawHtml()
                    : $this->resolveFieldName($fieldOrGroup, $legendOrName);
            }, array_keys($groupedFieldsArray), array_values($groupedFieldsArray));
        }

        public function fields() {
            return [];
        }

        public function attributes() {
            return [];
        }

        final protected function mustThrowIn($whatToThrowIn) {
            return in_array($whatToThrowIn, $this->throwIn ?? []) === true;
        }

        final protected function mustThrowInFields() {
            return $this->mustThrowIn('fields'); 
        }

        final protected function mustThrowInAttributes() {
            return $this->mustThrowIn('attributes');
        }

        final protected function hasReadyFields() {
            return count($this->readyFields ?? []) >= 0;
        }

        final protected function isGrouped() {
            return $this->grouped === true;
        }

        final protected function isGroupedNotSetYet() {
            return $this->grouped !== true;
        }

        final protected function setGrouped($value) {
            return $this->grouped = $value;
        }

        final protected function getFields() {
            $whereToSlice = null;
            if($this->mustThrowInFields()) {
                $unmergedFields = $this->fields();
                if($this->hasReadyFields()) {
                    for($i = count($unmergedFields) - 1; $i >= 0; $i--) {
                        $fieldOrGroup = array_values($unmergedFields)[$i];
                        if($fieldOrGroup instanceof Input) {
                            if($fieldOrGroup->isTypeSubmitOrButton()) {
                                $whereToSlice = $i;
                            }
                        } else {
                            if($this->isGroupedNotSetYet()) $this->setGrouped(true);
                            $fieldsetLabel = array_keys($unmergedFields)[$i];
                            if(in_array($fieldsetLabel, array_keys($this->readyFields) ?? []) === true) {
                                $unmergedFields[$fieldsetLabel] = $fieldOrGroup->merge($this->readyFields[$fieldsetLabel]->getFields());
                                unset($this->readyFields[$fieldsetLabel]);
                            }
                        }
                    }
                    if($whereToSlice == null) {
                        $unreadyFields = array_merge($unmergedFields, $this->readyFields);
                    } else {
                        $unreadyFields = array_merge(
                            array_slice($unmergedFields, 0, $whereToSlice),
                            $this->readyFields,
                            array_slice($unmergedFields, $whereToSlice)
                        );
                    }
                }
            } else {
                $unreadyFields = $this->readyFields ?? $this->fields();
                $this->setGrouped(count(array_filter($unreadyFields, fn ($fieldOrGroup) => $fieldOrGroup instanceof Fieldset)) >= 1);
            }
            return implode('', $this->isGrouped() ? $this->resolveGrouped($unreadyFields) :  $this->resolveFieldsNames($unreadyFields));
        }

        final protected function getAttributes() {
            if($this->mustThrowInAttributes()) {
                $attributes = array_merge($this->attributes(), $this->readyAttributes);
            } else $attributes = $this->readyAttributes ?? $this->attributes();
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

        final protected function format($unformatedHtml) {
            $dom = new \DOMDocument();
            @$dom->loadHTML($unformatedHtml);
            $dom->formatOutput = true;
            $dom->preserveWhiteSpace = false;
            return $dom->saveHTML($dom->documentElement->childNodes->item(0)->childNodes->item(0));
        }

        final public function render() {
            $fields = $this->getFields();
            echo $this->format(static::open() . "
                $fields
            " . static::close());
        }

        final public static function field($type = 'text', $attributesOrOptions = [], $attributes = []) {
            return (new Input($type, $attributesOrOptions, $attributes));
        }

        final public static function group($fields) {
            return (new Fieldset($fields));
        }

        final public static function make($method = null, $action = null, $fields = null, $attributes = null, $throwIn = null) {
            return new static($method, $action, $fields, $attributes, $throwIn);
        }

    }  