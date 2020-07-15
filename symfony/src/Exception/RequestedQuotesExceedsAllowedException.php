<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestedQuotesExceedsAllowedException extends HttpException
{
    const MESSAGE = 'Amount of quotes requested exceeds the limit.';

    /**
     * RequestedQuotesExceedsAllowedException constructor.
     */
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, self::MESSAGE);
    }

}