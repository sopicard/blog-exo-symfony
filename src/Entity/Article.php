<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

//je crée une entité (avec une class) et je m'assure que le use associé est présent
/**
 * @ORM\Entity()
 */
class Article
{
    // chaque ligne @ORM décrit comment chaque colonne seront crées sur la table
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="string")
     */
    public $image;

    /**
     * @ORM\Column(type="boolean")
     */
    public $isPublished;

    /**
     * @ORM\Column(type="string")
     */
    public $author;
}
// dans cmder j'ordonne de migrer mes infos php vers la table SQL. un comparatif sera fait entre
// les infos préexistantes et les nouvelles pour une MAJ
//fait en 2 temps :
//php bin/console make:migration
// puis php bin/console doctrine:migration:migrate
