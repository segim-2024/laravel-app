<!-- 모달창 시작 -->
<div class="modal" id="detail" style="display: none">
    <!-- 주문 상세 내역 -->
    <div class="layer_order_detail" style="position:absolute; top: 50%; left: 50%; transform:translate(-50%, -50%)">
        <div class="title">
            주문 상세 내역
        </div>
        <div class="close">
            <img src="{{asset("/images/icn_layer_close.png")}}" alt="닫기">
        </div>
        <div class="content_wrap">
            <p>주문번호 <span>20240315 - 12472226</span></p>
            <div class="layer_table">
                <table>
                    <thead>
                    <tr>
                        <th colspan="6">상 품 명</th>
                    </tr>
                    <tr>
                        <th>옵션명</th>
                        <th>수량</th>
                        <th>판매가</th>
                        <th>소계</th>
                        <th>배송비</th>
                        <th>상태</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>CoCo Phonics 1</td>
                        <td>2</td>
                        <td>22,000</td>
                        <td>44,000</td>
                        <td>선불</td>
                        <td>입금</td>
                    </tr>
                    <tr>
                        <td>CoCo Phonics 2</td>
                        <td>3</td>
                        <td>22,000</td>
                        <td>66,000</td>
                        <td>선불</td>
                        <td>입금</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="gray_box">
                <div class="history">
                    <div class="history_title">주문</div>
                    <div class="history_con">주문이 접수되었습니다.</div>
                </div>
                <div class="history">
                    <div class="history_title">입금</div>
                    <div class="history_con">입금(결제)이 완료되었습니다.</div>
                </div>
                <div class="history">
                    <div class="history_title">준비</div>
                    <div class="history_con">상품 준비 중입니다.</div>
                </div>
                <div class="history">
                    <div class="history_title">배송</div>
                    <div class="history_con">상품 배송 중입니다.</div>
                </div>
                <div class="history">
                    <div class="history_title">완료</div>
                    <div class="history_con">상품 배송이완료 되었습니다.</div>
                </div>
            </div>
            <div class="price_box_wrap">
                <div class="price_box">
                    <div class="wh_box_wrap">
                        <div class="price_txt">
                            <div class="price_title">주문 총액</div>
                            <div class="price">110,000원</div>
                        </div>
                        <div class="price_txt">
                            <div class="price_title">취소 금액</div>
                            <div class="price">0원</div>
                        </div>
                        <div class="price_txt">
                            <div class="price_title">E-Cash</div>
                            <div class="price">0원</div>
                        </div>
                    </div>
                    <div class="wh_box_wrap blue_bg">
                        <div class="price_txt">
                            <div class="price_title">총 구매액</div>
                            <div class="price">110,000원</div>
                        </div>
                        <div class="price_txt">
                            <div class="price_title">결 제 액</div>
                            <div class="price">110,000원</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /주문 상세 내역 -->

</div>
<!-- 모달창 끝 -->
