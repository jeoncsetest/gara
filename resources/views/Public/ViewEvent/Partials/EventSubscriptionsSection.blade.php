<section id="competitions" class="container">
    <div class="row">
        <h1 class='section_head'>
            @lang("Public_ViewEvent.COMPETITIONS")
        </h1>
    </div>

    @if($event->end_date->isPast())
        <div class="alert alert-boring">
            @lang("Public_ViewEvent.event_already", ['started' => trans('Public_ViewEvent.event_already_ended')])
        </div>
    @else

        @if($competitions->count() > 0)

            {!! Form::open(['url' => route('postValidateTickets', ['event_id' => $event->id]), 'class' => 'ajax']) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <div class="tickets_table_wrap">
                            <table class="table">
                                <?php
                                $is_free_event = true;
                                ?>
                                @foreach($competitions as $competition)
                                    <tr class="ticket" property="offers" typeof="Offer">
                                        <td>
                                <span class="ticket-title semibold" property="name">
                                    {{$competition->title}}
                                </span>
                                            <p class="ticket-descripton mb0 text-muted" property="description">
                                                {{$competition->description}}
                                            </p>
                                        </td>
                                        <td style="width:200px; text-align: right;">
                                            <div class="ticket-pricing" style="margin-right: 20px;">
                                                <?php
                                                $is_free_event = false;
                                                ?>
                                                <span title='{{money($competition->price, $event->currency)}} @lang("Public_ViewEvent.competition_price")'>{{money($competition->price, $event->currency)}} </span>
                                                <span class="tax-amount text-muted text-smaller">{{ ($event->organiser->tax_name && $event->organiser->tax_value) ? '(+'.money(($competition->price*($event->organiser->tax_value)/100), $event->currency).' '.$event->organiser->tax_name.')' : '' }}</span>
                                                <meta property="priceCurrency"
                                                        content="{{ $event->currency->code }}">
                                                <meta property="price"
                                                        content="{{ number_format($competition->price, 2, '.', '') }}">
                                            </div>
                                        </td>
                                        <td style="width:85px;">
                                                @if('status' === config('attendize.ticket_status_sold_out'))
                                                    <span class="text-danger" property="availability"
                                                          content="http://schema.org/SoldOut">
                                    @lang("Public_ViewEvent.sold_out")
                                </span>
                                                @elseif('status' === config('attendize.ticket_status_before_sale_date'))
                                                    <span class="text-danger">
                                    @lang("Public_ViewEvent.sales_have_not_started")
                                </span>
                                                @elseif('status' === config('attendize.ticket_status_after_sale_date'))
                                                    <span class="text-danger">
                                    @lang("Public_ViewEvent.sales_have_ended")
                                </span>
                                                @else
                                                    {!! Form::hidden('competitions[]', $competition->id) !!}
                                                    <meta property="availability" content="http://schema.org/InStock">
                                                    <select name="competition_{{$competition->id}}" class="form-control"
                                                            style="text-align: center">
                                                        @if ($competitions->count() > 1)
                                                            <option value="0">0</option>
                                                        @endif
                                                        @for($i=0; $i<=$competition->max_competitors; $i++)
                                                            <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($competitions->count() > 0)
                                <tr class="has-access-codes" data-url="{{route('postShowHiddenTickets', ['event_id' => $event->id])}}">
                                    <td colspan="3"  style="text-align: left">
                                        @lang("Public_ViewEvent.has_unlock_codes")
                                        <div class="form-group" style="display:inline-block;margin-bottom:0;margin-left:15px;">
                                            {!!  Form::text('unlock_code', null, [
                                            'class' => 'form-control',
                                            'id' => 'unlock_code',
                                            'style' => 'display:inline-block;width:65%;text-transform:uppercase;',
                                            'placeholder' => 'ex: UNLOCKCODE01',
                                        ]) !!}
                                            {!! Form::button(trans("basic.apply"), [
                                                'class' => "btn btn-success",
                                                'id' => 'apply_access_code',
                                                'style' => 'display:inline-block;margin-top:-2px;',
                                                'data-dismiss' => 'modal',
                                            ]) !!}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" style="text-align: center">
                                        @lang("Public_ViewEvent.below_competitions")
                                    </td>
                                </tr>
                                <tr class="checkout">
                                    <td colspan="3">
                                            <div class="hidden-xs pull-left">
                                                <img class=""
                                                     src="{{asset('assets/images/public/EventPage/credit-card-logos.png')}}"/>
                                            </div>
                                        {!!Form::submit(trans("Public_ViewEvent.subscribe"), ['class' => 'btn btn-lg btn-primary pull-right'])!!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::hidden('is_embedded', $is_embedded) !!}
            {!! Form::close() !!}

        @else

            <div class="alert alert-boring">
                @lang("Public_ViewEvent.competitions_are_currently_unavailable")
            </div>

        @endif

    @endif

</section>
