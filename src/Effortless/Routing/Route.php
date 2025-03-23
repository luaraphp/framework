<?php

    namespace Effortless\Routing;

    class Route {

        protected $method;
        protected $callback;

        public function __construct($endpoint) {
            $this->method = $this->resolveMethod($endpoint);
            $this->callback = $endpoint;
        }

        protected function resolveMethod($endpoint) {
            if(isset($endpoint->method)) {
                return strtoupper($endpoint->method);
            } else {
                return "GET";
            }
        }

        public function acceptsRequestMethod($requestMethod) {
            return $this->method === $requestMethod;
        }

        public function fireCallback() {
            $instance = $this->callback;
            $instance();
        }

    }