@extends('layouts.app')

@section('title', '정기 결제 상품 관리')

@section('content')
    <!-- 구독 상품 관리 시작 -->
    <div class="content-right subscription">
        <h2>구독 상품 관리</h2>
        <div class="box">
            <div class="txt_wrap one">
                결제 수단 등록 현황 [{{$cards->count()}}]
            </div>

            <div>
                <table>
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 10%;">
                        <col style="width: *">
                        <col style="width: 12%;">
                        <col style="width: 16%;">
                        <col style="width: 11%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>구독상품</th>
                        <th>결제금</th>
                        <th>결제 카드</th>
                        <th>상품 가입일</th>
                        <th>상태(약정일)</th>
                        <th>서비스개시일</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->name}}</td>
                            <td>{{number_format($product->price)}}원</td>
                            <td>
                                @if($product->subscribe && ! $product->subscribe->state->isUnsubscribe())
                                    {{$product->subscribe->card->name}} ({{$product->subscribe->card->number}})
                                    <div class="card_btn_wrap">
                                        <button class="btn btn_green" name="change" data-id="{{$product->id}}">
                                            변경
                                        </button>
                                        <button class="btn btn_red" name="canceled" data-id="{{$product->id}}">
                                            해지
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn_blue" name="register" data-id="{{$product->id}}">
                                        결제 카드 설정
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->created_at->format('Y-m-d')}}
                                @endif
                            </td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->state->toKorean()}}<br/>
                                    ({{$product->subscribe->payment_day}}일)
                                @endif
                            </td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->due_date?->format('Y-m-d') ?? '-'}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="m_content-right">
        <h2>구독 상품 관리</h2>
        <div class="box">
            <div class="txt_wrap">
                <p>결제 수단 등록 현황 [2]</p>
            </div>
            <div class="">
                <table>
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 10%;">
                        <col style="width: *">
                        <col style="width: 20%;">
                        <col style="width: 20%;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th>구독상품</th>
                        <th>결제금</th>
                        <th>결제 카드</th>
                        <th>상태(약정일)</th>
                        <th>서비스개시일</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->name}}</td>
                            <td>{{number_format($product->price)}}원</td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->card->name}} ({{$product->subscribe->card->number}})
                                    <div class="card_btn_wrap">
                                        {{--<button class="btn btn_green">변경</button>--}}
                                        <button class="btn btn_red" name="canceled" data-id="{{$product->id}}">
                                            해지
                                        </button>
                                    </div>
                                @else
                                    <button class="btn btn_blue" name="register" data-id="{{$product->id}}">
                                        결제 카드 설정
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->state->toKorean()}}<br/>
                                    ({{$product->subscribe->payment_day}}일)
                                @endif
                            </td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->due_date?->format('Y-m-d') ?? '-'}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- 구독 상품 관리 끝 -->

    <!-- payment_card_list end -->

    <script src="{{ asset('js/library_product_list.js') }}"></script>
    @include('modals.library_card_select', ['cards' => $cards])
    @include('modals.library_card_change', ['cards' => $cards])
@endsection
