<?php
namespace App\Components\JokeApi\Model\Creator;

use App\Components\JokeApi\Exception\ApiErrorException;
use App\Components\JokeApi\Exception\InvalidResponseException;
use App\Components\JokeApi\Model\Creator\Builder\ResponseModelBuilderInterface;
use App\Components\JokeApi\Model\ResponseModelInterface;
use App\Components\Services\Decoder\DecoderInterface;
use App\Components\Services\Validator\Object\ObjectValidatorInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class ResponseModelCreator implements ResponseModelCreatorInterface
{
    /**
     * @var DecoderInterface
     */
    private $decoder;
    /**
     * @var ObjectValidatorInterface
     */
    private $validator;
    /**
     * @var ResponseModelBuilderInterface
     */
    private $responseModelBuilder;

    public function __construct(
        DecoderInterface $decoder,
        ObjectValidatorInterface $validator,
        ResponseModelBuilderInterface $responseModelBuilder
    ){
        $this->decoder = $decoder;
        $this->responseModelBuilder = $responseModelBuilder;
        $this->validator = $validator;
    }

    /**
     * @param ResponseInterface $response
     * @param string $modelClass
     * @return ResponseModelInterface
     * @throws ApiErrorException
     * @throws InvalidResponseException
     */
    public function createValidModel(ResponseInterface $response, string $modelClass): ResponseModelInterface
    {
        $this->checkStatusCode($response);

        $responseModel = $this->responseModelBuilder->build(
            new $modelClass(),
            $this->decoder->decodeToArray($response->getBody())
        );

        $this->checkModelValidity($responseModel);

        return $responseModel;
    }

    /**
     * @param ResponseInterface $response
     * @throws ApiErrorException
     */
    private function checkStatusCode(ResponseInterface $response): void
    {
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new ApiErrorException($response->getStatusCode(), $response->getBody());
        }
    }

    /**
     * @param ResponseModelInterface $responseModel
     * @throws InvalidResponseException
     */
    private function checkModelValidity(ResponseModelInterface $responseModel): void
    {
        if (!$this->validator->isValid($responseModel)) {
            throw new InvalidResponseException($this->validator->getErrors());
        }
    }
}