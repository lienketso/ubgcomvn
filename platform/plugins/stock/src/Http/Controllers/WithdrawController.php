<?php


namespace Botble\Stock\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Stock\Repositories\Interfaces\WithdrawInterface;
use Botble\Stock\Http\Requests\WithdrawRequest;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Stock\Tables\WithdrawTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Stock\Forms\WithdrawForm;
use Botble\Base\Forms\FormBuilder;



class WithdrawController extends BaseController
{
    /**
     * @var WithdrawInterface
     */
    protected $WithdrawRepository;

    /**
     * WithdrawController constructor.
     * @param WithdrawInterface $WithdrawRepository
     */
    public function __construct(WithdrawInterface $WithdrawRepository)
    {
        $this->WithdrawRepository = $WithdrawRepository;
    }
    
    /**
     * @param WithdrawTable $table
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(WithdrawTable $table)
    {
        return $table->renderTable();
    }

     /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle('Tạo mới hợp đồng');

        return $formBuilder->create(WithdrawForm::class)->renderForm();
    }

    /**
     * @param WithdrawRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(WithdrawRequest $request, BaseHttpResponse $response)
    {
        $Withdraw = $this->WithdrawRepository->createOrUpdate($request->input());
        event(new CreatedContentEvent(Withdraw_MODULE_SCREEN_NAME, $request, $Withdraw));
        return $response
            ->setPreviousUrl(route('Withdraw.index'))
            ->setNextUrl(route('withdraw.edit', $Withdraw->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $Withdraw = $this->WithdrawRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $Withdraw));

        page_title()->setTitle("Chỉnh sửa " . $Withdraw->name . '"');

        return $formBuilder->create(WithdrawForm::class, ['model' => $Withdraw])->renderForm();
    }

    /**
     * @param $id
     * @param FaqWithdrawRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, WithdrawRequest $request, BaseHttpResponse $response)
    {
        $withdraw = $this->WithdrawRepository->findOrFail($id);

        $withdraw->fill($request->input());

        $this->WithdrawRepository->createOrUpdate($withdraw);

        event(new UpdatedContentEvent(WITHDRAW_MODULE_SCREEN_NAME, $request, $withdraw));
        return $response
            ->setPreviousUrl(route('withdraw.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $Withdraw = $this->WithdrawRepository->findOrFail($id);

            $this->WithdrawRepository->delete($Withdraw);

            event(new DeletedContentEvent(Withdraw_MODULE_SCREEN_NAME, $request, $Withdraw));
            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $Withdraw = $this->WithdrawRepository->findOrFail($id);
            $this->WithdrawRepository->delete($Withdraw);
            event(new DeletedContentEvent(Withdraw_MODULE_SCREEN_NAME, $request, $Withdraw));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}