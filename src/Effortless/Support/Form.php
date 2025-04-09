<?php

    namespace Effortless\Support;

    use Effortless\Support\Html\Form\PropertyFormater;
    use Effortless\View\View;

    class Form {

        protected $method;

        protected $action;

        protected $fields;

        public function __construct($method = "GET", $action = "", $fields = [
            "username" => [
                'type' => "text",
                'label' => "Username",
                'required' => true,
                'nextLine' => true
            ],
            "password" => [
                'type' => "password",
                'label' => "Password",
                'required' => true,
                'nextLine' => false
            ]
        ]) {
            $this->method = (new PropertyFormater("method", $method))->toHtml(strtoupper($method));
            $this->action = (new PropertyFormater("action"))->toHtml($action);
            $this->fields = $fields;
        }

        public function render() {
            return (new View(null, [
                'method' => $this->method,
                'action' => $this->action,
                'fields' => $this->fields
            ], false, true))
                ->setPath(__DIR__ .  "/views/form.view.php")
                ->compile()
                ->render();
        }

    }  