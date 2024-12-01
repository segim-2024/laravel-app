<!-- 모달창 시작 -->
<div class="modal" id="changeCard" style="display: none">
    <input type="hidden" id="changeCardProductId" name="changeCardProductId" value="">

    <!-- 레이어_결제 카드 설정 시작 -->
    <div class="layer layer_card_set" style="display: block; position:absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <p class="title">결제 카드 설정</p>
        <div class="card_box_wrap">
            @foreach($cards as $card)
            <div class="card_box_pick">
                <div class="card_box" name="changeCardList" data-id="{{$card->id}}">
                    <div class="card">
                        <img src="{{asset("images/ex_card.png")}}" alt="">
                    </div>
                    <p class="card_name">{{$card->name}} ({{$card->number}})</p>
                </div>
                <div class="card_radio">
                    <label>
                        <input class="pick" type="radio" name="changeCard" value="{{$card->id}}">
                        <div></div>
                    </label>
                </div>
            </div>
            @endforeach
        </div>

        <div class="btn_wrap">
            <button class="btn btn_gray" type="button" name="button" id="changeCardCancel">취 소</button>
            <button class="btn btn_green" type="button" name="button" id="changeCardOkay">확 인</button>
        </div>
    </div>
    <!-- 레이어_결제 카드 설정 끝 -->
</div>
<!-- 모달창 끝 -->

<script src="{{ asset('js/library_card_change.js') }}"></script>
