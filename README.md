# Security PSR-15 middlewares 

This library provides a collection of [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware to manage HTTP security. 


## The collection includes

* [`ContentSecurityPolicyMiddleware`](src/ContentSecurityPolicyMiddleware.php) Adds a [`Content-Security-Policy`](https://developer.mozilla.org/docs/Web/HTTP/Headers/Content-Security-Policy) HTTP headers to the response
* [`ContentTypeOptionsMiddleware`](src/ContentTypeOptionsMiddleware.php) Adds a [`X-Content-Type-Options`](https://developer.mozilla.org/docs/Web/HTTP/Headers/X-Content-Type-Options) HTTP headers to the response
* [`ExpectCtMiddleware`](src/ExpectCtMiddleware.php) Adds a [`Expect-CT`](https://developer.mozilla.org/docs/Web/HTTP/Headers/Expect-CT) HTTP headers to the response
* [`FrameOptionsMiddleware`](src/FrameOptionsMiddleware.php) Adds a [`X-Frame-Options`](https://developer.mozilla.org/docs/Web/HTTP/Headers/X-Frame-Options) HTTP headers to the response
* [`ReferrerPolicyMiddleware`](src/ReferrerPolicyMiddleware.php) Adds a [`Referrer-Policy`](https://developer.mozilla.org/docs/Web/HTTP/Headers/Referrer-Policy) HTTP headers to the response
* [`StrictTransportSecurityMiddleware`](src/StrictTransportSecurityMiddleware.php) Adds a [`Strict-Transport-Security`](https://developer.mozilla.org/docs/Web/HTTP/Headers/Strict-Transport-Security) HTTP headers to the response
* [`XssProtectionMiddleware`](src/XssProtectionMiddleware.php) Adds a [`X-Xss-Protection`](https://developer.mozilla.org/docs/Web/HTTP/Headers/X-XSS-Protection) HTTP headers to the response
* [`BlockUnsecureRequestsMiddleware`](src/BlockUnsecureRequestsMiddleware.php) Blocks unsecure (other than `HTTPS`) requests responses

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/security-middleware) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/security-middleware
```

:speech_balloon: This library is extracted from the now deprecated [codeinc/psr15-middlewares](https://packagist.org/packages/codeinc/psr15-middlewares) package.

## License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file).