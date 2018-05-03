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
// Time:     17:57
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware\Tests;
use CodeInc\SecurityMiddleware\Assets\UnsecureResponse;
use CodeInc\SecurityMiddleware\BlockUnsecureRequestsMiddleware;
use CodeInc\SecurityMiddleware\Tests\Assets\BlankResponse;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeRequestHandler;
use CodeInc\SecurityMiddleware\Tests\Assets\FakeServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;


/**
 * Class BlockUnsecureRequestsMiddlewareTest
 *
 * @uses BlockUnsecureRequestsMiddleware
 * @package CodeInc\SecurityMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class BlockUnsecureRequestsMiddlewareTest extends TestCase
{
    public function testSecureRequest():void
    {
        $middleware = new BlockUnsecureRequestsMiddleware();
        $response = $middleware->process(
            FakeServerRequest::getSecureServerRequest(),
            new FakeRequestHandler(new BlankResponse())
        );
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(BlankResponse::class, $response);
    }

    public function testUnsecureRequest():void
    {
        $middleware = new BlockUnsecureRequestsMiddleware();
        $response = $middleware->process(
            FakeServerRequest::getUnsecureServerRequest(),
            new FakeRequestHandler(new BlankResponse())
        );
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(UnsecureResponse::class, $response);
    }

    public function testSecureRequestCheck():void
    {
        $this->assertTrue(BlockUnsecureRequestsMiddleware::isRequestSecure(FakeServerRequest::getSecureServerRequest()));
        $this->assertFalse(BlockUnsecureRequestsMiddleware::isRequestSecure(FakeServerRequest::getUnsecureServerRequest()));
    }
}