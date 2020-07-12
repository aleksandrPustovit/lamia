<?php
namespace App\Api\V1\Controllers;

/*
 * Abstract class for REST API
 * All controllers must inherit from this class
 */

/**
 * Class Api
 * Abstract class for REST API
 * All controllers must inherit from this class
 * @package App\Api\V1\Controllers
 */
abstract class Api
{
    public $apiName = ''; 

    protected $method = ''; //GET|POST|PUT|DELETE

    public $requestUri = [];
    public $requestParams = [];
    public $requestJSONParams = [];

    protected $action = ''; //Method name to run


    public function __construct() {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        //Array of GET params
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
        $this->requestParams = $_REQUEST;

        if($_SERVER['CONTENT_TYPE'] == 'application/json'){

            $jsonData = file_get_contents("php://input");
            $this->requestJSONParams = json_decode($jsonData);
        }


        //Determitane request METHOD
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new \Exception("Unexpected Header");
            }
        }
    }

    /**
     * First two elements have to be api and name of a table.
     * Defining an action to process.
     * @return mixed
     */
    public function run() {

        $this->action = $this->getAction();

        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        } else {
            throw new \RuntimeException('Invalid Method', 405);
        }
    }

    /**
     * Return a status coed for a request.
     * @param $code
     * @return mixed
     */
    private function requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    /**
     * @return string|null
     */
    protected function getAction()
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                return 'index';
                break;
            case 'POST':
                return 'create';
                break;
            case 'PUT':
                return 'update';
                break;
            case 'DELETE':
                return 'delete';
                break;
            default:
                return null;
        }
    }

    abstract protected function index();
    abstract protected function view();
    abstract protected function create();
    abstract protected function update();
    abstract protected function delete();
}