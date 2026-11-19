<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response; use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
class JwtAdmin { public function handle(Request $request, Closure $next): Response { try {  $user = JWTAuth::parseToken()->authenticate(); $request->setUserResolver(fn () => $user); $request->attributes->set("admin_authenticated", true); } catch (\Throwable) { return response()->json(['message'=>'Token tidak valid atau kedaluwarsa.'],401); } return $next($request); } }
