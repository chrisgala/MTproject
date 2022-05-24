<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Log;
class LogRequests
{
    public function handle($request, Closure $next)
    {
        $request->start = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response): void
    {
        $request->end = microtime(true);
        $this->log($request,$response);
    }
    protected function log($request,$response): void
    {
        $duration = $request->end - $request->start;
        $url = $request->fullUrl();
        $method = $request->getMethod();
        $ip = $request->getClientIp();
        $log = "$ip: $method@$url - {$duration}ms \n".
            "Request : {$request} \n";
        Log::channel('api')->info($log);
    }
}
