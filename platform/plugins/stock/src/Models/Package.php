<?php


namespace Botble\Stock\Models;


use Botble\Stock\Enums\PackageStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;

class Package extends BaseModel
{
    use EnumCastable;

    protected $table = 'cp_package';

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'status' => PackageStatusEnum::class,
    ];

    protected $fillable = ['name','percentage','end_date','price_per_stock','qty_of_stock','content','thumbnail','cp_category_id','status','percent_paid_by_ubgxu','percent_paid_by_money', 'content'];
}