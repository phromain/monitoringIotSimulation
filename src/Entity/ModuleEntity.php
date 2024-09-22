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
    private $id_unite;

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
}
