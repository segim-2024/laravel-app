$(document).ready(function() {
    let table = $('#oTable').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        order: [[0, 'desc']],
        pageLength: $('#perPage').val(),
        dom: 'lfrtp',  // 'i'를 제거하여 정보 요약 숨김
        ajax: {
            url: "/orders/list",
            data: function(d) {
                d.periodStart = $('#start').val();
                d.periodEnd = $('#end').val();
                d.keyword = $('#keyword').val();
            }
        },
        columns: [
            { data: 'od_time', render: function(data, type, row) {
                    return data ? new Date(data).toLocaleDateString() : '';
                }},
            {
                data: 'od_id',
                render: function(data, type, row) {
                    return data;
                },
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).addClass('num').attr('data-role', 'detail');
                }
            },
            { data: 'carts', render: function(data, type, row) {
                let productName = data[0].it_name;
                if (data.length > 1) {
                    productName += ` 외 ${data.length - 1}건`;
                }

                return productName;
            }},
            { data: 'od_cart_price', render: function(data, type, row) {
                return `${data.toLocaleString('ko-KR')}원`;
            }},
            { data: 'od_receipt_ecash', render: function(data, type, row) {
                return data.toLocaleString('ko-KR');
            }},
        ],
        language : {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Korean.json"
        },
    });

    // 모달을 표시하는 버튼에 대한 이벤트 리스너 설정
    // 이벤트 위임을 사용하여 동적으로 생성된 요소에 이벤트 바인딩
    $('#oTable tbody').on('click', '[data-role="detail"]', function() {
        const order = table.row($(this).closest('tr')).data();
        updateModalContent(order);
        $('#detail').show();
    });

    // 모달 내용을 업데이트하는 함수
    function updateModalContent(order) {
        const itemsHtml = order.carts.map(function(cart) {
            let total = cart.io_type ? cart.io_price * cart.ct_qty : (cart.ct_price + cart.io_price) * cart.ct_qty;
            let sendType = cart.ct_send_cost === 1 ? "착불" : (cart.ct_send_cost === 2 ? "무료" : "선불");

            return `
                <tr>
                    <td>${cart.it_name}</td>
                    <td>${cart.ct_qty}</td>
                    <td>${cart.ct_price.toLocaleString()}</td>
                    <td>
                        ${total.toLocaleString()}
                    </td>
                    <td>
                        ${sendType}
                    </td>
                    <td>${cart.ct_status}</td>
                </tr>
            `
        }).join('');

        let total = order.od_cart_price + order.od_send_cost + order.od_send_cost2;
        let receiptTotal = order.od_receipt_ecash + order.od_receipt_point + order.od_receipt_price;

        $('#detail .content_wrap #detail_od_id').html(order.od_id);
        $('#detail .content_wrap #cart_list').html(itemsHtml);
        $('#detail .content_wrap #detail_od_cart_price').html(`${total.toLocaleString()}원`);
        $('#detail .content_wrap #detail_od_cancel_price').html(`${order.od_cancel_price.toLocaleString()}원`);
        $('#detail .content_wrap #detail_od_receipt_ecash').html(`${order.od_receipt_ecash.toLocaleString()}원`);
        $('#detail .content_wrap #detail_od_receipt_total').html(`${receiptTotal.toLocaleString()}원`);
    }

    // 모달 내 '취소' 버튼에 대한 이벤트 리스너 설정
    document.querySelector('.modal .close').addEventListener('click', function() {
        // 모달의 display 속성을 'none'으로 설정하여 숨김
        document.getElementById('detail').style.display = 'none';
    });


    // 검색 버튼 클릭 이벤트 핸들러
    $('#searchButton').click(function() {
        table.ajax.reload();  // DataTables 다시 로드하여 서버로부터 데이터 재요청
    });

    // 페이지당 표시할 항목 수 변경 이벤트 핸들러
    $('#perPage').change(function() {
        table.page.len($(this).val()).draw();
        return false;
    });

    // 엔터키 입력시 검색 버튼 클릭
    $('#keyword').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#searchButton').click();
            return false;
        }
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


    // DatePicker 변경 이벤트 핸들러
    $('.date input').change(function() {
        table.ajax.reload();
        return false;
    });
});
