<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable =[
        'name',
        'DOB',
        'gender',
        'contact_number',
        'email',
        'doctor_assigned_id'
    ];
    // protected $dates = [
    //     'DOB',
    //     'created_at',
    //     'updated_at',
    // ];
    protected $casts = [
        'DOB' => 'date',
    ];
    public function assignedDoctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_assigned_id');
    }
}
