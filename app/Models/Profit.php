<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profit extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_id', 'total'];

    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    protected function createdAt() : Attribute
    {
        return Attribute::make(
            get: fn ($value) =>
            Carbon::parse($value)->format('d-M-Y H:i:s')
        );
    }
}
