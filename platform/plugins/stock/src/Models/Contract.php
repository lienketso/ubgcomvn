<?php

namespace Botble\Stock\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Models\Customer;
use Botble\Stock\Enums\ContractPaymentStatusEnum;
use Botble\Stock\Enums\ContractStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends BaseModel
{
    use EnumCastable;
    /**
     * @var string
     */
    protected $table = 'cp_contract';

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'package_id',
        'customer_id',
        'name',
        'content',
        'file_contract',
        'active_date',
        'expires_date',
        'first_buy_price',
        'first_buy_percentage',
        'status',
        'daily_profit_amount',
        'total_day_paid',
        'amount_withdrawn',
        'amount_available',
        'minimum_withdraw',
        'payment_type',
        'confirm_id',
        'presenter_id',
        'contract_code',
        'percent_paid_by_money',
        'percent_paid_by_ubgxu',
        'total_profit_amount',
        'contract_paid_status'
    ];

    protected $casts = [
        'status'       => ContractStatusEnum::class,
        'contract_paid_status' => ContractPaymentStatusEnum::class
    ];

    /**
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class,'package_id');
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id');
    }

}