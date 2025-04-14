<?php

namespace App\Http\Traits;

use App\Helpers\LogHelper;

trait LogsActions
{
    /**
     * Log an action for the current model.
     *
     * @param string $action
     * @param array|null $data
     * @return void
     */
    public function logAction($reservation)
    {
        LogHelper::logAction($this, $reservation);
    }
}
