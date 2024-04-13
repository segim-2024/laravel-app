@extends('layouts.app')

@section('title', '정기 결제 카드 관리')

@section('content')
    <div class="content-right card_detail">
        <h2>정기 결제 내역</h2>
        <div class="box">
            <div class="txt_wrap">
                <p>결제 내역 <span>( {{$totalCount}} 건 )</span>이 조회되었습니다. </p>
                <p>총 누적 결제액 <strong>[ {{number_format($totalAmount)}} ]</strong>원 입니다. </p>
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
                            <th>결제일</th>
                            <th>주문 번호</th>
                            <th>결제 항목</th>
                            <th>결제수단</th>
                            <th>결제 금액</th>
                            <th>매출 전표</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- 정기 결제 내역 끝 -->

    <script src="{{ asset('js/payment_list.js') }}"></script>
@endsection
