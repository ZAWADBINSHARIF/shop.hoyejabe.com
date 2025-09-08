<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyDetails extends Model
{
    protected $fillable = ['name', 'logo', 'about', 'width', 'height', 'show_company_name'];


    public static function booted()
    {
        static::updating(function (CompanyDetails $model) {
            if ($model->isDirty('logo') && $model->getOriginal("logo")) {
                Storage::disk('public')->delete($model->getOriginal("logo"));
            }
        });

        static::deleting(function (CompanyDetails $model) {
            if ($model->getOriginal("logo")) {
                Storage::disk('public')->delete($model->getOriginal("logo"));
            }
        });
    }
}
