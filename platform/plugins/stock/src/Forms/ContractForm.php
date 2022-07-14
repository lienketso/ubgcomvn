<?php

namespace Botble\Stock\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Stock\Enums\ContractStatusEnum;
use Botble\Stock\Enums\ContractPaymentStatusEnum;

use Botble\Stock\Http\Requests\ContractRequest;
use Botble\Stock\Models\Contract;

class ContractForm extends FormAbstract
{
    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Contract)
            ->setValidatorClass(ContractRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
           
            ->add('content', 'textarea', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 400,
                ],
            ]) 
            ->add('file_contract', 'text', [
                'label'      => 'Link hợp đồng điện tử',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => 'Nhập link hợp đồng điện tử',
                    'data-counter' => 120,
                ],
            ])
            ->add('contract_paid_status', 'customSelect', [
                'label'      => 'Trạng thái thanh toán',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ContractPaymentStatusEnum::labels(),
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => ContractStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
