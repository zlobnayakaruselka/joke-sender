<?php

namespace App\Application\Controller;

use App\Application\Controller\Exception\InvalidRequestParametersException;
use App\Application\Form\JokeSenderType;
use App\Application\Form\Model\JokeSenderModel;
use App\Feature\Joke\UseCase\GetCategories\Interactor as CategoriesGetter;
use App\Feature\Joke\UseCase\SendJoke\Interactor as JokeSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokeController extends AbstractController
{
    public const SEND_JOKE_ROUTE = 'send_joke_by_request';

    /**
     * @var CategoriesGetter
     */
    private $categoriesGetter;
    /**
     * @var JokeSender
     */
    private $jokeSender;
    /**
     * @var string
     */
    private $jokeSenderTemplate;

    public function __construct(
        CategoriesGetter $categoriesGetter,
        JokeSender $jokeSender,
        string $jokeSenderTemplate
    ) {
        $this->categoriesGetter = $categoriesGetter;
        $this->jokeSender = $jokeSender;
        $this->jokeSenderTemplate = $jokeSenderTemplate;
    }

    /**
     * @Route("/")
    */
    public function getCategories(): Response
    {
        throw new \Exception('lol');
        $form = $this->createForm(
            JokeSenderType::class,
            new JokeSenderModel(),
            [
                'categories' => $this->categoriesGetter->getCategories(),
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
    public function sendJoke(Request $request): Response
    {
        $form = $this->createForm(
            JokeSenderType::class,
            new JokeSenderModel(),
            ['action' => '', 'categories' => $this->categoriesGetter->getCategories()]
        );
        $form->submit($request->get($form->getName()));

        if (!$form->isValid()) {
            throw new InvalidRequestParametersException($form->getErrors()->current()->getMessage());
        }

        /** @var JokeSenderModel $model */
        $model = $form->getData();
        $this->jokeSender->sendJoke($model);

        return new Response('Joke was send to ' . $model->getEmail());
    }
}