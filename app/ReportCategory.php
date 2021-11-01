<?php

namespace App;

use App\ProjectReport;
use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'description',
        'cwe_cve_reference',
        'created_at',
        'updated_at'
    ];

    public function project_reports()
    {
        return $this->hasMany(ProjectReport::class);
    }
}
