<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['name', 'email', 'ticket_id', 'amount'];

    public function ticket() {
        $this->hasMany(Ticket::class);
    }
}
