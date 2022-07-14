<?php


namespace Botble\Stock\Models;

use Botble\Base\Models\BaseModel;
// use Botble\Stock\Enums\WithdrawStatusEnum;
use Botble\Ecommerce\Models\Customer;
use Botble\Stock\Enums\WithdrawStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Botble\Base\Traits\EnumCastable;

class Withdraw extends BaseModel
{
    use EnumCastable;

    protected $table = 'cp_withdraw';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'customer_id',
        'package_id',
        'contract_id',
        'amount',
        'confirm_id',
        'status'
    ];

     protected $casts = [
         'status' => WithdrawStatusEnum::class,
     ];
  
    public function contract(): BelongsTo {
        return $this->belongsTo(Contract::class,'contract_id');
    }

    public function package(): BelongsTo {
        return $this->belongsTo(Package::class,'package_id');
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class,'customer_id');
    }
}