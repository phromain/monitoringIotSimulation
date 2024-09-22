<?php

namespace App\Entity;

use App\Repository\MonitoringEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonitoringEntityRepository::class)]
class MonitoringEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id_monitoring_module;

    #[ORM\ManyToOne(targetEntity: ModuleEntity::class)]
    #[ORM\JoinColumn(name: 'id_module', referencedColumnName: 'id_module')]
    private $module;

    /**
     * Get the value of id_monitoring_module
     */
    public function getIdMonitoringModule()
    {
        return $this->id_monitoring_module;
    }

    /**
     * Set the value of id_monitoring_module
     */
    public function setIdMonitoringModule($id_monitoring_module): self
    {
        $this->id_monitoring_module = $id_monitoring_module;

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
}
