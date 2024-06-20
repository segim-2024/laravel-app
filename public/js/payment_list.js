$(document).ready(function() {
    const getPaymentStateMessage = (data) => ({
        'UNPAID': '결제 준비',
        'READY': '결제 준비',
        'FAILED': '결제 실패',
        'PAID': '결제 완료',
        'CANCELLED': '결제 취소',
        'PARTIAL_CANCELLED': '부분 취소',
    }[data] || '결제 에러');

    let table = $('#oTable').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        order: [[0, 'desc']],
        pageLength: $('#perPage').val(),
        dom: 'lfrtp',  // 'i'를 제거하여 정보 요약 숨김
        ajax: {
            url: "/payments/list",
            data: function(d) {
                d.periodStart = $('#start').val();
                d.periodEnd = $('#end').val();
                d.keyword = $('#keyword').val();
            }
        },
        columns: [
            { data: 'paid_at', render: function(data, type, row) {
                return data ? new Date(data).toLocaleDateString() : new Date(row.updated_at).toLocaleDateString();
            }},
            { data: 'state', render: function(data, type, row) {
                return getPaymentStateMessage(data);
            }},
            { data: 'payment_id', render: function(data, type, row) {
                return data;
            }},
            { data: 'title' },
            { data: 'method', render: function(data, type, row) {
                return row.card?.name ?? "삭제된 카드";
            }},
            { data: 'amount', render: function(data, type, row) {
                return `${parseInt(data).toLocaleString()}원`;
            }},
            { data: 'receipt_url', orderable:false, render: function(data, type, row) {
                if (!data) return '';

                return `<button class="btn btn_green" data-role="receipt">
                    전표 출력
                </button>`;
            }},
        ],
        language : {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Korean.json"
        },
    });

    $('#oTable').on('click', '[data-role="receipt"]', function() {
        const data = $('#oTable').DataTable().row($(this).parents('tr')).data();
        window.open(data.receipt_url, '_blank');
        return false;
    });

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

    // 검색 버튼 클릭 이벤트 핸들러
    $('#searchButton').click(function() {
        table.ajax.reload();  // DataTables 다시 로드하여 서버로부터 데이터 재요청
    });

    // 페이지당 표시할 항목 수 변경 이벤트 핸들러
    $('#perPage').change(function() {
        table.page.len($(this).val()).draw();
        return false;
    });

    // DatePicker 변경 이벤트 핸들러
    $('.date input').change(function() {
        table.ajax.reload();
        return false;
    });

    // 엔터키 입력시 검색 버튼 클릭
    $('#keyword').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#searchButton').click();
            return false;
        }
    });
});
