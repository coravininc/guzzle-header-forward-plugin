<?php

namespace SilviuButnariu\GuzzleHeaderForwardPlugin;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Middleware
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $headers;

    /**
     * Middleware constructor.
     *
     * @param RequestStack $requestStack
     * @param array $headers
     */
    public function __construct(RequestStack $requestStack, array $headers = [])
    {
        $this->requestStack = $requestStack;
        $this->headers = $headers;
    }

    /**
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        return function(RequestInterface $request, array $options) use (&$handler) {
            if (empty($this->headers)) {
                return $handler;
            }

            $currentRequest = $this->requestStack->getCurrentRequest();

            foreach ($this->headers as $header) {
                if ($currentRequest->headers->has($header['name'])) {// if header is specifically given, forward it
                    $request = $request->withHeader($header['name'], $currentRequest->headers->get($header['name']));
                } elseif (array_key_exists('default', $header)) {// forward the defined default value
                    $request = $request->withHeader($header['name'], $header['default']);
                }
            }

            return $handler($request, $options);
        };
    }
}
