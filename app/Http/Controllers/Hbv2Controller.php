<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Hbv2Controller extends Controller
{
    public function processRequest(Request $request)
    {
        // Perform validation and authentication checks
        // ...

        // Determine the microservice URL from the service registry
        $microservice = $this->determineMicroservice($request);
        $microserviceUrl = ServiceRegistry::getServiceUrl($microservice);

        $path = explode("/", $request->path());
        $routeParams = $path[1] . "/" . $path[2];

        // Forward the request to the microservice
        $response = Http::withHeaders($request->header())
            ->{$request->method()}("$microserviceUrl/api/{$routeParams}", $this->getRequestData($request));

        // Process the response from the microservice, if needed
        // ...

        // Return the response to the client
        return $response->body();
    }

    private function determineMicroservice(Request $request)
    {
        // Implement your logic here to determine the microservice based on the request
        // You can use the request endpoint, parameters, or any other criteria

        // For example, let's assume the endpoint starts with '/user' goes to 'hbv2-user',
        // '/permission' goes to 'hbv2-permission', and anything else goes to 'hbv2-property'

        $endpoint = $request->path();
//        $endpoint = explode("/", $endpoint)[0];

        if (strpos($endpoint, 'user') !== 0) {
            return 'hbv2-user';
        } elseif (strpos($endpoint, 'permission') !== 0) {
            return 'hbv2-permission';
        } elseif (strpos($endpoint, 'property') !== 0) {
            return 'hbv2-property';
        }

        return '';
    }

    private function getRequestData(Request $request)
    {
        // Get the request data based on the request method

        switch ($request->method()) {
            case 'POST':
            case 'PATCH':
            case 'PUT':
                return $request->all();
            case 'GET':
            case 'DELETE':
                return $request->query();
            default:
                return [];
        }
    }
}

class ServiceRegistry
{
    private $services;

    public function __construct()
    {
        // Initialize the services array with registered services and their URLs
        $this->services = [
            'hbv2-user' => [
                'url' => 'http://heartbeat-user', // Replace with actual URL of hbv2-user microservice
            ],
            'hbv2-permission' => [
                'url' => 'http://hbv2-permission-service:8000', // Replace with actual URL of hbv2-permission microservice
            ],
            'hbv2-property' => [
                'url' => 'http://hbv2-property-service:8000', // Replace with actual URL of hbv2-property microservice
            ],
            // Add more microservices and their URLs as needed
        ];
    }

    public static function getServiceUrl($serviceName)
    {
        $registry = new ServiceRegistry();

        if (isset($registry->services[$serviceName])) {
            return $registry->services[$serviceName]['url'];
        }

        throw new \Exception("Service '$serviceName' not found in the service registry.");
    }
}
