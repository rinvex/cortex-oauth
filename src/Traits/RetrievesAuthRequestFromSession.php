<?php

declare(strict_types=1);

namespace Cortex\Oauth\Traits;

use Exception;
use Illuminate\Http\Request;
use Rinvex\Oauth\Bridge\User;
use Rinvex\Oauth\Exceptions\InvalidAuthTokenException;

trait RetrievesAuthRequestFromSession
{
    /**
     * Make sure the auth token matches the one in the session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Rinvex\Oauth\Exceptions\InvalidAuthTokenException
     *
     * @return void
     */
    protected function assertValidAuthToken(Request $request)
    {
        if ($request->has('auth_token') && $request->session()->get('authToken') !== $request->input('auth_token')) {
            $request->session()->forget(['authToken', 'authRequest']);

            throw InvalidAuthTokenException::different();
        }
    }

    /**
     * Get the authorization request from the session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     *
     * @return \League\OAuth2\Server\RequestTypes\AuthorizationRequest
     */
    protected function getAuthRequestFromSession(Request $request)
    {
        return tap($request->session()->get('authRequest'), function ($authRequest) use ($request) {
            if (! $authRequest) {
                throw new Exception('Authorization request was not present in the session.');
            }

            $authRequest->setUser(new User($request->user()->getMorphClass().':'.$request->user()->getAuthIdentifier()));

            $authRequest->setAuthorizationApproved(true);
        });
    }
}
