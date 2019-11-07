<?php

namespace App\Controller;

use App\Component\JokeProcessorInterface;
use App\Controller\Exception\InvalidRequestParametersException;
use App\Form\JokeSenderType;
use App\Services\JokeAPI\JokeAPI;
use App\Services\Validator\JsonSchemaValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokeController extends AbstractController
{
    public const SEND_JOKE_ROUTE = 'send_joke_by_request';
    private const JOKE_PROCESSING_JSON_SCHEMA = '/JsonSchema/joke_processing_request_schema.json';

    /**
     * @var JokeAPI
     */
    private $jokeApi;
    /**
     * @var JokeProcessorInterface
     */
    private $jokeProcessor;
    /**
     * @var JsonSchemaValidatorInterface
     */
    private $jsonSchemaValidator;
    /**
     * @var string
     */
    private $jokeSenderTemplate;

    public function __construct(
        JokeAPI $jokeAPI,
        JokeProcessorInterface $jokeProcessor,
        JsonSchemaValidatorInterface $jsonSchemaValidator,
        string $jokeSenderTemplate
    ) {
        $this->jokeApi = $jokeAPI;
        $this->jokeProcessor = $jokeProcessor;
        $this->jsonSchemaValidator = $jsonSchemaValidator;
        $this->jokeSenderTemplate = $jokeSenderTemplate;
    }

    /**
     * @Route("/")
    */
    public function getCategories(): Response
    {
        $categories = $this->jokeApi->getJokeCategories();

        $form = $this->createForm(
            JokeSenderType::class,
            null,
            [
                'choices' => array_combine($categories, $categories),
                'action_url' =>  $this->generateUrl(self::SEND_JOKE_ROUTE)
            ]
        );

        return $this->render($this->jokeSenderTemplate, ['form' => $form->createView()]);
    }

    /**
     * @Route("/send_joke", name=JokeController::SEND_JOKE_ROUTE)
     * @param Request $request
     * @return Response
     */
    public function processing(Request $request): Response
    {
        parse_str($request->getContent(), $requestData);
        $this->checkProcessingRequestData($requestData);

        $this->jokeProcessor->processJoke($requestData['joke_sender']['category'], $requestData['joke_sender']['email']);

        return new Response('Joke was send to ' . $requestData['joke_sender']['email']);;
    }

    /**
     * @param array $requestData
     * @throws InvalidRequestParametersException
     */
    private function checkProcessingRequestData(array $requestData): void
    {
        if (!$this->jsonSchemaValidator->isValid(
            json_encode($requestData),
            realpath(__DIR__ . self::JOKE_PROCESSING_JSON_SCHEMA)
        )) {
            throw new InvalidRequestParametersException($this->jsonSchemaValidator->getErrors());
        }
    }
}