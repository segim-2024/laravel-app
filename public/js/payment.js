document.addEventListener('DOMContentLoaded', function() {
    async function issueBillingKey() {
        try {
            const issueResponse = await PortOne.requestIssueBillingKey({
                storeId: "store-aa223689-8f3c-48a8-b4d4-c991c06782eb",
                channelKey: $("#channel_key").val(),
                billingKeyMethod: "CARD",
                card: {},
            });

            // 빌링키가 제대로 발급되지 않은 경우 에러 코드가 존재합니다
            if (issueResponse.code != null) {
                return alert(issueResponse.message);
            }

            const response = await fetch('/cards', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    billing_key: issueResponse.billingKey, // 빌링키 전송
                })
            });

            if (!response.ok) throw new Error(`response: ${await response.json()}`);

            window.location.reload();
        } catch (error) {
            console.error('빌링키 발급 실패:', error);
        }
    }

    const registrationButton = document.querySelector('.registration');
    if (registrationButton) {
        registrationButton.addEventListener('click', function() {
            issueBillingKey();
        });
    }
});
