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
// Time:     12:26
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware\Tests;
use CodeInc\SecurityMiddleware\ContentTypeOptionsMiddleware;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeServerRequest;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeRequestHandler;
use Psr\Http\Message\ResponseInterface;


/**
 * Class ContentTypeOptionsMiddlewareTest
 *
 * @uses ContentTypeOptionsMiddleware
 * @package CodeInc\SecurityMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class ContentTypeOptionsMiddlewareTest extends AbstractHttpHeaderMiddlewareTestCase
{
    public function testMiddleware():void
    {
        $middleware = new ContentTypeOptionsMiddleware();
        $response = $middleware->process(FakeServerRequest::getSecureServerRequest(), new FakeRequestHandler());
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertResponseHasHeaderValue($response, 'X-Content-Type-Options', ['nosniff']);
    }
}