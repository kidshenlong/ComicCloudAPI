<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 08/06/14
 * Time: 13:50
 */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$request = $_GET['request'];

$request_array = explode('/', rtrim($request, '/'));

$method = strtoupper($_SERVER['REQUEST_METHOD']);
switch (method) {
    case 'GET':
        $request->parameters = $_GET;
        break;
    case 'POST':
        $request->parameters = $_POST;
        break;
    case 'PUT':
        parse_str(file_get_contents('php://input'), $request->parameters);
        break;
}


