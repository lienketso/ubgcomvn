<?php

namespace Botble\Stock\Commands;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\UbgxuPayLog;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Botble\Ecommerce\Repositories\Interfaces\UbgxuTransactionInterface;
use Botble\Stock\Models\Contract;
use Botble\Stock\Models\CPHistory;
use Botble\Stock\Repositories\Interfaces\ContractInterface;
use Botble\Stock\Repositories\Interfaces\CPHistoryInterface;
use Illuminate\Console\Command;

class cronPaymentProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create data for cp_contract and cp_history daily ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $contractRepository;
    protected $historyRepository;
    protected $customerRepository;
    protected $ubgxuTransactionRepository;

    public function __construct(ContractInterface $contractRepository,
                                CPHistoryInterface $historyRepository,
                                CustomerInterface $customerRepository, UbgxuTransactionInterface $ubgxuTransactionRepository)
    {
        parent::__construct();
        $this->contractRepository = $contractRepository;
        $this->historyRepository = $historyRepository;
        $this->customerRepository = $customerRepository;
        $this->ubgxuTransactionRepository = $ubgxuTransactionRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            $month = date('m');
            $day = date('d');
            $year = date('Y');
            $contract = $this->contractRepository->getModel()->where('status', 'active')
                ->whereDate('expires_date', '>=', now()->toDateString())->get();

            if ($contract) {
                foreach ($contract as $d) {
                    $data = [
                        'total_day_paid' => $d->total_day_paid + 1,
                        'updated_at'=>now()->toDateString()
                    ];

                    $this->contractRepository->update(['id'=>$d->id],$data);

                    //l??u l???ch s???
                    $dataHis = [
                        'customer_id' => $d->customer_id,
                        'contract_id' => $d->id,
                        'amount' => $d->daily_profit_amount,
                        'type' => 'withdraw',
                        'contract_code'=>$d->contract_code,
                        'history_date' => now()
                    ];
                        CPHistory::create($dataHis);
                    //n???u h???p ?????ng tr??? b???ng coin, c???ng xu v??o cho user - l??u l???ch s???
                    if($d->payment_type=='coin'){
                        $customer = $this->customerRepository->getFirstBy(['id'=>$d->customer_id]);
                        $dataCustomer = [
                            'ubgxu'=>$customer->ubgxu + $d->daily_profit_amount
                        ];
                        //update xu customer
                        $this->customerRepository->update(['id'=>$customer->id],$dataCustomer);
                        //Log xu
                        $contentXu = 'B???n v???a ???????c c???ng th??m '.number_format($d->daily_profit_amount).' xu t??? h???p ?????ng c??? ph???n : '.$d->contract_code;
                        $dataLogXu = [
                            'customer_id'=>$customer->id,
                            'amount'=>$d->daily_profit_amount,
                            'description'=>$contentXu,
                            'transaction_type'=>'increase',
                            'transaction_source'=>'stock',
                            'compare_code'=>$d->contract_code,
                            'status'=>'completed'
                        ];
//                        UbgxuPayLog::create($dataLogXu);
                        $this->ubgxuTransactionRepository->create($dataLogXu);
                    }else{
                        $this->contractRepository->update(['id'=>$d->id],['amount_available'=>$d->amount_available+$d->daily_profit_amount]);
                    }

                    $this->info('Cron job th??nh c??ng t???o l???ch s??? tr??? l??i');

                }
                return 'Success cron job';
            }
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

}
