<?php
/**
 * ContractManagerController.php
 * Created by: trainheartnet
 * Created at: 23/06/2022
 * Contact me at: longlengoc90@gmail.com
 */


namespace Botble\Stock\Http\Controllers\Fronts;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Http\Requests\SettingRequest;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Stock\Enums\ContractStatusEnum;
use Botble\Stock\Repositories\Interfaces\ContractInterface;
use Botble\Stock\Repositories\Interfaces\CPHistoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ContractManagerController extends BaseController
{
    /**
     * @var ContractInterface
     */
    protected $contractRepository;

    /**
     * @var CPHistoryInterface
     */
    protected $contractHistoryRepository;

    /**
     * ContractManagerController constructor.
     * @param ContractInterface $contractRepository
     * @param CPHistoryInterface $contractHistoryRepository
     */
    public function __construct(
        ContractInterface $contractRepository,
        CPHistoryInterface $contractHistoryRepository
    )
    {
        $this->contractRepository = $contractRepository;
        $this->contractHistoryRepository = $contractHistoryRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getContract()
    {
        page_title()->setTitle('Quản lý hợp đồng');

        $customerId = auth('customer')->id();

        $uncompleteContract = $this->contractRepository->allBy([
            'customer_id' => $customerId,
            ['status', '!=', ContractStatusEnum::ACTIVE]
        ], ['package']);

        $activeContract = $this->contractRepository->allBy([
            'customer_id' => $customerId,
            'status' => ContractStatusEnum::ACTIVE
        ], ['package']);

        $expiredContract = $this->contractRepository->allBy([
            'customer_id' => $customerId,
            'status' => ContractStatusEnum::EXPIRED
        ], ['package']);

        return view('plugins/stock::dashboard.contracts.index', compact(
            'uncompleteContract', 'activeContract', 'expiredContract'
        ));
    }

    public function getContractDetail(
        $id,
        BaseHttpResponse $response
    )
    {
        $customerId = auth('customer')->id();

        $contract = $this->contractRepository->findById($id, ['package']);

        if ($customerId != $contract->customer_id) {
            return $response->setNextUrl('stock-manager.contract')
                ->setError()
                ->setMessage('Bạn không có quyền xem hợp đồng này');
        }

        page_title()->setTitle('Thông tin chi tiết hợp đồng');

        $contactHistory = $this->contractHistoryRepository->getModel()
            ->where('customer_id', $customerId)
            ->where('contract_id', $id)
            ->paginate(30);

        return view('plugins/stock::dashboard.contracts.detail', compact('contract', 'contactHistory'));
    }

    /**
     * @return Application|Factory|View
     */
    public function getSetting()
    {
        page_title()->setTitle('Cài đặt');

        $collaborator = auth('customer')->user();

        return view('plugins/stock::dashboard.settings', compact('collaborator'));
    }

    /**
     * @param SettingRequest $request
     * @param StoreInterface $storeRepository
     * @param BaseHttpResponse $response
     */
    public function saveSettings(SettingRequest $request, StoreInterface $storeRepository, BaseHttpResponse $response)
    {
        $customer = auth('customer')->user();
        if ($customer && $customer->id) {
            $walletInfo = $customer->walletInfo;
            $walletInfo->bank_info = $request->input('bank_info');
            $walletInfo->save();
        }

        event(new UpdatedContentEvent(STORE_MODULE_SCREEN_NAME, $request, $customer));

        return $response
            ->setNextUrl(route('stock-manager.settings'))
            ->setMessage(__('Cập nhật thông tin thành công!'));
    }
}