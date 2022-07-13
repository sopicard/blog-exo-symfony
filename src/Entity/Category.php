<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


//->Table générée via cmder
// php bin/console make:entity
//->Ensuite le terminal pose les questions chronologiques de création de class(entité) et de propriétés
// avec une différence : propriétés = en private
/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="category")
     */
    private $articles;

    //->Création relation category->articles donc OneToMany - penser à inser mappedBy dans param ORM
    //OneToMany ne crée aucune clé (aucune info sup sur la db)
    //mais permet à Doctrine de comprendre qu'il y a un lien avec la clé étrangère crée dans Article.
    //->Fonction __construct s'executera en tout début de code si et ssi classe = instanciée (new..):
    //->Ici je spécifie que $articles devient 1 tableau mais je pourrai aussi mettre d'autres valeurs par défaut
    //(que l'utilisateur pourra ensuite modifier)
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }
    //->Penser à générer(clic droit) getters & setters
    //=>Attn aux interprétations de php qui ajoute des : ou des ArrayCollection alors qu'il n'en faut pas
    /**
     * @param ArrayCollection $articles
     */
    public function setArticles($articles): void
    {
        $this->articles = $articles;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
