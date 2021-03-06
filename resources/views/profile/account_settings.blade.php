@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    <div class="container" id="container-profile ">
        <div class="col-lg-12 col-xs-12 col-sm-12" id="box-user-profile">
            <div class="col-lg-6 col-xs-12 col-sm-6">
                <h2>Account settings</h2>
            </div>

            <div class="col-lg-12">
                <div class="box-form">
                    @if(session('message'))
                        @if(is_string(session('message')))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @else
                            <div class="alert alert-{{@session('message')['type'] ?: 'success'}}">{{ @session('message')['message'] ?: session('message') }}</div>
                        @endif
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('update-user-data') }}"
                          autocomplete="off">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-lg-6 col-sm-6 col-md-6{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name">First Name</label>
                                <input id="first_name" type="text" class="form-control" name="first_name"
                                       value="{{ $user->first_name }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" type="text" class="form-control" name="last_name"
                                       value="{{ $user->last_name }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-6 col-sm-6 col-md-6{{ $errors->has('old_password') ? ' has-error' : '' }}">
                                <label for="password">Old password</label>
                                <input id="password" type="password" class="form-control" name="old_password" value="">

                                @if ($errors->has('old_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password-confirm">New password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password" value="">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default btn-block btn-lg box-form-btn-green">
                            Save
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 col-sm-6">
                <h2>Subscription info</h2>
            </div>
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="col-lg-4" style="font-size:16px">
                    @if(!$user->subscription('main'))
                        An error occured while fetching subscription.
                        There is no active subscription on our database.
                        Please contact with support team. #9000
                    @else
                        @if($user->subscription('main')->ends_at)
                            Your subscription has been cancelled and will be ended at
                            <strong>{{($user->subscription('main')->ends_at)->format('d M Y')}}</strong>
                            . You won't be charged for next cycle. <br/>

                            <a class="btn btn-success" style="margin-top: 5px"
                               href="/intapi/resume-subscription">Resume
                                subscription</a>
                        @else
                            @if(!$user->stripe_id)
                                An error occured while fetching subscription.
                                Please contact with support team.  #8001
                            @elseif($user->stripe_id)

                                @if(!$user->subscription('main')->stripe_id)
                                    An error occured while fetching subscription. #8000-{{str_random(6)}}
                                @elseif($user->subscription('main')->stripe_id)
                                    @if(!$subscription)
                                        An error occured while fetching subscription.
                                        Please contact with support team. #9001-{{str_random(6)}}
                                    @else
                                        @if(!$subscription->plan->id)
                                            An error occured while fetching subscription.
                                            Please contact with support team. #9002-{{str_random(6)}}
                                        @else
                                            @if($isGrace)
                                                On grace period, valid to: <span
                                                        class="label label-success">{{$subscription->current_period_end ? ( date('d F Y',$subscription->current_period_end)) : ''}}</span>
                                                <a class="btn btn-success" style="margin-top: 5px"
                                                   href="/intapi/resume-subscription">Resume
                                                    subscription</a>
                                                <br>
                                            @else
                                                Next Billing:
                                                <span class="label label-success">{{$subscription->current_period_end ? ( date('d F Y',$subscription->current_period_end)) : ''}}</span>
                                            @endif
                                            <br><br>

                                            @if(!$isGrace)
                                                <a class="btn btn-warning" href="/intapi/cancel-subscription">Cancel
                                                    subscription</a>
                                                <br><br>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection