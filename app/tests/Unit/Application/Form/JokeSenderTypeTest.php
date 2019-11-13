<?php
namespace tests\Unit\Application\Form;

use App\Application\Form\JokeSenderType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;

/**
 * @coversDefaultClass App\Application\Form\JokeSenderType
 */
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

    /**
     * @covers ::<public>
     */
    public function testCreateForm(): void
    {
        $form = $this->formFactory->create(
            JokeSenderType::class,
            null,
            ['action_url' => 'localhost', 'categories' => ['aa' => 123, 'nono' => 12345678]]);

        $submitData = ['email' => 'y@gmail.com', 'category' => 'aa'];

        $form->submit($submitData);
        $children = $form->createView()->children;

        foreach (array_keys($submitData) as $key) {
            $this->assertArrayHasKey($key,$children);
        }
    }
}