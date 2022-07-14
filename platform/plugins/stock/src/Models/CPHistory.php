<?php


namespace Botble\Stock\Models;


use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CPHistory extends BaseModel
{
    protected $table = 'cp_history';
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * @var array
     */
    protected $fillable = ['customer_id','package_id','amount','history_date','type','contract_code','contract_id','contract_code'];

    public function contract(): BelongsTo{
        return $this->belongsTo(Contract::class,'contract_id');
    }

    public function package(): BelongsTo{
        return $this->belongsTo(Package::class,'package_id');
    }

    public function customer(): BelongsTo{
        return $this->belongsTo(Customer::class,'customer_id');
    }

}