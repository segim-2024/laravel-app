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
                        <button class="btn btn_green" data-role="register" data-product-id="${row.id}" data-is-change="true">
                            변경
                        </button>`;
                } else {
                    return `<button class="btn btn_blue" data-role="register" data-product-id="${row.id}" data-is-change="false">
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
        const isChange = $(this).data('is-change') === true;
        
        // 변경 버튼인 경우 중복 체크 없이 바로 모달 표시
        if (isChange) {
            // 카드 존재 여부만 확인
            fetch('/cards/is-exists', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => {
                    if (response.status === 200) {
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
        }
        
        // 신규 등록인 경우 중복 체크 로직 실행
        fetch('/cards/is-exists', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => {
                if (response.status === 200) {
                    // 카드가 존재하면 다른 상품에 등록된 카드가 있는지 확인
                    return fetch('/products/check-card-registered', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    });
                } else {
                    alert("카드를 먼저 등록해주세요.")
                    location.href = "/cards";
                    return null;
                }
            })
            .then(response => {
                if (response === null) return;
                
                if (response.status === 204) {
                    // 등록된 카드가 없음 - 모달 표시
                    document.getElementById('selectCard').style.display = 'block';
                    document.getElementById('selectProductId').value = productId;
                } else if (response.status === 409) {
                    // 이미 다른 상품에 카드가 등록됨 - 알럿만 띄우고 등록 중단
                    alert("이미 다른 상품에 카드가 등록되어 있습니다.\n기존 상품을 해지한 후 등록해주세요.");
                } else {
                    alert("서버에 문제가 있습니다. 잠시 후에 시도해주세요.");
                }
            })
            .catch((error) => {
                alert("서버에 문제가 있습니다. 잠시 후에 시도해주세요.\n현상이 지속되면 고객센터로 문의해주세요.");
            });

        return false;
    });
});
