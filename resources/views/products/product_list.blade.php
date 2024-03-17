@extends('layouts.app')

@section('title', '정기 결제 상품 관리')

@section('content')
    <!-- payment_card_list -->
    <div class="content-right card_list">
        <h2>정기 결제 상품 관리</h2>
        <div class="box">
            <p class="">결제 수단 등록 현황 [{{$products->count()}}]</p>

            <div class="">
                <table>
                    <colgroup>
                        <col style="width: 18%;">
                        <col style="width: 14%;">
                        <col>
                        <col style="width: 18%;">
                        <col style="width: 8%;">
                    </colgroup>
                    <tr>
                        <th>정기 결제 상품</th>
                        <th>결제금</th>
                        <th>결제 카드</th>
                        <th>카드 등록일</th>
                        <th>결제일</th>
                    </tr>
                    @foreach($products as $product)
                        <tr data-product-id="{{$product->id}}">
                            <td>
                                {{$product->name}}
                            </td>
                            <td>
                                {{number_format($product->price)}}원
                            </td>
                            <td class="card_name">
                                @if($product->subscribe)
                                    {{$product->subscribe->card->name}} ({{$product->subscribe->card->number}})
                                    <button class="btn btn_green" data-role="register" data-product-id="{{$product->id}}">
                                        변경
                                    </button>
                                @else
                                    <button class="btn btn_blue" data-role="register" data-product-id="{{$product->id}}">
                                        결제 카드 설정
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($product->subscribe)
                                    {{$product->subscribe->created_at}}
                                @endif
                            </td>
                            <td>
                                {{$product->payment_day}}일
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{--<div class="paging">
            <a class="arrow prev" href="#">
                <img src="{{asset('/images/paging_arrow.png')}}" alt="이전">
            </a>
            <a class="active" href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a class="arrow next" href="#">
                <img src="{{asset('/images/paging_arrow.png')}}" alt="다음">
            </a>
        </div>--}}
    </div>
    <!-- payment_card_list end -->

    <script src="{{ asset('js/product_list.js') }}"></script>

    @include('modals.card_select', ['cards' => $cards])
@endsection
