<?php

    namespace Effortless\Support\Html;

    class Input {

        protected $type;

        protected $name = "";

        protected $rawName = "";

        protected $label = "";

        protected $datasetOptions;

        protected $attributes;

        protected $defaults = [];

        protected $additions = ['html', 'label'];

        protected $html = "";
        
        public function __construct($type = 'text', $attributes = []) {
            $this->type = (new Attribute("type", strtolower($type)))->toRawHtml();
            $this->attributes = $attributes;
        }

        protected function setAttribute($name, $value = true) {
            $this->attributes[$name] = $value;
            return $this;
        }

        protected function isAddition($attribute) {
            return in_array($attribute, $this->additions);
        }

        public function setName($name) {
            $this->rawName = $name;
            $this->name = (new Attribute("name", $name))->toRawHtml();
            return $this;
        }

        public function required() {
            return $this->setAttribute('required');
        }

        public function disabled() {
            return $this->setAttribute('disabled');
        }

        public function readOnly() {
            return $this->setAttribute('readonly');
        }

        public function value($value) {
            return $this->setAttribute('value', $value);
        }

        public function class($className) {
            return $this->setAttribute('class', $className);
        }

        public function id($id) {
            return $this->setAttribute('id', $id);
        }

        public function min($min) {
            return $this->setAttribute('min', $min);
        }

        public function max($max) {
            return $this->setAttribute('max', $max);
        }

        public function size($size) {
            return $this->setAttribute('size', $size);
        }

        public function maxLength($maxLength) {
            return $this->setAttribute('maxlength', $maxLength);
        }

        public function noAutoComplete() {
            return $this->setAttribute('autocomplete', 'off');
        }

        public function autoFocus() {
            return $this->setAttribute('autofocus');
        }

        public function action($action) {
            return $this->setAttribute('formaction', $action);
        }

        public function formEnctype($enctype) {
            return $this->setAttribute('formenctype', $enctype);
        } 

        public function formEnctypeMultiPartFormData() {
            return $this->formEnctype('multipart/form-data');
        }

        public function formMethod($method) {
            return $this->setAttribute('formmethod', $method);
        }

        public function formMethodPost() {
            return $this->formMethod('POST');
        }

        public function formNoValidate() {
            return $this->setAttribute('formnovalidate');
        }

        public function formTarget($target) {
            return $this->setAttribute('formtarget', $target);
        }

        public function formTargetBlank() {
            return $this->formTarget('_blank');
        }

        public function multiple() {
            return $this->setAttribute('mutiple');
        }

        public function step($step) {
            return $this->setAttribute('step', $step);
        }

        public function width($width) {
            return $this->setAttribute('width', $width);
        }

        public function height($height) {
            return $this->setAttribute('height', $height);
        }

        public function dataset($options = []) {
            $this->datasetOptions = $options;
            return $this;
        }

        public function toRawHtml() {
            $restOfAttributes = implode(' ', array_map(function($key, $value) {
                if($this->isAddition($key) === true) {
                    switch ($key) {
                        case 'html':
                            $this->html = $value;
                            break;
                        case "label":
                            $this->label = "$value: ";
                            break;
                        default:
                            break;
                    }
                    return "";
                } else {
                    $defaultValue = null;
                    if(array_key_exists($key, $this->defaults) === true) {
                        $defaultValue = $this->defaults[$key];
                    }
                    if($value === false) return "";
                    return (new Attribute(strtolower($key), $defaultValue))->toRawHtml($value);
                }
            }, array_keys($this->attributes), array_values($this->attributes)));
            if($this->datasetOptions === null) {
                return "
                    $this->label <input $this->type $this->name $restOfAttributes /> $this->html
                ";
            } else {
                $listAttribute = (new Attribute('list', $this->rawName))->toRawHtml();
                $idAttribute = (new Attribute('id', $this->rawName))->toRawHtml();

                $options = implode('', array_map(function($option) {
                    $value = (new Attribute('value', $option))->toRawHtml();
                    return "<option $value> $option </option>";
                }, $this->datasetOptions));

                $dataset = "
                    <datalist $idAttribute>
                        $options
                    </datalist>
                ";

                return "
                    $this->label <input $this->type $this->name $listAttribute $restOfAttributes />

                    $dataset

                    $this->html
                ";
            }
            
        }
    }