document.addEventListener('DOMContentLoaded', function() {
    $.datepicker.setDefaults({
        lang:'ko',
        dateFormat: 'yy-mm-dd',
        showMonthAfterYear:true,
        yearSuffix: "년 ",
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], //달력의 월 부분 텍스트
        monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'], //달력의 월 부분 Tooltip
        dayNamesMin: ['일','월','화','수','목','금','토'], //달력의 요일 텍스트
        dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토요일'] //달력의 요일 Tooltip
    });

    // input을 datepicker로 선언
    $(".date input").datepicker();
    $(".date input:last-child").datepicker();

    // 모달을 표시하는 버튼에 대한 이벤트 리스너 설정
    document.querySelectorAll('[data-role="detail"]').forEach(function(button) {
        button.addEventListener('click', function() {
            document.getElementById('detail').style.display = 'block';
        });
    });

    // 모달 내 '취소' 버튼에 대한 이벤트 리스너 설정
    document.querySelector('.modal .close').addEventListener('click', function() {
        // 모달의 display 속성을 'none'으로 설정하여 숨김
        document.getElementById('detail').style.display = 'none';
    });
});
