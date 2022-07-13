<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
//->Création d'une entité (avec une class) et je m'assure que le use associé est présent

//->Lorsque l'on crée une entité penser à mettre la classe en repository correspondante
/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
     * @ORM\Column(type="boolean")
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }
    //->J'ajoute moi-même le lien entre article et catégorie - penser à inversedBy dans param ORM
    //pour articles = 1 seule cat
    //pour cat = plusieurs articles
    //->ManyToOne crée la clé étrangère qui permettra de relier une catégorie à un article
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     */
    private $category;

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

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
//->Dans cmder j'ordonne de migrer mes infos php vers la table SQL. Un comparatif sera fait entre
// les infos préexistantes et les nouvelles pour une MAJ
//->Fait en 2 temps :
// php bin/console make:migration
//->Bien vérifier mes infos dans le dossier migrations avant de migrer définitivement vers ma table
// puis php bin/console doctrine:migration:migrate

