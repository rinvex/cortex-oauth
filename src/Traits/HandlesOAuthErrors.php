<?php

declare(strict_types=1);

namespace Cortex\Oauth\Traits;

use Nyholm\Psr7\Response as Psr7Response;
use Rinvex\Oauth\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueException;

trait HandlesOAuthErrors
{
    use ConvertsPsrResponses;

    /**
     * Perform the given callback with exception handling.
     *
     * @param \Closure $callback
     *
     * @throws \Rinvex\Oauth\Exceptions\OAuthServerException
     *
     * @return mixed
     */
    protected function withErrorHandling($callback)
    {
        try {
            return $callback();
        } catch (LeagueException $e) {
            throw new OAuthServerException(
                $e,
                $this->convertResponse($e->generateHttpResponse(new Psr7Response()))
            );
        }
    }
}
