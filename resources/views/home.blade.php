@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <wallet-component :wallet="{{$user->wallet()}}"></wallet-component>
        <identity-component :user="{{$user}}"></identity-component>
        <div class="col-md-4">
            <div>
                <div class="card">
                    <div class="card-header"> Referral Code</div>

                    <div class="card-body">
                        <p>{{ $user->referral_code }}</p>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection
