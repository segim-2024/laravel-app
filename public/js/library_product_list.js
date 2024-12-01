$(document).ready(function() {
    // 구독 버튼 클릭 시 이벤트 핸들러
    $('[name=register]').on('click', async function() {
        const productId = $(this).data('id');

        if (! await checkCanSubscribe()) {
            alert("이미 구독중인 상품이 있습니다.");
            return false
        }

        try {
            const response = await fetch('/cards/is-exists', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            if (! response.ok) {
                alert("카드를 먼저 등록해주세요.");
                location.href = "/cards";
            }

            document.getElementById('selectCard').style.display = 'block';
            document.getElementById('selectProductId').value = productId;
        } catch (error) {
            console.error('Error:', error);
            alert("서버에 문제가 있습니다. 잠시 후에 시도해주세요.\n현상이 지속되면 고객센터로 문의해주세요.");
        }

        return false;
    });

    // 카드 해지 버튼 클릭 시 이벤트 핸들러
    $('[name=canceled]').on('click', async function() {
        try {
            const productId = $(this).data('id');
            const response = await fetch(`/library-products/${productId}/unsubscribe`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PATCH',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });

            const data = await response.json();
            if (! response.ok) {
                alert(data.message);
                return false;
            }

            alert("구독이 해지되었습니다.");
            location.reload();
        } catch (error) {
            alert('처리 중 오류가 발생했습니다.');
            console.error(error);
        }

        return false;
    });

    // 카드 변경 버튼 클릭 시 모달 띄우기
    $('[name=change]').on('click', async function() {
        document.getElementById('changeCard').style.display = 'block';
        document.getElementById('changeCardProductId').value = $(this).data('id');
        return false;
    });

    // 구독 가능 여부를 반환하는 함수
    async function checkCanSubscribe() {
        const response = await fetch('/library-products/check-can-subscribe', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        });

        return response.ok;
    }
});
