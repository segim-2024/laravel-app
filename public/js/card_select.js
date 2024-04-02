document.addEventListener('DOMContentLoaded', function() {
    // let selectedProductId = localStorage.getItem('selectedProductId');
    let selectedProductId = 1;
    let selectedCardId = null; // 선택된 카드 ID 저장
    document.querySelectorAll('.card_box').forEach(function(cardBox) {
        cardBox.addEventListener('click', function() {
            // 모든 카드 박스의 선택 효과를 제거
            document.querySelectorAll('.card_box').forEach(function(box) {
                box.classList.remove('selected'); // 'selected' 클래스 제거
            });

            // 현재 클릭된 카드 박스에 선택 효과 추가
            cardBox.classList.add('selected'); // 'selected' 클래스 추가
            selectedCardId = cardBox.getAttribute('data-id'); // 선택된 카드 ID 저장
            console.log(selectedCardId);
        });
    });

    // 모달 내 '취소' 버튼에 대한 이벤트 리스너 설정
    document.querySelector('#cancel').addEventListener('click', function() {
        // 모달의 display 속성을 'none'으로 설정하여 숨김
        document.getElementById('selectCard').style.display = 'none';
    });

    document.querySelector('#okay').addEventListener('click', function() {
        if (selectedCardId) {
            fetch('/products/subscribe', { // 서버 엔드포인트 URL
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
        } else {
            alert('카드를 선택해주세요.'); // 카드가 선택되지 않았을 경우 경고
        }
    });
});
