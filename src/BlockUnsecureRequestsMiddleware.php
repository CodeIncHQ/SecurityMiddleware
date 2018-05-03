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
// Time:     17:54
// Project:  SecurityMiddleware
//
declare(strict_types=1);
namespace CodeInc\SecurityMiddleware;
use CodeInc\SecurityMiddleware\Tests\BlockUnsecureRequestsMiddlewareTest;
use CodeInc\Psr7Responses\ForbiddenResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class BlockUnsecureRequestsMiddleware
 *
 * @see BlockUnsecureRequestsMiddlewareTest
 * @package CodeInc\SecurityMiddleware
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @license MIT <https://github.com/CodeIncHQ/SecurityMiddleware/blob/master/LICENSE>
 * @link https://github.com/CodeIncHQ/SecurityMiddleware
 */
class BlockUnsecureRequestsMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseInterface
     */
    private $unsecureResponse;


    /**
     * BlockHttpRequestsMiddleware constructor.
     *
     * @param null|ResponseInterface $blockedResponse
     */
    public function __construct(?ResponseInterface $blockedResponse = null)
    {
        $this->unsecureResponse  = $blockedResponse ?? new ForbiddenResponse();
    }


    /**
     * @return ForbiddenResponse|null|ResponseInterface
     */
    public function getUnsecureResponse()
    {
        return $this->unsecureResponse;
    }


    /**
     * @param ResponseInterface $unsecureResponse
     */
    public function setUnsecureResponse($unsecureResponse):void
    {
        $this->unsecureResponse = $unsecureResponse;
    }


    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        // blocks HTTP requests
        if ($request->getUri()->getScheme() != 'https') {
            return $this->unsecureResponse;
        }

        return $handler->handle($request);
    }
}