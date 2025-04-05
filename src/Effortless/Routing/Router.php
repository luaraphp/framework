<?php

    namespace Effortless\Routing;

    class Router {

        protected $currentPath;

        protected $currentRequestMethod;

        public function __construct() {
            $this->currentPath = parse_url($_SERVER['REQUEST_URI'])['path'];
            $this->currentRequestMethod = $_SERVER['REQUEST_METHOD'];
        }

        protected function resolve($path, $method) {

            $endpoint = (new RouteFileResolver())->resolve($path)->getEndpoint();

            if(isset($endpoint)) {
                $route = new Route($endpoint);
                if(! $route->acceptsRequestMethod($method)) {
                    echo "Request Method Is Not Match";
                } else {
                    $route->fireCallback();
                }
                unset($route);
            } else {
                echo '404 Not Found';
            }
        }

        public function listen() {
            $this->resolve($this->currentPath, $this->currentRequestMethod);
        }
    }