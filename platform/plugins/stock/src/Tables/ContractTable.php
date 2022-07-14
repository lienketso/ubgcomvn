<?php

namespace Botble\Stock\Tables;

use BaseHelper;
use Botble\Stock\Enums\ContractStatusEnum;
use Botble\Stock\Repositories\Interfaces\ContractInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ContractTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * ContractTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ContractInterface $ContractRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ContractInterface $ContractRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $ContractRepository;

        if (!Auth::user()->hasAnyPermission(['contract.edit', 'contract.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('contract_code', function ($item) {
                if (!Auth::user()->hasPermission('contract.edit')) {
                    return $item->contract_code;
                }
                return Html::link(route('contract.edit', $item->id), $item->contract_code);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('customer_id', function ($item) {      
                return $item->customer->name;
             })
             ->editColumn('package_name', function ($item) {
                return $item->package->name;
             })
             ->editColumn('first_buy_price', function ($item) {
                return $item->first_buy_price;
            })
            ->editColumn('first_buy_percentage', function ($item) {
                return $item->first_buy_percentage;
            })
            ->editColumn('contract_paid_status', function ($item) {
                return $item->contract_paid_status->toHtml();
            })
            ->editColumn('active_date', function ($item) {
                return $item->active_date;
            })
            ->editColumn('expires_date', function ($item) {
                return $item->expires_date;
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at;
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('contract.edit', 'contract.destroy', $item);
            });
            
        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
        ->with([
            'package',           
            'customer'
        ])
        ->select([
            'id',
            'customer_id',
            'contract_code',
            'package_id',
            'contract_paid_status',
            'first_buy_price',
            'first_buy_percentage',
            'active_date',
            'expires_date',
            'created_at',
            'status'
        ]);
        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'contract_code' => [
                'title' => 'Mã Hợp đồng',
                'class' => 'text-start',
            ],
            'customer_id' => [
                'title' => 'Tên nhà đầu tư',
                'class' => 'text-start',
            ],
            'package_name' => [
                'title' => 'Tên gói',
                'class' => 'text-start',
            ],
            'first_buy_price' => [
                'title' => 'Giá trị hợp đồng(VND)',
                'class' => 'text-start',
            ],
            'first_buy_percentage' => [
                'title' => 'Lợi nhuận(%)',
                'class' => 'text-start',
            ],
            'contract_paid_status' => [
                'title' => 'Trạng thái thanh toán',
                'class' => 'text-start',
            ],
            'status' => [
                'title' => 'Trạng thái',
                'class' => 'text-start',
            ],
            'active_date' => [
                'title' => 'Ngày bắt đầu',
                'class' => 'text-start',
            ],
            'expires_date' => [
                'title' => 'Ngày kết thúc',
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => 'Ngày khởi tạo',
                'class' => 'text-start',
            ],
           
           
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        // return $this->addCreateButton(route('Contract.create'), 'Contract.create');
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('contract.deletes'), 'contract.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'customer_id' => [
                'title' => 'Tên nhà đầu tư',
                'type'     => 'select-ajax',
                'validate' => 'required',
                'callback' => 'getCustomers',
            ],
            'contract_code' => [
                'title' => 'Mã Hợp đồng',
                'type'     => 'text',
            ],
            'first_buy_price' => [
                'title' => 'Giá trị hợp đồng(VND)',
                'type'     => 'text',
            ],
            'created_at' => [
                'title' => 'Ngày giao dịch',
                'type'  => 'date',
            ],
        ];
    }

}
