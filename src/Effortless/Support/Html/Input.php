<?php

    namespace Effortless\Support\Html;

    class Input {

        protected $type;

        protected $rawType;

        protected $name = "";

        protected $rawName = "";

        protected $label = "";

        protected $datasetOptions;

        protected $attributes;

        protected $defaults = [];

        protected $additions = ['html', 'label'];

        protected $html = "";

        protected $unresolvedDirName;

        protected $options = [];

        protected $lowerCaseValues = false;
        
        public function __construct($type = 'text', $attributesOrOptions = [], $attributes = []) {
            $this->rawType = strtolower($type);
            if(in_array($this->rawType, ['select', 'radio']) === true) {
                $this->options = $attributesOrOptions;
                $this->attributes = $attributes;
            } else {
                $this->type = (new Attribute("type", $this->rawType))->toRawHtml();
                $this->attributes = $attributesOrOptions;
            }
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

        public function resolveDirName() {
            if($this->unresolvedDirName === true) {
                $this->setAttribute('dirname', $this->rawName . '.dir');
            } else if($this->unresolvedDirName !== null) $this->setAttribute('dirname', $this->unresolvedDirName);
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
            return $this->setAttribute($this->rawType == "text" ? 'minlength' : 'min', $min);
        }

        public function max($max) {
            return $this->setAttribute($this->rawType == "text" ? 'maxlength' : 'max', $max);
        }

        public function size($size) {
            return $this->setAttribute('size', $size);
        }

        public function maxLength($maxLength) {
            return $this->setAttribute('maxlength', $maxLength);
        }

        public function autoComplete($autoComplete) {
            return $this->setAttribute('autocomplete', $autoComplete);
        }

        public function withAutoComplete() {
            return $this->autoComplete('on');
        }

        public function withoutAutoComplete() {
            return $this->autoComplete('off');
        }

        public function autoCorrect($autoCorrect) {
            return $this->setAttribute('autocorrect', $autoCorrect);
        }

        public function withAutoCorrect() {
            return $this->autoCorrect('on');
        }

        public function withoutAutoCorrect() {
            return $this->autoCorrect('off');
        }

        public function autoCapitalize($autoCapitalize) {
            return $this->setAttribute('autocapitalize', $autoCapitalize);
        }

        public function withAutoCapitalizeWords() {
            return $this->autoCapitalize('words');
        }

        public function autoCapitalizeSentences() {
            return $this->autoCapitalize('sentences');
        }

        public function autoCapitalizeCharacters() {
            return $this->autoCapitalize('characters');
        }

        public function withoutAutoCapitalize() {
            return $this->autoCapitalize('none');
        }

        public function spellCheck($spellCheck) {
            return $this->setAttribute(
                'spellcheck', 
                is_bool($spellCheck) 
                    ? $spellCheck
                        ? 'true' 
                        : 'false'
                    : $spellCheck
            );
        }

        public function withSpellCheck() {
            return $this->spellCheck(true);
        }

        public function withoutSpellCheck() {
            return $this->spellCheck(false);
        }

        public function mode($mode) {
            return $this->setAttribute('inputmode', $mode);
        }

        public function modeUrl() {
            return $this->mode('url');
        }

        public function modeTel() {
            return $this->mode('tel');
        }

        public function modeEmail() {
            return $this->mode('email');
        }

        public function modeSearch() {
            return $this->mode('search');
        }

        public function modeNumeric() {
            return $this->mode('numeric');
        }

        public function accept($fileExtensions) {
            if(is_string($fileExtensions)) {
                $fileExtensions = [$fileExtensions];
            }
            $extensions = implode(', ', array_map(fn ($extension) => '.' . $extension, $fileExtensions));
            return $this->setAttribute('accept', $extensions);
        }

        public function acceptImageOnly() {
            return $this->setAttribute('accept', 'image/*');
        }

        public function acceptAudioOnly() {
            return $this->setAttribute('accept', 'audio/*');
        }

        public function acceptVideoOnly() {
            return $this->setAttribute('accept', 'video/*');
        }

        public function acceptMediaOnly() {
            return $this->setAttribute('accept', 'image/*, audio/*, video/*');
        }

        public function acceptPdfOnly() {
            return $this->setAttribute('accept', 'application/pdf');
        }

        public function acceptsDocumentsOnly() {
            return $this->setAttribute('accept', '.docx, application/msword, .pdf, text/plain');
        }

        public function capture($capture) {
            return $this->setAttribute('capture', $capture);
        }

        public function captureUser() {
            return $this->capture('user');
        }

        public function captureEnvironment() {
            return $this->capture('environment');
        }

        public function dir($dir) {
            return $this->setAttribute('dir', $dir);
        }

        public function dirRightToLeft() {
            return $this->setAttribute('dir', 'rtl');
        }

        public function dirLeftToRight() {
            return $this->setAttribute('dir', 'ltr');
        }

        public function withDirName($name = null) {
           $this->unresolvedDirName = $name ?? true;
           return $this;
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

        public function checked() {
            return $this->setAttribute('checked');
        }

        public function placeholder($placeholder) {
            return $this->setAttribute('placeholder', $placeholder);
        }

        public function label($label) {
            return $this->setAttribute('label', $label);
        }

        public function rawLabel($label) {
            return $this->label('raw:' . label);
        }

        public function html($html) {
            return $this->setAttribute('html', $html);
        }

        public function br($times = 1) {
            return $this->html(($this->attributes['html'] ?? "") . str_repeat(' <br> ', $times));
        }

        public function withLowerCasedValues() {
            $this->lowerCaseValues = true;
            return $this;
        }

        public function getRawType() {
            return $this->rawType;
        }

        public function toRawHtml() {
            $restOfAttributes = implode(' ', array_map(function($key, $value) {
                if($this->isAddition($key) === true) {
                    switch ($key) {
                        case 'html':
                            $this->html = $value;
                            break;
                        case "label":
                            if(substr(ltrim($value), 0, 4) === "raw:") {
                                $value = substr(ltrim($value), 4);
                            } else {
                                if(!(substr(rtrim($value), -(strlen(":"))) == ":")) $value .= ": ";
                                if(isset($this->attributes['id'])) {
                                    $forAttribute = (new Attribute('for', $this->attributes['id']))->toRawHtml();
                                    $value = "<label $forAttribute> $value </label>";
                                }
                            }
                            $this->label = $value;
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
                if($this->rawType === "select") {
                    $values = array_keys($this->options);
                    $labels = array_values($this->options);
                    if(count($values) + count($labels) !== 0) {
                        $values = array_map(function($label) {
                            return $this->lowerCaseValues == true ? strtolower($label) : $label;
                        }, $labels);
                    }
                    $options = implode('', array_map(function($value, $label) {
                        $selected = "";
                        if(substr(ltrim($value), 0, 9) === "selected:") { 
                            $value = str_replace('selected:', '', $value);
                            $selected = "selected";
                        }
                        $label = str_replace('selected:', '', $label);
                        $value = (new Attribute('value', $value))->toRawHtml();
                        return "<option $value $selected> $label </option>";
                    }, $values, $labels));
                    $input = "
                        <select $this->name $restOfAttributes>
                            $options
                        </select>
                    ";
                } else {
                    $input = "<input $this->type $this->name $restOfAttributes />";
                }
                if(is_callable($this->html) == true) {
                    return $this->html("$this->label $input");
                }
                return "
                    $this->label $input $this->html
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

                if(is_callable($this->html) === true) {
                    return $this->html("
                        $this->label <input $this->type $this->name $listAttribute $restOfAttributes />

                        $dataset
                    ");
                }
                return "
                    $this->label <input $this->type $this->name $listAttribute $restOfAttributes />

                    $dataset

                    $this->html
                ";
            }
            
        }
    }