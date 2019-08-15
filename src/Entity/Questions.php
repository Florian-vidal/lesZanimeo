<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionsRepository")
 */
class Questions
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $libelle_question;

    /**
     * @ORM\Column(type="text")
     */
    private $libelle_reponse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleQuestion(): ?string
    {
        return $this->libelle_question;
    }

    public function setLibelleQuestion(string $libelle_question): self
    {
        $this->libelle_question = $libelle_question;

        return $this;
    }

    public function getLibelleReponse(): ?string
    {
        return $this->libelle_reponse;
    }

    public function setLibelleReponse(string $libelle_reponse): self
    {
        $this->libelle_reponse = $libelle_reponse;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }
}
