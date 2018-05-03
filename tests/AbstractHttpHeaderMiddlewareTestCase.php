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
// Time:     11:14
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware\Tests;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;


/**
 * Class AbstractHttpHeaderMiddlewareTestCase
 *
 * @package CodeInc\SecurityMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractHttpHeaderMiddlewareTestCase extends TestCase
{
    /**
     * @param ResponseInterface $response
     * @param string $headerName
     * @param array $headerValues
     */
    protected static function assertResponseHasHeaderValue(ResponseInterface $response, string $headerName,
        array $headerValues):void
    {
        self::assertResponseHasHeader($response, $headerName);
        self::assertArraySubset($headerValues, $response->getHeaders()[$headerName], false,
           sprintf('The header values (%s) are missing for the header %s',
               implode(', ', $headerValues), $headerName));
    }


    /**
     * @param ResponseInterface $response
     * @param string $headerName
     */
    protected static function assertResponseHasHeader(ResponseInterface $response, string $headerName):void
    {
        self::assertArrayHasKey($headerName, $response->getHeaders(),
            sprintf('The header %s is missing', $headerName));
        self::assertNotEmpty($response->getHeaders()[$headerName],
            sprintf('The header %s value is empty', $headerName));
    }


    /**
     * @param ResponseInterface $response
     * @param string $headerName
     */
    protected static function assertResponseNotHasHeader(ResponseInterface $response, string $headerName):void
    {
        self::assertArrayNotHasKey($headerName, $response->getHeaders(),
            sprintf('The header %s is missing in the response', $headerName));
    }


    /**
     * @param ResponseInterface $response
     * @param int $headersCount
     */
    protected static function assertResponseHeadersCount(ResponseInterface $response, int $headersCount):void
    {
        self::assertCount($headersCount, $response->getHeaders());
    }
}