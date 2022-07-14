<?php

namespace Botble\Stock\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Stock\Enums\WithdrawStatusEnum;
use Botble\Stock\Http\Requests\WithdrawRequest;
use Botble\Stock\Forms\Fields\AssignCPCategorySelector;
use Botble\Stock\Models\Withdraw;

class WithdrawForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Withdraw)
            ->setValidatorClass(WithdrawRequest::class)
            ->withCustomFields()
            
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => WithdrawStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
