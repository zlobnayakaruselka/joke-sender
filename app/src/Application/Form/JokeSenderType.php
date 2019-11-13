<?php
namespace App\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JokeSenderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder->setAction($options['action_url'])
            ->add('email', EmailType::class, ['required' => true, 'label' => 'Your email address: '])
            ->add('category', ChoiceType::class, [
                'required' => true,
                'label' => 'Joke categories:  ',
                'choices' => array_combine($options['categories'], $options['categories'])
            ])
            ->add('submit', SubmitType::class, ['label' => 'Send joke!']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'categories' => [],
            'action_url' => ''
        ]);
    }
}