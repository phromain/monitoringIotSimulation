<?php

namespace App\Entity;

use App\Repository\ModuleEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleEntityRepository::class)]
class ModuleEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_module;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $nom_module;

    #[ORM\ManyToOne(targetEntity: UniteEntity::class, fetch: "EAGER")]
    #[ORM\JoinColumn(name: "id_unite", referencedColumnName: "id_unite", nullable: false)]
    private $unite;
    

    /**
     * Get the value of id_module
     */
    public function getIdModule()
    {
        return $this->id_module;
    }

    /**
     * Set the value of id_module
     */
    public function setIdModule($id_module): self
    {
        $this->id_module = $id_module;

        return $this;
    }

    /**
     * Get the value of nom_module
     */
    public function getNomModule()
    {
        return $this->nom_module;
    }

    /**
     * Set the value of nom_module
     */
    public function setNomModule($nom_module): self
    {
        $this->nom_module = $nom_module;

        return $this;
    }

    /**
     * Get the value of unite
     */
    public function getUnite()
    {
        return $this->unite; 
    }

    /**
     * Set the value of unite
     */
    public function setUnite($unite): self
    {
        $this->unite = $unite; 

        return $this;
    }
}
