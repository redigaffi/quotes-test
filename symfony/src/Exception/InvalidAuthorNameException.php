<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidAuthorNameException extends HttpException
{
    const MESSAGE = 'Provider author name is not following the slug format.';

    /**
     * InvalidAuthorNameException constructor.
     */
    public function __construct()
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, self::MESSAGE);
    }
}