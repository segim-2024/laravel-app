$(document).ready(function() {
    $('#productTable').DataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        ajax: "/products/list",
        dom: 'lfrtp',  // 'i'를 제거하여 정보 요약 숨김
        columns: [
            { data: 'name' },
            { data: 'price', render: function(data, type, row) {
                return `${parseInt(data).toLocaleString()}원`;
            }},
            { data: null, render: function(data, type, row) {
                if (row.subscribe) {
                    return `${row.subscribe.card.name} (${row.subscribe.card.number})
                        <button class="btn btn_green" data-role="register" data-product-id="${row.id}">
                            변경
                        </button>`;
                } else {
                    return `<button class="btn btn_blue" data-role="register" data-product-id="${row.id}">
                            결제 카드 설정
                        </button>`;
                }
            }},
            { data: 'subscribe.created_at', render: function(data, type, row) {
                return data ? new Date(data).toLocaleString() : '';
            }},
            { data: 'payment_day', render: function(data, type, row) {
                return `${data}일`;
            }}
        ],
        language : {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Korean.json"
        },
    });

    $('#productTable').on('click', '[data-role=register]', function() {
        const productId = $(this).data('product-id');
        fetch('/cards/is-exists', { // 서버 엔드포인트 URL
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => {
                if (response.status === 200) {
                    // 모달의 display 속성을 'block'으로 설정하여 표시
                    document.getElementById('selectCard').style.display = 'block';
                    document.getElementById('selectProductId').value = productId;
                } else {
                    alert("카드를 먼저 등록해주세요.")
                    location.href = "/cards";
                }
            })
            .catch((error) => {
                alert("서버에 문제가 있습니다. 잠시 후에 시도해주세요.\n현상이 지속되면 고객센터로 문의해주세요.");
            });

        return false;
    });
});
