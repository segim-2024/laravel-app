document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.card_box').forEach(function(cardBox) {
        cardBox.addEventListener('click', function() {
            const cardId = this.getAttribute('data-id');

            // name=card 중 cardId value가 동일한 checkbox 박스에 checked
            document.querySelectorAll('input[name=card]').forEach(function(card) {
                card.checked = card.value === cardId;
            });
        });
    });

    document.querySelector('#cancel').addEventListener('click', function() {
        document.querySelector('#selectCard').style.display = 'none';
    });

    document.querySelector('#okay').addEventListener('click', function() {
        const selectedProductId = document.querySelector('#selectProductId').value;
        const selectedCardId = document.querySelector('input[name=card]:checked').value;
        if (! selectedProductId) {
            alert('상품을 다시 선택해주세요.');
            return;
        }

        if (! selectedCardId) {
            alert('카드를 선택해주세요.');
            return;
        }

        fetch('/products/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    product_id: selectedProductId, // 선택된 카드 ID 전송
                    card_id: selectedCardId, // 선택된 카드 ID 전송
                })
            })
            .then(response => response.json())
            .then(data => {
                alert("카드 등록 완료");
                console.log('Success:', data);
                window.location.reload();
            })
            .catch((error) => {
                console.error('Error:', error);
                // 에러 처리 로직 (예: 에러 메시지 표시)
            });
    });
});
