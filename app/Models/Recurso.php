<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'recurso';

    public function menu()
    {
    	return $this->hasMany(Menu::class);
    }
}
