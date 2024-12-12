<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class CheckAai
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if(Auth::user()) {
            return $next($request);
        }

        // Check if the user is authenticated by SwitchAAI
        if($this->getServerVariable('Shib-Identity-Provider')) {
            // Check if the user can be found in the database
            $user = User::where('email', $this->getServerVariable('mail'))->first();

            if (!$user) {
                // If the user cannot be found, create it
                $user = $this->createAaiUser();
            }

            // Log the user
            Auth::login($user, true);

            return $next($request);
        }

        // Return to the app root with error message otherwise
        return redirect('/login')
            ->with('error', trans('auth.aai_failed'));
    }

    /**
     * Create a new aai user.
     *
     * @return User $user
     */
    private function createAaiUser(): User
    {
        $unilMemberOf = $this->getServerVariable('unilMemberOf');
        $isMemberOfCse = 0;
        if (strpos($unilMemberOf, 'cse-tous-g') == true || strpos($unilMemberOf, 'cse-pers-g') == true) {
            $isMemberOfCse = 1;
        }

        return User::create([
            'name' => $this->getServerVariable('givenName') . ' ' .
                $this->getServerVariable('surname'),
            'email' => $this->getServerVariable('mail'),
            'password' => '(aai_password)',
            'is_cse_member' => (bool)$isMemberOfCse,
        ]);
    }
    //'password' => $this->getServerVariable('password'),
    /**
     * Wrapper function for getting server variables.
     *
     * @param string $variableName
     *
     * @return string|null
     */
    private function getServerVariable(string $variableName)
    {
        $variable = null;

        if(Request::server($variableName)) {
            $variable = Request::server($variableName);
        } elseif(Request::server('REDIRECT_' . $variableName)) {
            $variable = Request::server('REDIRECT_' . $variableName);
        }

        return $variable;
    }
}
