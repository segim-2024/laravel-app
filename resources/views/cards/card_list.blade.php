@extends('layouts.app')

@section('title', '정기 결제 카드 관리')

@section('content')
    <div class="content-right card_management">
        <h2>정기 결제 카드 관리</h2>
        <div class="box">
            <h3 class="">등록된 카드 {{$cards->count()}}개</h3>
            <div class="card_box_wrap">
                @foreach($cards as $card)
                    <div class="card_box">
                        <div class="card">
                            <img src="{{asset("images/ex_card.png")}}" alt="">
                        </div>
                        <p class="card_name">{{$card->name}} ({{$card->number}})</p>
                    </div>
                @endforeach
                @if($cards->count() < 3)
                    <div class="card_box registration">
                        <div class="content">카드 등록하기</div>
                        <p>* 정기 결제 카드는 <strong>최대 3개까지 등록</strong> 가능합니다.</p>
                    </div>
                @endif
            </div>
            <div class="txt_wrap">
                <strong>[유의 사항]</strong><br/>
                카드 소유자 명의의 휴대폰 인증을 진행 한 후 카드 정보를 등록합니다.<br />
                - 개인 카드 : 등록할 카드의 소유주의 생년월일과 휴대폰 본인 인증 생년월일 일치해야 합니다.<br />
                - 법인 카드 : 카드 등록 시 사업자 번호 기입 후 결제 담당자의 휴대폰 번호로 인증을 받습니다.<br /><br />

                * 설정한 결제일대로 등록 익월부터 결제 진행됩니다.<br />
                * 정기 결제 카드는 <strong>최대 3개까지 등록</strong>가능합니다.
            </div>
        </div>
    </div>

    <input type="hidden" name="customerKey" id="customerKey" value="{{request()->user()->toss_customer_key}}">
    <input type="hidden" name="tossCk" id="tossCk" value="{{$tossClientKey}}">
    <script src="{{ asset('js/payment.js') }}"></script>
@endsection
