<?php

namespace Botble\Stock\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Stock\Enums\PackageStatusEnum;
use Botble\Stock\Http\Requests\PackageRequest;
use Botble\Stock\Forms\Fields\AssignCPCategorySelector;
use Botble\Stock\Models\Package;

class PackageForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Package)
            ->setValidatorClass(PackageRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('cp_category_id', 'html', [
                'html'       => AssignCPCategorySelector::render($this->getModel()),
                'label'      => 'Loại hợp đồng',
                'wrapper'    => false,
                'label_show' => false,
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
            ->add('percentage', 'number', [
                'label'      => 'Lợi nhuận(%)',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Lợi nhuận',
                    'data-counter' => 100,
                ],
            ])
            ->add('price_per_stock', 'number', [
                'label'      => 'Giá trên 1 cổ phần',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Giá trên 1 cổ phần',
                    'data-counter' => 120,
                ],
            ])   
            ->add('qty_of_stock', 'number', [
                'label'      => 'Số lượng cổ phần',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Số lượng cổ phần',
                    'data-counter' => 120,
                ],
            ])          
            ->add('percent_paid_by_ubgxu', 'number', [
                'label'      => 'Thanh toán lãi suất theo Xu (%)',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Thanh toán lãi suất theo Xu',
                    'data-counter' => 100,
                ],
            ]) 
            ->add('percent_paid_by_money', 'number', [
                'label'      => 'Thanh toán lãi suất theo VNĐ (%)',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Thanh toán lãi suất theo VNĐ',
                    'data-counter' => 100,
                ],
            ]) 
            ->add('end_date', 'text', [
                'label'      => 'Kỳ hạn',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => 'Kỳ hạn',
                    'class'        => 'form-control',  
                ]
            ])  
            ->add('thumbnail', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => PackageStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}
