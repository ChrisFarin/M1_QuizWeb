<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Quiz;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Entitled;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Response1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Response2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Response3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Response4;

    /**
     * @ORM\Column(type="integer")
     */
    private $GoodResponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->Quiz;
    }

    public function setQuiz(?Quiz $Quiz): self
    {
        $this->Quiz = $Quiz;

        return $this;
    }

    public function getEntitled(): ?string
    {
        return $this->Entitled;
    }

    public function setEntitled(string $Entitled): self
    {
        $this->Entitled = $Entitled;

        return $this;
    }

    public function getResponse1(): ?string
    {
        return $this->Response1;
    }

    public function setResponse1(string $Response1): self
    {
        $this->Response1 = $Response1;

        return $this;
    }

    public function getResponse2(): ?string
    {
        return $this->Response2;
    }

    public function setResponse2(string $Response2): self
    {
        $this->Response2 = $Response2;

        return $this;
    }

    public function getResponse3(): ?string
    {
        return $this->Response3;
    }

    public function setResponse3(?string $Response3): self
    {
        $this->Response3 = $Response3;

        return $this;
    }

    public function getResponse4(): ?string
    {
        return $this->Response4;
    }

    public function setResponse4(?string $Response4): self
    {
        $this->Response4 = $Response4;

        return $this;
    }

    public function getGoodResponse(): ?int
    {
        return $this->GoodResponse;
    }

    public function setGoodResponse(int $GoodResponse): self
    {
        $this->GoodResponse = $GoodResponse;

        return $this;
    }
}
