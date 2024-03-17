document.addEventListener('DOMContentLoaded', function() {
    // 모달을 표시하는 버튼에 대한 이벤트 리스너 설정
    document.querySelectorAll('[data-role="register"]').forEach(function(button) {
        button.addEventListener('click', function() {
            fetch('/cards/is-exists', { // 서버 엔드포인트 URL
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => {
                    if (response.status === 200) {
                        const productId = button.getAttribute('data-product-id');
                        localStorage.setItem('selectedProductId', productId);
                        // 모달의 display 속성을 'block'으로 설정하여 표시
                        document.getElementById('selectCard').style.display = 'block';
                    } else {
                        alert("카드를 먼저 등록해주세요.")
                        location.href = "/cards";
                    }
                })
                .catch((error) => {
                    alert("서버에 문제가 있습니다. 잠시 후에 시도해주세요.\n현상이 지속되면 고객센터로 문의해주세요.");
                });
        });
    });
});
