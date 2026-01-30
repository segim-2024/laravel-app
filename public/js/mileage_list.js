$(document).ready(function() {
    // 날짜 선택기 설정
    $("#start, #end").datepicker({
        dateFormat: 'yy-mm-dd',
        showMonthAfterYear: true,
        yearSuffix: "년 ",
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        dayNamesMin: ['일','월','화','수','목','금','토'],
        dayNames: ['일요일','월요일','화요일','수요일','목요일','금요일','토요일']
    });

    let currentFilter = 'all';

    let table = $('#mileageTable').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        order: [[0, 'desc']],
        pageLength: parseInt($('#perPage').val()),
        dom: 'lfrtp',
        ajax: {
            url: "/mileage/list",
            data: function(d) {
                d.periodStart = $('#start').val();
                d.periodEnd = $('#end').val();
                d.keyword = $('#keyword').val();
                d.filter = currentFilter;
            }
        },
        columns: [
            { data: 'created_at', visible: false },
            { data: 'action_icon', orderable: false, render: function(data, type, row) {
                return `<div class="area">
                    <div class="icon"><img src="/images/${data}" alt="${row.action_label}"></div>
                    <div class="type">${row.action_label}</div>
                </div>`;
            }},
            { data: 'formatted_date', orderable: false, render: function(data, type, row) {
                return `<div class="area">
                    <div class="date">${data}</div>
                    <div class="desc">${row.description}</div>
                </div>`;
            }},
            { data: 'formatted_amount', orderable: false, render: function(data, type, row) {
                return `<div class="amount ${row.action_class}">${data}</div>`;
            }},
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Korean.json",
            "emptyTable": "마일리지 내역이 없습니다."
        }
    });

    // 필터 버튼 클릭 이벤트
    $('.section_header .btn_wrap button').on('click', function() {
        $('.section_header .btn_wrap button').removeClass('on');
        $(this).addClass('on');
        currentFilter = $(this).data('filter');
        table.ajax.reload();
    });

    // 검색 버튼 클릭
    $('#searchButton').click(function() {
        table.ajax.reload();
    });

    // 페이지당 표시 개수 변경
    $('#perPage').change(function() {
        table.page.len($(this).val()).draw();
    });

    // 날짜 변경시 검색
    $('#start, #end').change(function() {
        table.ajax.reload();
    });

    // 엔터키로 검색
    $('#keyword').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#searchButton').click();
            return false;
        }
    });

    // 전환액 입력 시 숫자만 허용하고 천단위 콤마 적용
    $(document).on('input', '#convertAmount', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value) {
            $(this).val(parseInt(value).toLocaleString());
        } else {
            $(this).val('');
        }
    });
});

// 포인트 전환 팝업 열기
function openConvertPopup() {
    $('#convertAmount').val('0');
    $('#convertModal').show();

    $.get('/mileage/status', function(data) {
        $('#convertModal').data('convertible', data.convertibleAmount);
        $('#convertModal .total input').val(data.currentBalance.toLocaleString());
        $('#convertModal .convertible input').val(data.convertibleAmount.toLocaleString());
        $('#convertModal .gray_box .price').text(data.totalPoints.toLocaleString());
    });
}

// 포인트 전환 팝업 닫기
function closeConvertPopup() {
    $('#convertModal').hide();
}

// 전액 버튼 클릭
function setFullAmount() {
    const convertible = $('#convertModal').data('convertible') || 0;
    $('#convertAmount').val(convertible.toLocaleString());
}

// 이용안내 팝업 열기
function openGuidePopup() {
    $('#guideModal').show();
}

// 이용안내 팝업 닫기
function closeGuidePopup() {
    $('#guideModal').hide();
}

// 모달 배경 클릭시 닫기
$(document).on('click', '.modal', function(e) {
    if (e.target === this) {
        $(this).hide();
    }
});

// 포인트 전환 실행
function doConvert() {
    const convertAmountText = $('#convertAmount').val().replace(/,/g, '');
    const convertAmount = parseInt(convertAmountText) || 0;

    if (convertAmount <= 0) {
        alert('전환할 금액을 입력해주세요.');
        return;
    }

    if (!confirm(convertAmount.toLocaleString() + '원을 포인트로 전환하시겠습니까?')) {
        return;
    }

    $.ajax({
        url: '/mileage/convert',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            amount: convertAmount
        },
        beforeSend: function() {
            $('.btn_blue').prop('disabled', true).text('전환 중...');
        },
        success: function(response) {
            alert(response.message);
            closeConvertPopup();
            location.reload();
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || xhr.responseJSON?.message || '전환 중 오류가 발생했습니다.';
            alert(error);
        },
        complete: function() {
            $('.btn_blue').prop('disabled', false).text('전환하기');
        }
    });
}