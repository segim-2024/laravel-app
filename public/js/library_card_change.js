document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.card_box[name=changeCardList]').forEach(function(cardBox) {
        cardBox.addEventListener('click', function() {
            const cardId = this.getAttribute('data-id');

            // name=card 중 cardId value가 동일한 checkbox 박스에 checked
            document.querySelectorAll('input[name=changeCard]').forEach(function(card) {
                card.checked = card.value === cardId;
            });
        });
    });

    document.querySelector('#changeCardCancel').addEventListener('click', function() {
        document.querySelector('#changeCard').style.display = 'none';
    });

    document.querySelector('#changeCardOkay').addEventListener('click', async function() {
        const selectedProductId = document.querySelector('#changeCardProductId').value;
        const selectedCardId = document.querySelector('input[name=changeCard]:checked')?.value ?? null;
        if (! selectedProductId) {
            alert('상품을 다시 선택해주세요.');
            return;
        }

        if (! selectedCardId) {
            alert('카드를 선택해주세요.');
            return;
        }

        try {
            const response = await fetch(`/library-products/${selectedProductId}/card`, {
                method: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'PATCH',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    card_id: selectedCardId,
                }),
            });

            const data = await response.json();
            if (! response.ok) {
                alert(data.message);
                return false;
            }

            alert("상품에 연결된 카드가 변경되었습니다.");
            location.reload();
        } catch (error) {
            console.error('Error:', error);
            alert("서버에 문제가 있습니다. 잠시 후에 시도해주세요.\n현상이 지속되면 고객센터로 문의해주세요.");
        }

        return false;
    });
});
