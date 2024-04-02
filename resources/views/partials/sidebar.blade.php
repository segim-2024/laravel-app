<!-- 고정 상태창 시작 -->
<div class="content-left">
    <div class="state">
        <div class="box">
            <div class="logo-wrap">
                <div class="logo">
                    E-Cash
                </div>
            </div>
            <div class="campus-name">
                <span>E-Cash 관리자</span><br />
                죽전 캠퍼스
                {{--{{request()->user()->mb_nick}}--}}
            </div>
            <div class="blue_box use">
                E-cash 월간 정기 결제 이용중
            </div>
            <div class="blue_box">
                <div class="blue_box_con">
                    <div class="l-title">
                        다음청구일
                    </div>
                    <div class="r-con">
                        2024.04.15
                    </div>
                </div>
                <div class="blue_box_con">
                    <div class="l-title">
                        잔여 E-cash
                    </div>
                    <div class="r-con">
                        {{request()->user()->cash->amount}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="nav">
        <div class="box">
            <div class="nav_title">
                E-Cash 관리
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
                    <a href="{{ route('orders.index') }}">E-Cash 사용 관리</a>
                </li>
            </ul>
            <div class="bottom_option">
                {{--<div class="setting">
                    편집
                </div>
                <div class="bar"></div>--}}
                <div class="">
                    <a href="{{ route('auth.logout') }}">로그아웃</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="m_top_nav">
    <div class="title">E-Cash</div>
    <div class="nav_wrap">
        <input type="checkbox" />
        <span class="buger_bar"></span>
        <span class="buger_bar"></span>
        <span class="buger_bar"></span>
        <div class="nav_wrap_box">
            <div class="state">
                <div class="box">
                    <p class="blue_box_title"><span>E-Cash 관리자</span> 죽전 캠퍼스</p>
                    <div class="blue_box use">
                        E-cash 월간 정기 결제 이용중
                    </div>
                    <div class="blue_box">
                        <div class="blue_box_con">
                            <div class="l-title">
                                다음청구일
                            </div>
                            <div class="r-con">
                                2024.04.15
                            </div>
                        </div>
                        <div class="blue_box_con">
                            <div class="l-title">
                                잔여 E-cash
                            </div>
                            <div class="r-con">
                                800,000
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav">
                <div class="nav_title">
                    E-Cash 관리
                </div>
                <ul class="nav-wrap">
                    <li class="{{ request()->is('payments/*') ? 'active' : '' }}">
                        <a href="#">정기 결제 내역</a>
                    </li>
                    <li class="{{ request()->is('cards/*') ? 'active' : '' }}">
                        <a href="#">정기 결제 카드 관리</a>
                    </li>
                    <li class="{{ request()->is('products/*') ? 'active' : '' }}">
                        <a href="{{ route('cards.index') }}">정기 결제 상품 관리</a>
                    </li>
                    <li class="{{ request()->is('e-cashes/*') ? 'active' : '' }}">
                        <a href="#">E-Cash 사용 관리</a>
                    </li>
                </ul>
            </div>
            <div class="bottom_option">
                <div class="setting">편집</div>
                <div class="bar"></div>
                <div class=""><a href="{{ route('auth.logout') }}">로그아웃</a></div>
            </div>
        </div>
    </div>
</div>
<!-- 고정 상태창 끝 -->
