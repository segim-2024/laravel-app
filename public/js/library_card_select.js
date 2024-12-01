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

    document.querySelector('#okay').addEventListener('click', async function() {
        const selectedProductId = document.querySelector('#selectProductId').value;
        const selectedCardId = document.querySelector('input[name=card]:checked')?.value ?? null;
        const paymentDay = document.querySelector('#paymentDay').value;
        if (! selectedProductId) {
            alert('상품을 다시 선택해주세요.');
            return;
        }

        if (! selectedCardId) {
            alert('카드를 선택해주세요.');
            return;
        }

        if (! paymentDay) {
            alert('결제일을 선택해주세요.');
            return
        }

        try {
            await fetchLibraryCard(selectedProductId, selectedCardId, paymentDay);
        } catch (error) {
            console.log(error);
            alert(error.message);
        }
    });

    async function fetchLibraryCard(selectedProductId, selectedCardId, paymentDay) {
        const response = await fetch(`/library-products/${selectedProductId}/subscribe`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                card_id: selectedCardId, // 선택된 카드 ID 전송
                payment_day: paymentDay, // 선택된 결제일 전송
            })
        })

        const data = await response.json();
        if (! response.ok) {
            throw new Error(data.message);
        }

        alert("구독이 완료되었습니다.");
        location.reload();
    }
});
