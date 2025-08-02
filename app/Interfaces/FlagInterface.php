<?php

namespace App\Interfaces;

use App\Constants\Flags;

interface FlagInterface
{
    /**
     * Summary of selectFlags
     * @param \App\Constants\Flags[] $flags
     */
    public function selectFlags(array $flags);
    /**
     * Summary of selectFlags
     * @param \App\Constants\Flags[] $flags
     */
    public function selectIdsByName(array $flags);
}
