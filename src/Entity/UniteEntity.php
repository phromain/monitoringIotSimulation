<?php

namespace App\Entity;

use App\Repository\UniteEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteEntityRepository::class)]
class UniteEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_unite;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $nom_unite;

    

    /**
     * Get the value of id_unite
     */
    public function getIdUnite()
    {
        return $this->id_unite;
    }

    /**
     * Set the value of id_unite
     */
    public function setIdUnite($id_unite): self
    {
        $this->id_unite = $id_unite;

        return $this;
    }

    /**
     * Get the value of nom_unite
     */
    public function getNomUnite()
    {
        return $this->nom_unite;
    }

    /**
     * Set the value of nom_unite
     */
    public function setNomUnite($nom_unite): self
    {
        $this->nom_unite = $nom_unite;

        return $this;
    }
}
