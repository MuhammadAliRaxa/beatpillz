<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertUrlParametersToLowerCase
{
    public function handle(Request $request, Closure $next): Response
    {
        $uri = $_SERVER['REQUEST_URI'];

        $path = strtok($uri, '?');

        $query = '';
        if (strpos($uri, '?') !== false) {
            $after = substr($uri, strpos($uri, '?') + 1);
            if ($after !== false) {
                $query = $after;
            }
        }

        if (strtolower($path) !== $path) {
            $redirectUri = strtolower($path);
            if (strlen($query) > 0) {
                $redirectUri .= '?' . $query;
            }

            $uri = trim($redirectUri, '/');
            $parsedUrl = parse_url(url($uri));
            $redirectUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/' . $uri;

            return redirect($redirectUrl);
        }

        return $next($request);
    }
}
