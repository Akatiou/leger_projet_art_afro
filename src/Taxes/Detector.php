<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Detector
{
    protected $seuil;

    public function __construct($seuil)
    {
        $this->seuil = $seuil;
    }

    public function detect(float $prix): bool
    {
        if ($prix > $this->seuil) {
            return true;
        }

        return false;
    }
}
