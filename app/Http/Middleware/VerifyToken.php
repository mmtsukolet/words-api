<?php
namespace App\Http\Middleware;

use App\Models\ErankApp\Members;
use Carbon\Carbon;
use Closure;
use Firebase\JWT\JWT;

class VerifyToken {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('token');
            if (is_null($token)) {
                return response()->json(['success' => false ,'message' => 'Unauthorized access', 'code' => 403 ], 403);
            }

            $check = JWT::decode($token, env('APP_SECRET_KEY'), ['HS256']);

            if ($check->expires_on) {
                $is_valid = Carbon::now(env('APP_TIMEZONE'))->lessThanOrEqualTo(Carbon::parse($check->expires_on, env('APP_TIMEZONE')));

                if (!$is_valid) {
                    return response()->json(['success' => false ,'message' => 'Unauthorized access', 'code' => 403 ], 403);
                }

                $member = Members::where('Memberid', $check->member_id)
                                 ->first();
                if (!$member) {
                    return response()->json(['success' => false ,'message' => 'Unauthorized access', 'code' => 403 ], 403);
                }

                $response = $next($request);
            } else {
                return response()->json(['success' => false ,'message' => 'Unauthorized access', 'code' => 403 ], 403);
            }

        } catch (\Exception $ex) {
            return response()->json(['success' => false ,'message' => 'Unauthorized access', 'code' => 403 ], 403);
        }

        return $response;
    }
}
