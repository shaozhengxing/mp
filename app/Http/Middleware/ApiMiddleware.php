<?php
//zhengxingok@gmail.com

namespace App\Http\Middleware;

use Closure;

class ApiMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        try {
            $this->initEnv($request);
            $this->verifyParams($request);
            $this->checkLimit($request);

            return $next($request);
        } catch (\Exception $ex) {
            return \Response::make($ex->getMessage(), 500);
        }
    }

    private function checkLimit($request) {
        //do nothing now
    }

    private function verifyParams($request) {
        //do nothing now
    }

    private function initEnv($request) {
        //do nothing now
    }
}