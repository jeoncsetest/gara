<section id='order_form' class="container">
    <div class="row">
        <h1 class="section_head">
            @lang("Public_ViewEvent.order_details")
        </h1>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            @lang("Public_ViewEvent.below_order_details_header")
        </div>
        <div class="col-md-4 col-md-push-8">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="ico-cart mr5"></i>
                        @lang("Public_ViewEvent.order_summary")
                    </h3>
                </div>

                <div class="panel-body pt0">
                    <table class="table mb0 table-condensed">
                        @foreach($competitions as $competition)
                        <tr>
                            <td class="pl0">{{{$competition['competition']['title']}}} X <b>{{$competition['qty']}}</b></td>
                            <td style="text-align: right;">
                                @if((int)ceil($competition['full_price']) === 0)
                                    @lang("Public_ViewEvent.free")
                                @else
                                {{ money($competition['full_price'], $event->currency) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @if($order_total > 0)
                <div class="panel-footer">
                    <h5>
                        @lang("Public_ViewEvent.total"): <span style="float: right;"><b>{{ $orderService->getOrderTotalWithBookingFee(true) }}</b></span>
                    </h5>
                    @if($event->organiser->charge_tax)
                    <h5>
                        {{ $event->organiser->tax_name }} ({{ $event->organiser->tax_value }}%):
                        <span style="float: right;"><b>{{ $orderService->getTaxAmount(true) }}</b></span>
                    </h5>
                    <h5>
                        <strong>@lang("Public_ViewEvent.grand_total")</strong>
                        <span style="float: right;"><b>{{  $orderService->getGrandTotal(true) }}</b></span>
                    </h5>
                    @endif
                </div>
                @endif

            </div>
            <div class="help-block">
                {!! @trans("Public_ViewEvent.time", ["time"=>"<span id='countdown'></span>"]) !!}
            </div>
        </div>
        <div class="col-md-8 col-md-pull-4">
            <div class="event_order_form">
                {!! Form::open(['url' => route('postSubscriptionCreateOrder', ['event_id' => $event->id]), 'class' => ($order_requires_payment && @$payment_gateway->is_on_site) ? 'ajax payment-form' : 'ajax', 'data-stripe-pub-key' => isset($account_payment_gateway->config['publishableKey']) ? $account_payment_gateway->config['publishableKey'] : '']) !!}

                {!! Form::hidden('event_id', $event->id) !!}

                <h3> @lang("Public_ViewEvent.your_information")</h3>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            {!! Form::label("order_first_name", trans("Public_ViewEvent.first_name")) !!}
                            {!! Form::text("order_first_name", null, ['required' => 'required', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            {!! Form::label("order_last_name", trans("Public_ViewEvent.last_name")) !!}
                            {!! Form::text("order_last_name", null, ['required' => 'required', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label("order_email", trans("Public_ViewEvent.email")) !!}
                            {!! Form::text("order_email", null, ['required' => 'required', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="custom-checkbox">
                                {!! Form::checkbox('is_business', 1, null, ['data-toggle' => 'toggle', 'id' => 'is_business']) !!}
                                {!! Form::label('is_business', trans("Public_ViewEvent.is_business"), ['class' => 'control-label']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="row hidden" id="business_details">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        {!! Form::label("business_name", trans("Public_ViewEvent.business_name")) !!}
                                        {!! Form::text("business_name", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        {!! Form::label("business_tax_number", trans("Public_ViewEvent.business_tax_number")) !!}
                                        {!! Form::text("business_tax_number", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        {!! Form::label("business_address_line1", trans("Public_ViewEvent.business_address_line1")) !!}
                                        {!! Form::text("business_address_line1", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        {!! Form::label("business_address_line2", trans("Public_ViewEvent.business_address_line2")) !!}
                                        {!! Form::text("business_address_line2", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        {!! Form::label("business_address_state", trans("Public_ViewEvent.business_address_state_province")) !!}
                                        {!! Form::text("business_address_state", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        {!! Form::label("business_address_city", trans("Public_ViewEvent.business_address_city")) !!}
                                        {!! Form::text("business_address_city", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        {!! Form::label("business_address_code", trans("Public_ViewEvent.business_address_code")) !!}
                                        {!! Form::text("business_address_code", null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><div class="col-md-12">&nbsp;</div></div>
                <div class="p20 pl0">
                    <a href="javascript:void(0);" class="btn btn-primary btn-xs" id="mirror_buyer_info">
                        @lang("Public_ViewEvent.copy_buyer")
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="competition_holders_details" >
                            <h3>@lang("Public_ViewEvent.competition_holder_information")</h3>
                            <?php
                                $total_attendee_increment = 0;
                            ?>
                            @foreach($competitions as $competition)
                                @for($i=0; $i<=$competition['qty']-1; $i++)
                                <div class="panel panel-primary">

                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <b>{{$competition['competition']['title']}}</b>: @lang("Public_ViewEvent.competition_holder_n", ["n"=>$i+1])
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label("competition_holder_first_name[{$i}][{$competition['competition']['id']}]", trans("Public_ViewEvent.first_name")) !!}
                                                    {!! Form::text("competition_holder_first_name[{$i}][{$competition['competition']['id']}]", null, ['required' => 'required', 'class' => "competition_holder_first_name.$i.{$competition['competition']['id']} competition_holder_first_name form-control"]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    {!! Form::label("competition_holder_last_name[{$i}][{$competition['competition']['id']}]", trans("Public_ViewEvent.last_name")) !!}
                                                    {!! Form::text("competition_holder_last_name[{$i}][{$competition['competition']['id']}]", null, ['required' => 'required', 'class' => "competition_holder_last_name.$i.{$competition['competition']['id']} competition_holder_last_name form-control"]) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {!! Form::label("competition_holder_email[{$i}][{$competition['competition']['id']}]", trans("Public_ViewEvent.email_address")) !!}
                                                    {!! Form::text("competition_holder_email[{$i}][{$competition['competition']['id']}]", null, ['required' => 'required', 'class' => "competition_holder_email.$i.{$competition['competition']['id']} competition_holder_email form-control"]) !!}
                                                </div>
                                            </div>
                                            

                                        </div>

                                    </div>


                                </div>
                                @endfor
                            @endforeach
                        </div>
                    </div>
                </div>

                <style>
                    .offline_payment_toggle {
                        padding: 20px 0;
                    }
                </style>

                @if($order_requires_payment)

                <h3>@lang("Public_ViewEvent.payment_information")</h3>
                    @lang("Public_ViewEvent.below_payment_information_header")
                @if($event->enable_offline_payments)
                    <div class="offline_payment_toggle">
                        <div class="custom-checkbox">
                            @if($payment_gateway === false)
                                {{--  Force offline payment if no gateway  --}}
                                <input type="hidden" name="pay_offline" value="1">
                                <input id="pay_offline" type="checkbox" value="1" checked disabled>
                            @else
                                <input data-toggle="toggle" id="pay_offline" name="pay_offline" type="checkbox" value="1">
                            @endif
                            <label for="pay_offline">@lang("Public_ViewEvent.pay_using_offline_methods")</label>
                        </div>
                    </div>
                    <div class="offline_payment" style="display: none;">
                        <h5>@lang("Public_ViewEvent.offline_payment_instructions")</h5>
                        <div class="well">
                            {!! Markdown::parse($event->offline_payment_instructions) !!}
                        </div>
                    </div>

                @endif


                @if(@$payment_gateway->is_on_site)
                    <div class="online_payment">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('card-number', trans("Public_ViewEvent.card_number")) !!}
                                    <input required="required" type="text" autocomplete="off" placeholder="**** **** **** ****" class="form-control card-number" size="20" data-stripe="number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    {!! Form::label('card-expiry-month', trans("Public_ViewEvent.expiry_month")) !!}
                                    {!!  Form::selectRange('card-expiry-month',1,12,null, [
                                            'class' => 'form-control card-expiry-month',
                                            'data-stripe' => 'exp_month'
                                        ] )  !!}
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    {!! Form::label('card-expiry-year', trans("Public_ViewEvent.expiry_year")) !!}
                                    {!!  Form::selectRange('card-expiry-year',date('Y'),date('Y')+10,null, [
                                            'class' => 'form-control card-expiry-year',
                                            'data-stripe' => 'exp_year'
                                        ] )  !!}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('card-expiry-year', trans("Public_ViewEvent.cvc_number")) !!}
                                    <input required="required" placeholder="***" class="form-control card-cvc" data-stripe="cvc">
                                </div>
                            </div>
                        </div>
                    </div>

                @endif

                @endif

                @if($event->pre_order_display_message)
                <div class="well well-small">
                    {!! nl2br(e($event->pre_order_display_message)) !!}
                </div>
                @endif

               {!! Form::hidden('is_embedded', $is_embedded) !!}
               {!! Form::submit(trans("Public_ViewEvent.checkout_submit"), ['class' => 'btn btn-lg btn-success card-submit', 'style' => 'width:100%;']) !!}

            </div>
        </div>
    </div>
    <img src="https://cdn.attendize.com/lg.png" />
</section>
@if(session()->get('message'))
    <script>showMessage('{{session()->get('message')}}');</script>
@endif
