<?php

namespace App\Observers;

use App\Models\Digital;

class DigitalObserver
{
    /**
     * Handle the Digital "created" event.
     */
    public function created(Digital $digital): void
    {
        //
    }

    /**
     * Handle the Digital "updated" event.
     */
    public function updated(Digital $digital): void
    {
        //
    }

    /**
     * Handle the Digital "deleted" event.
     */
    public function deleted(Digital $digital): void
    {
        //
    }

    /**
     * Handle the Digital "restored" event.
     */
    public function restored(Digital $digital): void
    {
        //
    }

    /**
     * Handle the Digital "force deleted" event.
     */
    public function forceDeleted(Digital $digital): void
    {
        //
    }
}
