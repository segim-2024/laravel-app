document.addEventListener('DOMContentLoaded', function() {
    // ------ 클라이언트 키로 객체 초기화 ------
    const clientKey = 'test_ck_D5GePWvyJnrK0W0k6q8gLzN97Eoq'
    const tossPayments = TossPayments(clientKey)

    const registrationButton = document.querySelector('.registration');
    if (registrationButton) {
        registrationButton.addEventListener('click', function() {
            // Toss Payments 모듈 실행 로직
            console.log('Toss Payments 모듈 실행');
            // Toss Payments 결제 시작 로직
            tossPayments
                .requestBillingAuth('카드', {
                    // 결제수단 파라미터 (자동결제는 카드만 지원합니다.)
                    // 결제 정보 파라미터
                    customerKey: '4dttNH8hgNVHHQT0spyYY', // 구매자 ID로 상점에서 만들어야 합니다. 빌링키와 매핑됩니다. 자세한 파라미터 설명은 파라미터 설명을 참고하세요: https://docs.tosspayments.com/reference/js-sdk#결제-정보-5
                    successUrl: window.location.protocol+ "//" + window.location.host + '/cards/created', // 카드 등록에 성공하면 이동하는 페이지(직접 만들어주세요)
                    failUrl: window.location.protocol + "//" + window.location.host +  '/cards/failed', // 카드 등록에 실패하면 이동하는 페이지(직접 만들어주세요)
                })
                .catch(function (error) {
                    if (error.code === 'USER_CANCEL') {
                        // 결제 고객이 결제창을 닫았을 때 에러 처리
                    }
                })
        });
    }
});
