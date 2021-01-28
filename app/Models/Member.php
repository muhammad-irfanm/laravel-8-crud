<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone_number', 'status'];
    protected $appends = ['status_label'];
    
    public function getStatusLabelAttribute()
    {
        if ($this->status) {
            return '<span class="text-red-500">Keamanan Informasi</span>';
        }
            return '<span class="text-green-500">Sistem Terdistribusi</span>';
    }
}
