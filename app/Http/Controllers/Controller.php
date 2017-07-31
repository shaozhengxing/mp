<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function rawRequest(Request $request) {
        switch ($request->method()) {
            case 'GET' :
                $data = $request->query();
                break;
            case 'POST' :
                $data = jsonDecode($this->rawPost(), true) ?: [];
                if (!$data) {
                    $data = $request->all();
                }
                break;
            default :
                $data = [];
        }

        return $data;
    }

    protected function rawPost() {
        return file_get_contents('php://input');
    }

    protected function setContent($response, $content) {
        /* @var Response $response */
        $response->setContent(json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    protected function setCache($response, $ttl = null) {
        if ($ttl) {
            $response->header('Cache-Control', "max-age=$ttl");
        } else {
            $response->header('Cache-Control', 'no-store');
        }
    }
}
