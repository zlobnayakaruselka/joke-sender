<?php
namespace App\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class JokeSenderModel
{
    /**
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     * )
     */
    private $email;
    /**
     * @Assert\Length(
     *      min = 1,
     *      minMessage = "Category must not be empty"
     * )
     */
    private $category;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }
}