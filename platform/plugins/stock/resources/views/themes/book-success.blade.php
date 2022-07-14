@extends('plugins/stock::themes.layouts.master')
@section('content')

<main class="main" id="main">
    <div class="order-wrapper">
        <div class="order-top">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="order-detail">
                            <p>Bạn đang đăng ký tham gia gói đầu tư</p>
                            <h1>{{$contract->package->name}}</h1>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="order-meta d-flex justify-content-end flex-column align-items-end">
                            <p>Số hợp đồng: <b>{{$contract->contract_code}}</b></p>
                            <p>Ngày tạo hợp đồng: <b>{{$contract->created_at}}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-body">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="order-body-left">
                            <img src="{{RvMedia::getImageUrl($contract->package->thumbnail, 'full')}}" alt="" class="img-fluid">
                            <div class="order-body-content">
                                {{$contract->package->description}}
                                <p>Nhà đầu tư: <b>{{auth('customer')->user()->name}}</b></p>
                                <p>Lãi suất cam kết: <b>{{$contract->package->percentage}}%</b> </p>
                            </div>
                            <div class="order-body-status">
                                <p>Trạng thái hợp đồng</p>

                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td><b>Pháp lý:</b> {!! $contract->status->toHtml() !!}</td>
                                            @if ($contract->status == \Botble\Stock\Enums\ContractStatusEnum::UNSIGNED)
                                            <td><a href="javascript:;" id="confirm-payment" data-popup="stock-sign-confirm">Ký hợp đồng</a></td>
                                            @elseif ($contract->status == \Botble\Stock\Enums\ContractStatusEnum::REQUEST_TO_SIGN)
                                            <td>Đang đợi nhận hợp đồng & ký kết</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td><b>Hiệu lực:</b> {!! $contract->contract_paid_status->toHtml() !!}</td>
                                            <td>
                                                @if ($contract->contract_paid_status == \Botble\Stock\Enums\ContractPaymentStatusEnum::UNPAID)
                                                <a href="javascript:;" id="confirm-payment" data-popup="stock-payment-confirm">Xác nhận đã thanh toán</a>
                                                @elseif ($contract->contract_paid_status == \Botble\Stock\Enums\ContractPaymentStatusEnum::PENDING_PAYMENT)
                                                Đang đợi xác nhận
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                @if ($contract->contract_paid_status == \Botble\Stock\Enums\ContractPaymentStatusEnum::PAID && $contract->status == \Botble\Stock\Enums\ContractStatusEnum::ACTIVE)
                                    <div class="alert alert-success" role="alert">
                                        Hợp đồng đã được ký kết và có đang trong thời gian hiệu lực
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        Hợp đồng đầu tư chỉ có hiệu lực khi đã được thanh toán và ký xác nhận!
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="order-body-right">
                            <div class="order-body-payment-info">
                                <h4>Thông tin thanh toán hợp đồng</h4>
                                <p>Số tiền: <span class="money">{{format_price($contract->first_buy_price)}}</span></p>
                                <p>Tài khoản thụ hưởng: <b>Công ty cổ phần UBG</b></p>
                                <p>Số tài khoản: <b>711A3123131313</b></p>
                                <p>Ngân hàng: <b>Techcombank</b></p>
                                <p>Mã đối chiếu: <b>{{$contract->contract_code}}</b></p>
                            </div>
                        </div>

                        <div class="help-box" style="width: 100%; max-width: 100%">
                            <div class="help-box-item">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                                <div class="help-box-item-content">
                                    <p>Bạn cần hỗ trợ? Hãy gọi</p>
                                    <p><b>+84 xxx.xxx.xxx</b></p>
                                </div>
                            </div>
                            <div class="help-box-item">
                                <i class="fa-solid fa-envelope"></i>
                                <div class="help-box-item-content">
                                    <p>Hoặc gửi Email cho chúng tôi <a href="mailto:abc@gmail.com">tại đây</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="stock-popup">
    <div class="stock-popup-inner">
        <div class="stock-popup-content" id="stock-payment-confirm">
            <img src="{{asset('vendor/core/plugins/stock/images/payment-success.png')}}" alt="" class="img-fluid">
            <p>Bạn xác nhận là đã thanh toán cho hợp đồng này !</p>
            <form action="{{route('public.book-package.confirm_payment', $contract->contract_code)}}" class="hidden" method="POST">
                {{csrf_field()}}
            </form>
            <div class="stock-popup-confirm">
                <a href="javascript:;" class="stock-popup-accept">Đúng, tôi đã thanh toán</a>
                <a href="javascript:;" class="stock-popup-close" id="stock-popup-close">Không, tôi chưa thanh toán</a>
            </div>
        </div>

        <div class="stock-popup-content" id="stock-sign-confirm">
            <img src="{{asset('vendor/core/plugins/stock/images/sign-contract.jpg')}}" alt="" class="img-fluid">
            <div class="form-sendmail-contract">
                <p>Xác nhận thông tin pháp lý trong hợp đồng</p>
                <form action="{{route('public.stock_request_sign')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="contract_id" value="{{$contract->id}}">
                    <input type="hidden" name="contract_price" value="{{$contract->first_buy_price}}">
                    <input type="hidden" name="contract_name" value="{{$contract->name}}">
                    <input type="hidden" name="contract_code" value="{{$contract->contract_code}}">
                    <div class="form-group mb-3">
                        <label for="">Họ và tên</label>
                        <input type="text" name="name" value="{{$user->name}}" placeholder="Họ tên" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Số điện thoại</label>
                        <input type="text" name="phone" value="{{$user->phone}}" placeholder="Số điện thoại" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Email</label>
                        <input type="text" name="email" value="{{$user->email}}" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Địa chỉ</label>
                        <input type="text" name="address" value="" placeholder="Địa chỉ" class="form-control" required>
                    </div>
                </form>

                <div class="stock-popup-confirm">
                    <a href="javascript:;" class="stock-popup-accept">Xác nhận, và yêu cầu gửi để ký</a>
                    <a href="javascript:;" class="stock-popup-close" id="stock-popup-close">Bỏ qua</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection