<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HcdTraining extends Model
{
    use HasFactory;

    protected $table = 'hcd_trainings';

    protected $fillable = [
        'title',
        'category',
        'institution',
        'validity_years',
        'description',
    ];

    public function employeeTrainings(): HasMany
    {
        return $this->hasMany(HcdEmployeeTraining::class, 'training_id');
    }
}
