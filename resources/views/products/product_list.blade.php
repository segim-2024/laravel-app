@extends('layouts.app')

@section('title', '정기 결제 상품 관리')

@section('content')
    <!-- 정기 결제 상품 관리 시작 -->
    <div class="content-right card_list">
        <h2>정기 결제 상품 관리</h2>
        <div class="box">
            <p class="">결제 수단 등록 현황 [{{$products->count()}}]</p>

            <div class="">
                <table id="productTable">
                    <colgroup>
                        <col style="width: 18%;">
                        <col style="width: 14%;">
                        <col>
                        <col style="width: 18%;">
                        <col style="width: 8%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>정기 결제 상품</th>
                            <th>결제금</th>
                            <th>결제 카드</th>
                            <th>카드 등록일</th>
                            <th>결제일</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
{{--        <div class="paging">--}}
{{--            <a class="arrow prev" href="#">--}}
{{--                <img src="{{asset('/images/paging_arrow.png')}}" alt="이전">--}}
{{--            </a>--}}
{{--            <a class="active" href="#">1</a>--}}
{{--            <a href="#">2</a>--}}
{{--            <a href="#">3</a>--}}
{{--            <a class="arrow next" href="#">--}}
{{--                <img src="{{asset('/images/paging_arrow.png')}}" alt="다음">--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>
    <!-- payment_card_list end -->

    <script src="{{ asset('js/product_list.js') }}"></script>


    @include('modals.card_select', ['cards' => $cards])
@endsection
