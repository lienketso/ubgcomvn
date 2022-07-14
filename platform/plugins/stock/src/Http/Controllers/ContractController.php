<?php


namespace Botble\Stock\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Stock\Repositories\Interfaces\ContractInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Stock\Tables\ContractTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Stock\Forms\ContractForm;
use Botble\Stock\Http\Requests\ContractRequest;
use Botble\Base\Forms\FormBuilder;

class ContractController extends BaseController
{
    /**
     * @var ContractInterface
     */
    protected $ContractRepository;

    /**
     * ContractController constructor.
     * @param ContractInterface $ContractRepository
     */
    public function __construct(ContractInterface $ContractRepository)
    {
        $this->ContractRepository = $ContractRepository;
    }
    
    /**
     * @param ContractTable $table
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(ContractTable $table)
    {
        return $table->renderTable();
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $contract = $this->ContractRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $contract));

        page_title()->setTitle("Chỉnh sửa " . $contract->name . '"');

        return $formBuilder->create(ContractForm::class, ['model' => $contract])->renderForm();
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ContractRequest $request, BaseHttpResponse $response)
    {
        $contract = $this->ContractRepository->findOrFail($id);

        $contract->fill($request->input());

        $this->ContractRepository->createOrUpdate($contract);

        event(new UpdatedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));
        return $response
            ->setPreviousUrl(route('contract.index'))
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
            $contract = $this->ContractRepository->findOrFail($id);

            $this->ContractRepository->delete($contract);

            event(new DeletedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));
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
            $contract = $this->ContractRepository->findOrFail($id);
            $this->ContractRepository->delete($contract);
            event(new DeletedContentEvent(CONTRACT_MODULE_SCREEN_NAME, $request, $contract));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

     
}