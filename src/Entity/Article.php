<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

//je crée une entité (avec une class) et je m'assure que le use associé est présent
/**
 * @ORM\Entity()
 */
class Article
{
    // chaque ligne @ORM décrit une ligne miroir que l'on trouvera dans la table
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
}
// dans cmder j'ordonne de migrer mes (nouvelles) infos php vers la table SQL