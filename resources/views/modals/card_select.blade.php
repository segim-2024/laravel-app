<!-- 모달창 시작 -->
<div class="modal" id="selectCard" style="display: none">
    <!-- 레이어_결제 카드 설정 시작 -->
    <div class="layer layer_card_set" style="position:absolute; top: 50%; left: 60%; transform: translate(-50%, -50%);">
        <p class="title">결제 카드 설정</p>
        <div class="card_box_wrap">
            @foreach($cards as $card)
            <div class="card_box" data-id="{{$card->id}}">
                <div class="card">
                    <img src="{{asset("images/ex_card.png")}}" alt="">
                </div>
                <p class="card_name">{{$card->name}} ({{$card->number}})</p>
            </div>
            @endforeach
        </div>
        <div class="btn_wrap">
            <button class="btn btn_gray" type="button" name="button" id="cancel">취 소</button>
            <button class="btn btn_green" type="button" name="button" id="okay">확 인</button>
        </div>
    </div>
    <!-- 레이어_결제 카드 설정 끝 -->
</div>
<!-- 모달창 끝 -->

<script src="{{ asset('js/card_select.js') }}"></script>
