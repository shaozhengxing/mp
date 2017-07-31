<?php
//shaozhengxing@baixing.com

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class PageController extends Controller {
    public function index($filename) {
        //页面全部在 public/pages/ 目录下
        $file = ROOT . '/public/pages/' . $filename;
        if (file_exists($file)) {
            require $file;
        } elseif (file_exists($file . '.php')) {
            require $file . '.php';
        } else {
            return new Response('Not Found', 404);
        }
    }
}