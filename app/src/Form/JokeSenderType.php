<?php
namespace App\Form;

use App\Controller\JokeController;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use App\Services\JokeAPI\JokeAPI;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JokeSenderType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var JokeAPI
     */
    private $jokeApi;

    public function __construct(UrlGeneratorInterface $urlGenerator, JokeAPI $jokeAPI)
    {
        $this->urlGenerator = $urlGenerator;
        $this->jokeApi = $jokeAPI;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws ApiErrorException
     * @throws InvalidResponseException
     */
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $categories = $this->jokeApi->getJokeCategories();

        $builder->setAction($this->urlGenerator->generate(JokeController::SEND_JOKE_ROUTE))
            ->add('email', EmailType::class, ['required' => true, 'label' => 'Your email address: '])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'label' => 'Joke categories:  ',
                'choices' => array_combine($categories, $categories),
            ])
            ->add('submit', SubmitType::class, ['label' => 'Send joke!']);
    }
}