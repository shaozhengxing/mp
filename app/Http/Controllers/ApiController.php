<?php
//zhengxingok@gmail.com

namespace App\Http\Controllers;

use App\Lib\RPC\RPCCmder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller {
    public function index(Request $request) {
        $response = \Response::make();
        $response->header('Content-Type', 'application/json');

        $this->handle($request, $response);

        return $response;
    }

    private function handle(Request $request, Response $response) {
        $requestData = $this->rawRequest($request);
        $api = $this->getProcedureName($request->path());

        $result = RPCCmder::call($api, $requestData);
        $this->setContent($response, current($result));

        return $response;
    }

    private function getProcedureName($path) {
        return explode('api/', $path)[1];
    }
}