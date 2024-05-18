@extends('layouts.app')

@section('title', '정기 결제 카드 관리')

@section('content')
    <div class="content-right card_detail">
        <h2>E-Cash 사용 관리</h2>
{{--        <button class="btn_excel">--}}
{{--            <img src="{{asset("/images/excel_down.png")}}" alt="엑셀 다운로드">--}}
{{--        </button>--}}

        <div class="box">
            <div class="txt_wrap">
                <p>결제 내역 <span>( {{ number_format($totalCount) }} 건 )</span>이 조회되었습니다. </p>
                <p>총 누적 결제액 <strong>[ {{ number_format($totalAmount) }} ]</strong>원 입니다. </p>
            </div>
            <div class="sort_bar">
                <div class="date">
                    <input type="text" id="start" value=""> ~ <input type="text" id="end" value="">
                </div>
                <div class="search">
                    <input type="text" id="keyword" value="">
                    <button type="button" name="button" id="searchButton"></button>
                </div>
                <select name="perPage" id="perPage">
                    <option value="5">5개</option>
                    <option value="10">10개</option>
                    <option value="15">15개</option>
                    <option value="20">20개</option>
                    <option value="25">25개</option>
                </select>
            </div>
            <div class="">
                <table id="oTable">
                    <thead>
                        <tr>
                            <th>주문일</th>
                            <th>주문 번호</th>
                            <th>사용 항목</th>
                            <th>총 주문 금액</th>
                            <th>E-cash 사용 금액</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- 정기 결제 내역 끝 -->

    <script src="{{ asset('js/order_list.js') }}"></script>

    @include('modals.order_detail')
@endsection
