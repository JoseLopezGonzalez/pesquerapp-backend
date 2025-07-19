<?php

namespace App\Traits;

trait UsesTenantConnection
{
    public function initializeUsesTenantConnection()
    {
        $this->setConnection('tenant');
    }
}
