<?php

namespace Wink;

use Illuminate\Database\Eloquent\Model;

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
}
