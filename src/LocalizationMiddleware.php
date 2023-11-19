<?php
/**
 * @author Sebastian Eiweleit <sebastian@eiweleit.de>
 * @website https://github.com/basteyy
 * @website https://eiweleit.de
 */

declare(strict_types=1);

namespace basteyy\LocalizationMiddleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LocalizationMiddleware implements MiddlewareInterface {

    /** @var string $attributeName Name of the attribute, which is set to the request */
    private string $attribute_name = 'language';

    /** @var string|null $default_language Fallback language, when users languages arnt supported */
    private ?string $default_language;

    /** @var array $available_languages Supported languages ['en', 'de', 'ua', ...]*/
    private array $available_languages;

    /** @var bool $patch_requested_url Patch the requested url to remove the language */
    private bool $patch_requested_url;

    /** @var bool $patch_only_exactly_match Patch requested url only if the language is exactly matched */
    private bool $patch_only_exactly_match;

    /** @var bool $browser_overwrite_url Overwrite the url with the browser language */
    private bool $browser_overwrite_url;

    private bool $has_url_language = false;

    public function __construct(?string $default_language = null,
                                ?array $available_languages = null,
                                ?bool $patch_requested_url = true,
                                ?bool $browser_overwrite_url = false,
                                ?bool $patch_only_exactly_match = false)
    {
        $this->default_language = $default_language ?? null;
        $this->available_languages = $available_languages ?? [];
        $this->patch_requested_url = $patch_requested_url ?? true;
        $this->browser_overwrite_url = $browser_overwrite_url ?? false;
        $this->patch_only_exactly_match = $patch_only_exactly_match ?? false;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next): ResponseInterface {

        if (!$next) {
            return $response;
        }

        return $next($this->handleRequest($request), $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($this->handleRequest($request));
    }

    /**
     * Get the preferred language from the browser (based on Accept-Language header)
     * @param RequestInterface $request
     * @return array
     */
    private function getPreferredLanguage(RequestInterface $request): array
    {
        $acceptLanguageHeader = $request->getHeader('Accept-Language');

        return array_map(function ($lang) {
            return substr($lang, 0, 2);
        }, explode(',', $acceptLanguageHeader[0]));
    }

    /**
     * @param RequestInterface $request
     * @return string|null
     */
    private function getUrlLanguage(RequestInterface $request): string|null {
        $url = (string) $request->getUri()->getPath();

        // Detect Language in Url of the request and overwrite browser
        if ('/' === substr($url, 3, 1)  ) {
            $lang = substr($url, 1, 2);

            $this->has_url_language = true;

            if (in_array($lang, $this->available_languages)) {
                return substr($url, 1, 2);
            }
        }

        return null;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    private function determineLanguage(ServerRequestInterface $request): string
    {
        $browser = $this->getPreferredLanguage($request);
        $url = $this->getUrlLanguage($request);

        if (!$url) {
            foreach ($browser as $lang) {
                if (in_array($lang, $this->available_languages)) {
                    return $lang;
                }
            }

            return $this->default_language;
        }

        if ($this->browser_overwrite_url) {
            foreach ($browser as $lang) {
                if (in_array($lang, $this->available_languages)) {
                    return $lang;
                }
            }
        }

        if (in_array($url, $this->available_languages)) {
            return $url;
        }

        return $this->default_language;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function handleRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $lang = $this->determineLanguage($request);
        $request = $request->withAttribute($this->attribute_name, $lang);

        if ($this->patch_requested_url && $this->has_url_language) {

            // Exact patch?
            if ($this->patch_only_exactly_match) {
                if(str_starts_with($request->getUri()->getPath(), '/' . $lang . '/')) {
                    $url = substr($request->getUri()->getPath(), 3);
                }
            } else {
                $url = substr($request->getUri()->getPath(), 3);
            }

            // new url for thr request
            $uri = $request->getUri()->withPath($url);
            $request = $request->withUri($uri);
        }

        return $request;
    }
}
