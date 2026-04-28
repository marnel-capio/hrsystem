<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusinessUnitModel extends Model
{
    protected $table = 'business_units';
 
    public $timestamps = true;
 
    protected $fillable = [
        'business_unit'
    ];


    //TO BE USED IN RESOURCE REQS
    public static function getBusinessUnits()
    {
        return DB::table('business_units')
            ->select('id', 'business_unit')
            ->orderBy('business_unit')
            ->get();
    }


    public function requisitions2()
    {
        return $this->hasMany(IntermediateRequisitionModel::class, 'business_unit_id', 'id');
    }

//     public function requisitions()
// {
//     return $this->hasMany(
//         IntermediateRequisitionModel::class,
//         'business_unit_id',
//         'id'
//     );
// }

}