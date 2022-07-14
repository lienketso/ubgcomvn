<?php


namespace Botble\Ecommerce\Forms;


use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Models\Customer;

class EditXuForm extends FormAbstract
{

    public function buildForm()
    {
        $this->setupModel(new Customer)
            ->withCustomFields()
            ->add('transaction_type', 'customSelect', [
                'label'      => 'Loại update',
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => ['increase'=>'Cộng xu','decrease'=>'Giảm xu'],
            ])
            ->add('ubgxu','number',[
                'label'=>'Số xu hiện tại',
                'label_attr'=>['class'=>'control-label'],
                'attr'=>[
                    'placeholder'=>'',
                    'disabled'=>'disable'
                ]
            ])
            ->add('ubgxu_update','number',[
                'label'=>'Số xu muốn cập nhật ( Cộng hoặc trừ đi số xu hiện tại )',
                'label_attr'=>['class'=>'control-label required'],
                'attr'=>[
                    'placeholder'=>'Nhập số xu muốn sửa',
                ]
            ])
            ->add(
                'why','text',[
                    'label'=>'Lý do sửa',
                    'label_attr'=>['class'=>'control-label required'],
                    'attr'=>['placeholder'=>'Lý do thay đổi số xu']
                ]
            );
    }

}