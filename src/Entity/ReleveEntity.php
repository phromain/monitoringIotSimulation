<?php

namespace App\Entity;

use App\Repository\ReleveEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleveEntityRepository::class)]
class ReleveEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_releve;

    #[ORM\ManyToOne(targetEntity: ModuleEntity::class, fetch: "EAGER")]
    #[ORM\JoinColumn(name: "id_module", referencedColumnName: "id_module", nullable: false)]
    private $module;

    #[ORM\Column(type: 'float', nullable: true)]
    private $valeur;

    #[ORM\Column(type: 'boolean')]
    private $etat;

    #[ORM\Column(type: 'datetime')]
    private $date;

    /**
     * Get the value of id_releve
     */
    public function getIdReleve()
    {
        return $this->id_releve;
    }

    /**
     * Set the value of id_releve
     */
    public function setIdReleve($id_releve): self
    {
        $this->id_releve = $id_releve;

        return $this;
    }

    /**
     * Get the value of module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set the value of module
     */
    public function setModule($module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get the value of valeur
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set the value of valeur
     */
    public function setValeur($valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get the value of etat
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set the value of etat
     */
    public function setEtat($etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }
}
