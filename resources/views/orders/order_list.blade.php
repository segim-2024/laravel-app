@extends('layouts.app')

@section('title', '정기 결제 카드 관리')

@section('content')
    <div class="content-right card_detail">
        <h2>E-Cash 사용 관리</h2>
        <button class="btn_excel">
            <img src="{{asset("/images/excel_down.png")}}" alt="엑셀 다운로드">
        </button>

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
                        <th>사용 항목</th>
                        <th>총 주문 금액</th>
                        <th>E-cash 사용 금액</th>
                    </tr>
                    <tr>
                        <td>2024-03-15</td>
                        <td class="num" data-role="detail">20240315 - 12472226</td>
                        <td>Tooth phonics 2 외 3건</td>
                        <td>1,211,000</td>
                        <td>800,000</td>
                    </tr>
                    <tr>
                        <td>2024-02-15</td>
                        <td class="num" data-role="detail">20240215 - 32245226</td>
                        <td>Pre stella A1 외 3건</td>
                        <td>100,000</td>
                        <td>100,000</td>
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

    <script src="{{ asset('js/order_list.js') }}"></script>

    @include('modals.order_detail')
@endsection
