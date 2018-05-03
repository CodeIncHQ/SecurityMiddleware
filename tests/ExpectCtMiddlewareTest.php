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
// Time:     16:58
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware\Tests;
use CodeInc\SecurityMiddleware\ExpectCtMiddleware;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeRequestHandler;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeServerRequest;
use Psr\Http\Message\ResponseInterface;


/**
 * Class ExpectCtMiddlewareTest
 *
 * @uses ExpectCtMiddleware
 * @package CodeInc\SecurityMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class ExpectCtMiddlewareTest extends AbstractHttpHeaderMiddlewareTestCase
{
    private const TEST_MAX_AGE = 3600;
    private const TEST_REPORT_URI = 'https://example.org/enfcore/ct';

    public function testDisabled():void
    {
        $middleware = new ExpectCtMiddleware();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseNotHasHeader($response, 'Expect-CT');
    }

    public function testUnsecureRequest():void
    {
        $middleware = new ExpectCtMiddleware();
        $middleware->setEnforce(true);
        $response = $middleware->process(FakeServerRequest::getUnsecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseNotHasHeader($response, 'Expect-CT');
    }

    public function testEnforceMethod():void
    {
        $middleware = new ExpectCtMiddleware();
        $middleware->setEnforce(true);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT', ['enforce']);
    }

    public function testEnforceConstructor():void
    {
        $middleware = new ExpectCtMiddleware(null, true);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT', ['enforce']);
    }

    public function testMaxAgeMethod():void
    {
        $middleware = new ExpectCtMiddleware();
        $middleware->setMaxAge(self::TEST_MAX_AGE);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT', ['max-age='.self::TEST_MAX_AGE]);
    }

    public function testMaxAgeConstructor():void
    {
        $middleware = new ExpectCtMiddleware(self::TEST_MAX_AGE);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT', ['max-age='.self::TEST_MAX_AGE]);
    }

    public function testReportUriMethod():void
    {
        $middleware = new ExpectCtMiddleware();
        $middleware->setReportUri(self::TEST_REPORT_URI);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT',
            ['report-uri="'.self::TEST_REPORT_URI.'"']);
    }

    public function testReportUriConstructor():void
    {
        $middleware = new ExpectCtMiddleware(null, null, self::TEST_REPORT_URI);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT',
            ['report-uri="'.self::TEST_REPORT_URI.'"']);
    }

    public function testAllMethods():void
    {
        $middleware = new ExpectCtMiddleware();
        $middleware->setReportUri(self::TEST_REPORT_URI);
        $middleware->setMaxAge(self::TEST_MAX_AGE);
        $middleware->setEnforce(true);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT',
            ['report-uri="'.self::TEST_REPORT_URI.'", enforce, max-age='.self::TEST_MAX_AGE]);
    }

    public function testAllConstructor():void
    {
        $middleware = new ExpectCtMiddleware(self::TEST_MAX_AGE, true, self::TEST_REPORT_URI);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT',
            ['report-uri="'.self::TEST_REPORT_URI.'", enforce, max-age='.self::TEST_MAX_AGE]
        );
    }

    public function testValueChangeMethods():void
    {
        $middleware = new ExpectCtMiddleware(self::TEST_MAX_AGE, true, self::TEST_REPORT_URI);
        $middleware->setReportUri(null);
        $middleware->setMaxAge(null);
        $middleware->setEnforce(false);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseNotHasHeader($response, 'Expect-CT');
    }

    public function testValueChangeConstuctor():void
    {
        $middleware = new ExpectCtMiddleware(self::TEST_MAX_AGE, true, self::TEST_REPORT_URI);
        $middleware->setMaxAge(null);
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'Expect-CT',
            ['report-uri="'.self::TEST_REPORT_URI.'", enforce']);
    }
}