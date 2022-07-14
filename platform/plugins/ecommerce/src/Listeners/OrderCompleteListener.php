<?php
/**
 * OrderCompleteListener.php
 * Created by: trainheartnet
 * Created at: 05/05/2022
 * Contact me at: longlengoc90@gmail.com
 */


namespace Botble\Ecommerce\Listeners;

use Botble\Ecommerce\Services\HandleAffiliateService;
use DB;
use Carbon\Carbon;
use Exception;

class OrderCompleteListener
{
    /**
     * @param $event
     * @throws \Throwable
     */
    public function handle($event)
    {
        // Hoàn trả hoa hồng
        $commissionService = new HandleAffiliateService();
        $commissionService->resolveOrder($event->order->id);

        // Tạo giao dịch hoàn xu bằng tiền mặt
        $percent = intval(get_ecommerce_setting('ubgxu_percent_per_order')) / 100;
        $totalDayRefund = intval(get_ecommerce_setting('ubgxu_total_day_refund'));

        $cashbackAmount = $event->order->sub_total - $event->order->paid_by_ubgxu;
        if ($cashbackAmount > 0) {
            DB::table('ubgxu_transaction')->insert([
                'customer_id' => $event->order->user_id,
                'amount' => $cashbackAmount,
                'transaction_type' => 'cash',
                'description' => 'Giao dịch bằng tiền đơn hàng '.get_order_code($event->order->id),
                'transaction_source' => 'https://ubgmart.com',
                'rest_cashback_amount' => $cashbackAmount * $percent,
                'percent_cashback' => $percent,
                'total_day_refund' => $totalDayRefund,
                'created_at' => Carbon::now(),
                'compare_code' => get_order_code($event->order->id),
                'status' => 'on_cash_back'
            ]);
        }
    }
}