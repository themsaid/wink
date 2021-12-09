<?php

namespace Wink;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

abstract class AbstractWinkModel extends Model
{
    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return config('wink.database_connection');
    }
    
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
