<?php
namespace tests\Functional\Form;

use App\Form\JokeSenderType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;

class JokeSenderTypeTest extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->formFactory = Forms::createFormFactory();
    }

    public function testCreateForm(): void
    {
        $form = $this->formFactory->create(
            JokeSenderType::class,
            null,
            ['action_url' => 'localhost', 'choices' => ['aa' => 123, 'nono' => 12345678]]);

        $submitData = ['email' => 'y@gmail.com', 'category' => 'aa'];

        $form->submit($submitData);
        $children = $form->createView()->children;

        foreach (array_keys($submitData) as $key) {
            $this->assertArrayHasKey($key,$children);
        }
    }
}