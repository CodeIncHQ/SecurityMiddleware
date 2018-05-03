<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     27/04/2018
// Time:     12:33
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware\Tests;
use CodeInc\SecurityMiddleware\StrictTransportSecurityMiddleware;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeServerRequest;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeRequestHandler;
use Psr\Http\Message\ResponseInterface;


/**
 * Class StrictTransportSecurityMiddlewareTest
 *
 * @uses StrictTransportSecurityMiddleware
 * @package CodeInc\SecurityMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class StrictTransportSecurityMiddlewareTest extends AbstractHttpHeaderMiddlewareTestCase
{
    public function testDisabled():void
    {
        $middleware = new StrictTransportSecurityMiddleware();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseNotHasHeader($response, 'Strict-Transport-Security');
    }

    public function testDisabling():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->setMaxAge(null);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseNotHasHeader($response, 'Strict-Transport-Security');
    }

    public function testOnHttpRequest():void
    {
        // the header should only be added to responses for HTTPS requests
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $response = $middleware->process(FakeServerRequest::getUnsecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseNotHasHeader($response, 'Strict-Transport-Security');
    }

    public function testMaxAge():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security', ['max-age=3600']);
    }

    public function testMaxAgeChange():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->setMaxAge(7200);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security', ['max-age=7200']);
    }

    public function testIncludeSubDomains():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->includeSubDomains();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security',
            ['max-age=3600; includeSubDomains']);
    }

    public function testIncludeSubDomainsWithMaxAgeChange():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->includeSubDomains();
        $middleware->setMaxAge(7200);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security',
            ['max-age=7200; includeSubDomains']);
    }

    public function testEnablePreload():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->enablePreload();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security',
            ['max-age=3600; preload']);
    }

    public function testEnablePreloadWithMaxAgeChange():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->enablePreload();
        $middleware->setMaxAge(7200);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security',
            ['max-age=7200; preload']);
    }

    public function testIncludeSubDomainsAndEnablePreload():void
    {
        // with max age + include subdomaines + preload
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->enablePreload();
        $middleware->includeSubDomains();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security',
            ['max-age=3600; includeSubDomains; preload']);
    }

    public function testIncludeSubDomainsAndEnablePreloadWithMaxAgeChange():void
    {
        $middleware = new StrictTransportSecurityMiddleware(3600);
        $middleware->enablePreload();
        $middleware->includeSubDomains();
        $middleware->setMaxAge(7200);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Strict-Transport-Security',
            ['max-age=7200; includeSubDomains; preload']);
    }
}