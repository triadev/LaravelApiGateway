<?php
namespace App\Providers\Gateway\Events;

use Illuminate\Support\Facades\Validator;

/**
 * Class GatewayEvent
 *
 * @author Christopher Lorke <lorke@traum-ferienwohnungen.de>
 * @package App\Providers\Gateway\Contract
 */
abstract class GatewayEvent
{
    /**
     * Trigger
     *
     * @param int $userId
     * @param array $eventParams
     * @return bool
     */
    public function trigger(int $userId, array $eventParams) : bool
    {
        if (!$this->validateEventParams($eventParams)) {
            return false;
        }

        return $this->execute($userId, $eventParams);
    }

    /**
     * Validate event params
     *
     * @param array $eventParams
     * @return bool
     */
    private function validateEventParams(array $eventParams) : bool
    {
        $validator = Validator::make($eventParams, $this->getValidationForEventParams());

        if ($validator->fails()) {
            return false;
        }

        return true;
    }

    /**
     * Get validation for event params
     *
     * @return array
     */
    abstract protected function getValidationForEventParams() : array;

    /**
     * Execute
     *
     * @param int $userId
     * @param array $eventParams
     * @return bool
     */
    abstract protected function execute(int $userId, array $eventParams) : bool;
}
