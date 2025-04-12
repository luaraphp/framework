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

        final protected function resolveFieldsName($fieldsArray) {
            return array_map(function($fieldNameAttribute, $fieldInstance) {
                return $this->resolveFieldName($fieldInstance, $fieldNameAttribute);
            }, array_keys($fieldsArray), array_values($fieldsArray));
        }

        final protected function resolveGrouped($groupedFieldsArray) {
            return array_map(function($legendOrName, $fieldOrGroup) {
                if($fieldOrGroup instanceof Fieldset) {
                    $legend = "<legend> $legendOrName </legend>";
                    $fields = implode('', $this->resolveFieldsName($fieldOrGroup->getFields()));
                    return "
                        <fieldset>
                            $legend
                            $fields
                        </fieldset>
                    ";
                } else {
                    return $this->resolveFieldName($fieldOrGroup, $legendOrName);
                }
            }, array_keys($groupedFieldsArray), array_values($groupedFieldsArray));
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
                $unmergedFields = $this->fields();
                if(count($this->readyFields ?? []) >= 0) {
                    for($i = count($unmergedFields) - 1; $i >= 0; $i--) {
                        $fieldOrGroup = array_values($unmergedFields)[$i];
                        if($fieldOrGroup instanceof Input) {
                            if(in_array($fieldOrGroup->getRawType(), ['submit', 'button'])) {
                                $whereToSlice = $i;
                            }
                        } else {
                            if($this->grouped !== true) $this->grouped = true;
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
                $this->grouped = count(array_filter($unreadyFields, fn ($fieldOrGroup) => $fieldOrGroup instanceof Fieldset)) >= 1;
            }
            return implode('', $this->grouped == true ? $this->resolveGrouped($unreadyFields) :  $this->resolveFieldsName($unreadyFields));
        }

        final protected function getAttributes() {
            if(in_array('attributes', $this->throwIn ?? [])) {
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

        final public function render() {
            $fields = $this->getFields();
            echo static::open() . "
                $fields
            " . static::close();
        }

        final public static function field($type = 'text', $attributes = []) {
            return (new Input($type, $attributes));
        }

        final public static function group($fields) {
            return (new Fieldset($fields));
        }

        final public static function make($method = null, $action = null, $fields = null, $attributes = null, $throwIn = null) {
            return new static($method, $action, $fields, $attributes, $throwIn);
        }

    }  