<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['name', 'email', 'ticket_id', 'amount', 'order_id'];

    public function ticket() {
        $this->hasMany(Ticket::class);
    }
}
