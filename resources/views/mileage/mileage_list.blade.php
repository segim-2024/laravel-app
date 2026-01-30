@extends('layouts.app')

@section('title', '나의 마일리지')

@section('body-class', 'mileage-page')

@section('content')
    <div class="content-right mileage">
        <h2>나의 마일리지</h2>
        <div class="state">
            <div class="box">
                <div class="state_mileage">
                    <div class="mileage_area">
                        <p class="title">보유마일리지</p>
                        <p class="num">{{ number_format($currentBalance) }}</p>
                    </div>
                </div>
                <div class="gray_box">
                    <div class="gray_box_area">
                        <div class="title">총 누적 적립</div>
                        <div class="num">{{ number_format($totalAccrued) }}</div>
                    </div>
                    <div class="gray_box_area">
                        <div class="title">총 누적 전환</div>
                        <div class="num">{{ number_format($totalConverted) }}</div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="state_mileage">
                    <div class="mileage_area">
                        <p class="title">포인트 전환 가능 마일리지</p>
                        <p class="num">{{ number_format($convertibleAmount) }}</p>
                    </div>

                    <div class="gray_box_bottom">
                        <div class="title">총 보유 포인트</div>
                        <div class="num">{{ number_format($totalPoints) }}</div>
                    </div>
                </div>
                <button type="button" onclick="openConvertPopup()">포인트 전환하기</button>
            </div>
        </div>
        <div class="list_wrap">
            <div class="list_header">
                <div class="section_header">
                    <h2>마일리지 상세내역</h2>
                    <div class="btn_wrap">
                        <button class="on" type="button" data-filter="all">종합</button>
                        <button type="button" data-filter="save">적립</button>
                        <button type="button" data-filter="use">사용 · 전환</button>
                    </div>
                </div>
                <div class="sort_bar">
                    <div class="date">
                        <input type="text" id="start" value="" placeholder="시작일"> ~ <input type="text" id="end" value="" placeholder="종료일">
                    </div>
                    <div class="search">
                        <input type="text" id="keyword" value="" placeholder="검색어">
                        <button type="button" name="button" id="searchButton"></button>
                    </div>
                    <select name="perPage" id="perPage">
                        <option value="5">5개</option>
                        <option value="10" selected>10개</option>
                        <option value="15">15개</option>
                        <option value="20">20개</option>
                        <option value="25">25개</option>
                    </select>
                </div>
            </div>
            <table id="mileageTable" class="mileage-table">
                <thead>
                    <tr>
                        <th>날짜정렬용</th>
                        <th>유형</th>
                        <th>내역</th>
                        <th>금액</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 마일리지 포인트 전환 팝업 -->
    <div class="modal" id="convertModal" style="display: none;" data-convertible="{{ $convertibleAmount }}">
        <div class="gray_popup" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%)">
            <button class="close" onclick="closeConvertPopup()"><img src="{{ asset('images/icn_close_white.png') }}" alt="닫기 아이콘"></button>
            <div class="head">마일리지 포인트 전환</div>
            <div class="content">
                <div class="state_wrap">
                    <div class="total">
                        <p>보유 마일리지</p><input type="text" value="{{ number_format($currentBalance) }}" readonly>
                    </div>
                    <div class="convertible">
                        <p>전환 가능 마일리지</p><input type="text" value="{{ number_format($convertibleAmount) }}" readonly>
                    </div>
                </div>
                <div class="price_wrap">
                    <p>전환액</p>
                    <div class="area">
                        <div id="convertAmount">0</div>
                        <button type="button" onclick="setFullAmount()">전액</button>
                    </div>
                </div>
                <button class="btn_blue" type="button" onclick="doConvert()">전환하기</button>
                <div class="title">포인트 현황</div>
                <div class="gray_box">
                    <p>보유 포인트</p>
                    <div class="price">{{ number_format($totalPoints) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAMUS 마일리지 멤버스 이용안내 팝업 -->
    <div class="modal" id="guideModal" style="display: none;">
        <div class="guide_popup mileage_ver" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%)">
            <div class="close" onclick="closeGuidePopup()"><img src="{{ asset('images/icn_close.png') }}" alt="닫기 아이콘"></div>
            <div class="title">
                PAMUS 마일리지 멤버스 이용안내
            </div>
            <p>본 안내사항은 파머스 마일리지 멤버스 회원에게 적용되며,<br>
                아래 사항에 동의하셔야 멤버스 서비스를 이용 하실 수 있습니다.</p>
            <div class="box_wrap">
                <div class="box">
                    <dl>
                        <dt>1. 계약 안내사항</dt>
                        <dd>
                            <strong>1. 회원 선정</strong><br>
                            마일리지 적립 대상 학원(이하 "멤버스 학원")은 본사 및 지역본부의 내부 심사 절차를 거쳐 매년 선정하며 선정 기준 및 절차는 내부 정책에 따라 변경될 수 있습니다.<br><br>

                            <strong>2. 회원 자격 유지</strong><br>
                            금년도에 멤버스로 선정되었다 하더라도 차년도 기준 요건을 충족하지 못할 경우 멤버스 자격이 해지될 수 있습니다.<br><br>

                            <strong>3. 가맹 해지 및 환불 불가</strong><br>
                            금년도에 멤버스로 선정되었다 하더라도 차년도 기준 요건을 충족하지 못할 경우 멤버스 자격이 해지될 수 있습니다.
                        </dd>
                    </dl>
                </div>
                <div class="box">
                    <dl>
                        <dt>2. 적립 · 전환 · 사용 관련 안내사항</dt>
                        <dd>
                            <strong>1. 마일리지 적립 기준</strong><br>
                            교재 구매 금액의 5%를 마일리지 형태로 적립합니다.<br>
                            단, 마케팅 물품·이벤트 상품·추가 워크북 상품 등은 적립 대상에서 제외됩니다.<br><br>

                            <strong>2. 적립 제외 조건</strong><br>
                            포인트를 사용하여 결제한 교재 금액은 마일리지 적립 대상에서 제외됩니다.<br><br>

                            <strong>3. 포인트 전환 조건</strong><br>
                            본사가 정한 기준 적립 한도를 초과한 금액에 대해서는 직접 전환 절차를 통해 쇼핑몰 내 사용 <br><br>
                        </dd>
                    </dl>
                </div>
            </div>
            <p>위 마일리지 멤버스 안내 내용을 모두 숙지하고 이해하였으며 이에 동의합니다.</p>
            <button type="button" name="button" onclick="closeGuidePopup()">동의 후 마일리지 멤버스 이용</button>
        </div>
    </div>

    <script src="{{ asset('js/mileage_list.js') }}?v={{ time() }}"></script>
@endsection