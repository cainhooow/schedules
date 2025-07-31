<?php

namespace App\Interfaces;

use App\Constants\FlagConstant;

interface FlagInterface
{
    /**
     * Summary of selectFlags
     * @param \App\Constants\FlagConstant[] $flags
     */
    public function selectFlags(array $flags);
    /**
     * Summary of selectFlags
     * @param \App\Constants\FlagConstant[] $flags
     */
    public function selectIdsByName(array $flags);
}
