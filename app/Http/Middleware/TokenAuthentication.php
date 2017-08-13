<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthentication
{
    const TOKEN_INVALID = 'Invalid Token';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('token', '');
        if ($this->tokenIsValid($request, $token)) {
            return $next($request);
        }
        return new JsonResponse(['message' => self::TOKEN_INVALID], Response::HTTP_UNAUTHORIZED);
    }

    private function tokenIsValid(Request $request, string $token): bool
    {
        try {
            $user = User::where('token', $token)
                ->where('token_expires', '>', Carbon::now())
                ->select([User::ID, User::EMAIL, User::FIRST_NAME, User::LAST_NAME])
                ->firstOrFail();
            $user->{User::TOKEN_EXPIRES} = Carbon::now()->addHours(1);
            $user->save();
            $request->attributes->set(
                'user',
                $user->toArray()
            );
            return true;
        } catch (ModelNotFoundException $exception) {
            return false;
        }
    }
}
