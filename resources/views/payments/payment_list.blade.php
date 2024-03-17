@extends('layouts.app')

@section('title', '정기 결제 카드 관리')

@section('content')
    <div class="content-right card_detail">
        <h2>정기 결제 내역</h2>
        <div class="box">
            <div class="txt_wrap">
                <p>결제 내역 <span>( 2 건 )</span>이 조회되었습니다. </p>
                <p>총 누적 결제액 <strong>[ 1,800,000 ]</strong>원 입니다. </p>
            </div>
            <div class="sort_bar">
                <div class="date">
                    <input type="text" name="" value=""> ~ <input type="text" name="" value="">
                </div>
                <div class="search">
                    <input type="text" name="" value="">
                    <button type="button" name="button"></button>
                </div>
                <select name="">
                    <option value="">5개</option>
                    <option value="">10개</option>
                    <option value="">15개</option>
                    <option value="">20개</option>
                    <option value="">25개</option>
                </select>
            </div>
            <div class="">
                <colgroup>

                </colgroup>
                <table>
                    <tr>
                        <th>결제일</th>
                        <th>주문 번호</th>
                        <th>결제 항목</th>
                        <th>결제수단</th>
                        <th>결제 금액</th>
                        <th>매출 전표</th>
                    </tr>
                    <tr>
                        <td>2024-03-15</td>
                        <td>20240315 - 12472226</td>
                        <td>E-cash 월간 정기 결제</td>
                        <td>카드 (국민카드)</td>
                        <td>900,000원</td>
                        <td>
                            <button class="btn btn_blue" data-role="receipt">출력</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2024-02-15</td>
                        <td>20240215 - 32245226</td>
                        <td>E-cash 월간 정기 결제</td>
                        <td>카드 (국민카드)</td>
                        <td>900,000원</td>
                        <td>
                            <button class="btn btn_blue" data-role="receipt">출력</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="paging">
            <a class="arrow prev" href="#">
                <img src="{{asset("/images/paging_arrow.png")}}" alt="이전">
            </a>
            <a class="active" href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a class="arrow next" href="#">
                <img src="{{asset("/images/paging_arrow.png")}}" alt="다음">
            </a>
        </div>
    </div>
    <!-- 정기 결제 내역 끝 -->

    <script src="{{ asset('js/payment_list.js') }}"></script>
@endsection
