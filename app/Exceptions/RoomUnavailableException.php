<?php

namespace App\Exceptions;

use Exception;

class RoomUnavailableException extends Exception
{
    protected $unavailableRooms;

    public function __construct(array $unavailableRooms, $message = "Some rooms are unavailable", $code = 0, Exception $previous = null)
    {
        $this->unavailableRooms = $unavailableRooms;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the list of unavailable rooms.
     *
     * @return array
     */
    public function getUnavailableRooms(): array
    {
        return $this->unavailableRooms;
    }
}
