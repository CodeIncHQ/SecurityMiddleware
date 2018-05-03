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
// Time:     12:29
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware\Tests;
use CodeInc\SecurityMiddleware\FrameOptionsMiddleware;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeServerRequest;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeRequestHandler;
use Psr\Http\Message\ResponseInterface;


/**
 * Class FrameOptionsMiddlewareTest
 *
 * @uses FrameOptionsMiddleware
 * @package CodeInc\SecurityMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class FrameOptionsMiddlewareTest extends AbstractHttpHeaderMiddlewareTestCase
{
    private const TEST_URL = 'https://www.example.org';

    public function testDeny():void
    {
        $middleware = new FrameOptionsMiddleware(FrameOptionsMiddleware::VALUE_DENY);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['DENY']);
    }

    public function testDenyStatic():void
    {
        $middleware = FrameOptionsMiddleware::denyFrames();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['DENY']);
    }

    public function testAllowFromSameOrigin():void
    {
        $middleware = new FrameOptionsMiddleware(FrameOptionsMiddleware::VALUE_SAMEORIGIN);
        $middleware->allowFromSameOrigin();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['SAMEORIGIN']);
    }

    public function testAllowFromSameOriginStatic():void
    {
        $middleware = FrameOptionsMiddleware::allowFromSameOrigin();
        $middleware->allowFromSameOrigin();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['SAMEORIGIN']);
    }

    public function testAllowFrom():void
    {
        $middleware = new FrameOptionsMiddleware(
            sprintf(FrameOptionsMiddleware::VALUE_ALLOW_FROM, self::TEST_URL)
        );
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['ALLOW-FROM '.self::TEST_URL]);
    }

    /**
     * @throws \CodeInc\SecurityMiddleware\MiddlewareException
     */
    public function testAllowFromStatic():void
    {
        $middleware = FrameOptionsMiddleware::allowFrom(self::TEST_URL);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['ALLOW-FROM '.self::TEST_URL]);
    }

    public function testValueChange():void
    {
        $middleware = new FrameOptionsMiddleware(FrameOptionsMiddleware::VALUE_DENY);
        $middleware->setValue(FrameOptionsMiddleware::VALUE_SAMEORIGIN);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['SAMEORIGIN']);
    }

    public function testValueChangeStatic():void
    {
        $middleware = FrameOptionsMiddleware::denyFrames();
        $middleware->setValue(FrameOptionsMiddleware::VALUE_SAMEORIGIN);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Frame-Options', ['SAMEORIGIN']);
    }
}