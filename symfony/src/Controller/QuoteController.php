<?


namespace App\Controller;



use App\Exception\Exception;
use App\Service\Quote\QuoteService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuoteController
{
    private $quoteService;

    /**
     * QuoteController constructor.
     * @param QuoteService $quoteService
     */
    public function __construct(QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function getQuotes(string $name, int $limit): Response
    {
        $quoteResponse = $this->quoteService->getQuotes($name, $limit);
        return JsonResponse::fromJsonString($quoteResponse);
    }
}