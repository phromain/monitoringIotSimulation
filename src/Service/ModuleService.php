<?php

namespace App\Service;

use App\Repository\ModuleEntityRepository;


class ModuleService
{
    private $moduleRepository;

    public function __construct(ModuleEntityRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function getAllModules()
    {
        return $this->moduleRepository->findAll();
    }
}
