<!-- 고정 상태창 시작 -->
<div class="content-left">
    <div class="state">
        <div class="box">
            <div class="logo-wrap">
                <div class="logo">

                </div>
            </div>
            <div class="campus-name">
                <span>정기 결제 등급 관리자</span><br />
                {{request()->user()->mb_nick}}
            </div>
            <div class="blue_box use">
                @if(request()->user()->subscribes()->count() > 0)
                    월간 정기 결제 이용중
                @else
                    월간 정기 결제 대기중
                @endif
            </div>
            <div class="blue_box">
                <div class="blue_box_con">
                    <div class="l-title">
                        다음 청구일
                    </div>
                    <div class="r-con">
                        @php
                            $nextPayment = request()->user()->subscribes()->first();
                            $today = \Illuminate\Support\Carbon::now();
                        @endphp
                        @if (! $nextPayment)
                            카드 등록 후 상품에 설정해주세요.
                        @elseif (! $nextPayment->is_started)
                            {{$today->copy()->addMonthNoOverflow()->day($nextPayment->product->payment_day)->toDateString()}}
                        @elseif ($nextPayment->is_started)
                            @if ($today->day < $nextPayment->product->payment_day)
                                {{$today->copy()->day($nextPayment->product->payment_day)->toDateString()}}
                            @else
                                {{$today->copy()->addMonthNoOverflow()->day($nextPayment->product->payment_day)->toDateString()}}
                            @endif
                        @else
                            카드 등록 후 상품에 등록해주세요.
                        @endif
                    </div>
                </div>
{{--                <div class="blue_box_con">--}}
{{--                    <div class="l-title">--}}
{{--                        잔여 E-cash--}}
{{--                    </div>--}}
{{--                    <div class="r-con">--}}
{{--                        {{number_format(request()->user()->cash->amount)}}--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
    <div class="nav">
        <div class="box">
            <div class="nav_title">
                정기 결제 관리
            </div>
            <ul class="nav-wrap">
                <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <a href="{{ route('payments.index') }}">정기 결제 내역</a>
                </li>
                <li class="{{ request()->routeIs('cards.*') ? 'active' : '' }}">
                    <a href="{{ route('cards.index') }}">정기 결제 카드 관리</a>
                </li>
                <li class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}">정기 결제 상품 관리</a>
                </li>
                <li class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <a href="{{ route('orders.index') }}">교재 주문 내역</a>
                </li>
            </ul>
            <div class="bottom_option">
                {{--<div class="setting">
                    편집
                </div>
                <div class="bar"></div>--}}
                <div class="">
{{--                    <a href="{{ route('auth.logout') }}">로그아웃</a>--}}
                    회사명 : 새김교육(주) | 대표 : 한선덕 | 사업자등록번호 : 809-81-00876 | 경기도 용인시 기흥구 보정로 117 리베로3
                    통신판매업신고 : 제2018-용인기흥-0223호 | E-mail : segimedu@naver.com | 개인정보관리책임자 : 차아경 | 유선 번호 1670-1705
                </div>
            </div>
        </div>
    </div>
</div>

<div class="m_top_nav">
    <div class="title">E-Learning</div>
    <div class="nav_wrap">
        <input type="checkbox" />
        <span class="buger_bar"></span>
        <span class="buger_bar"></span>
        <span class="buger_bar"></span>
        <div class="nav_wrap_box">
            <div class="state">
                <div class="box">
                    <p class="blue_box_title"><span>정기 결제 등급 관리자</span> {{request()->user()->mb_nick}}</p>
                    <div class="blue_box use">
                        @if(request()->user()->subscribes()->count() > 0)
                            월간 정기 결제 이용중
                        @else
                            월간 정기 결제 대기중
                        @endif
                    </div>
                    <div class="blue_box">
                        <div class="blue_box_con">
                            <div class="l-title">
                                다음 청구일
                            </div>
                            <div class="r-con">
                                @php
                                    $nextPayment = request()->user()->subscribes()->first();
                                    $today = \Illuminate\Support\Carbon::now();
                                @endphp
                                @if (! $nextPayment)
                                    카드 등록 후 상품에 설정해주세요.
                                @elseif (! $nextPayment->is_started)
                                    {{$today->copy()->addMonthNoOverflow()->day($nextPayment->product->payment_day)->toDateString()}}
                                @elseif ($nextPayment->is_started)
                                    @if ($today->day < $nextPayment->product->payment_day)
                                        {{$today->copy()->day($nextPayment->product->payment_day)->toDateString()}}
                                    @else
                                        {{$today->copy()->addMonthNoOverflow()->day($nextPayment->product->payment_day)->toDateString()}}
                                    @endif
                                @else
                                    카드 등록 후 상품에 등록해주세요.
                                @endif
                            </div>
                        </div>
{{--                        <div class="blue_box_con">--}}
{{--                            <div class="l-title">--}}
{{--                                잔여 E-cash--}}
{{--                            </div>--}}
{{--                            <div class="r-con">--}}
{{--                                {{number_format(request()->user()->cash->amount)}}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
            <div class="nav">
                <div class="nav_title">
                    정기 결제 관리
                </div>
                <ul class="nav-wrap">
                    <li class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <a href="{{ route('payments.index') }}">정기 결제 내역</a>
                    </li>
                    <li class="{{ request()->routeIs('cards.*') ? 'active' : '' }}">
                        <a href="{{ route('cards.index') }}">정기 결제 카드 관리</a>
                    </li>
                    <li class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}">정기 결제 상품 관리</a>
                    </li>
                    <li class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <a href="{{ route('orders.index') }}">교재 주문 내역</a>
                    </li>
                </ul>
            </div>
            <div class="bottom_option">
                <div class="setting"></div>
                <div class="bar"></div>
                <div class=""><a href="{{ route('auth.logout') }}">로그아웃</a></div>
            </div>
        </div>
    </div>
    <div class="m_top_nav_bg"></div>
</div>
<!-- 고정 상태창 끝 -->
