<?php
/**
 * UbgxuTransaction.php
 * Created by: trainheartnet
 * Created at: 06/05/2022
 * Contact me at: longlengoc90@gmail.com
 */


namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;

class UbgxuTransaction extends BaseModel
{
    /**
     * @var string
     */
    protected $table =  'ubgxu_transaction';

    /**
     * @var string[]
     */
    protected $fillable = [
        'customer_id',
        'amount',
        'description',
        'transaction_type',
        'transaction_source',
        'total_day_refund',
        'paid_day_refund',
        'status',
        'rest_cashback_amount',
        'compare_code',
        'percent_cashback',
        'user_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}