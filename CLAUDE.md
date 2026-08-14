# SG-APP

고래영어와 파머스영어(새김)의 레거시 PHP(그누보드) 시스템을 통합 관리하기 위한 Laravel 백엔드 애플리케이션.

## 기술 스택

- **Backend**: Laravel 10.10 (PHP 8.1+)
- **Auth**: Laravel Sanctum (토큰 기반 API 인증)
- **Frontend**: Blade 템플릿
- **Payment**: PortOne(포트원) V2 - Toss는 더이상 사용하지 않음
- **Storage**: AWS S3
- **Test**: PHPUnit 10.1

## 데이터베이스

2개의 MySQL 데이터베이스가 연결되어 있음:

| Connection | 용도 | 모델 |
|------------|------|------|
| `mysql` (기본) | 새김(SEGIM) 시스템 | `Member`, `MemberCash`, `MemberCard` 등 |
| `mysql_whale` | 고래영어 시스템 | `WhaleMember`, `WhaleMemberCash`, `WhaleCart` 등 |

> `mysql_sg`는 앱 자체 데이터(토큰 등) 저장용

## SSO 인증

파머스영어(새김) 홈페이지에서 SSO를 통해 이 앱의 구독 관리 페이지로 이동하는 구조.

### 현재 SSO 흐름 (파머스영어만 지원)

```
[파머스영어 홈페이지] --SSO--> /sso-auth?data={암호화된데이터}
                                    ↓
                         SSOController::handleSSO()
                                    ↓
                         Member 모델로 인증 (mysql)
                                    ↓
                         /cards 또는 /library-products 로 리다이렉트
```

- **엔드포인트**: `GET /sso-auth`
- **파라미터**: `data` (base64 인코딩 + HMAC 서명)
- **인증**: `Member` 모델 사용 (`mb_id`로 조회)
- **리다이렉트**: 기본 `/cards`, `redirect_route=library-products`면 `/library-products`

### 멤버 모델 구분

| 모델 | DB | `isWhale()` |
|------|-----|-------------|
| `Member` | mysql (새김) | `false` |
| `WhaleMember` | mysql_whale (고래영어) | `true` |

둘 다 `MemberInterface` 구현.

### 고래영어 SSO (구현 중)

- **엔드포인트**: `GET /whale-sso-auth`
- **인증**: `WhaleMember` 모델 사용
- **SSO 키**: 파머스영어와 동일한 키 사용

## 시스템별 테이블 현황

| 테이블 | 파머스영어 (mysql) | 고래영어 (mysql_whale) | 비고 |
|--------|:------------------:|:----------------------:|------|
| `g5_member` | O | O | 레거시 그누보드 회원 |
| `member_cashes` | O | O | 고래영어는 수동 지급만 |
| `member_cash_transactions` | O | O | |
| `member_cards` | O | O | 빌링용 카드 |
| `member_payments` | O | O | 결제 내역 |
| `products` | O | O | 상품 |
| `member_subscribe_products` | O | O | 정기 구독 |
| `member_subscribe_product_logs` | O | O | 구독 로그 |
| `library_products` | O | X | 라이브러리 상품 |
| `member_subscribe_library_products` | O | X | 라이브러리 구독 |

### 고래영어 마이그레이션

고래영어 전용 마이그레이션은 별도 폴더에서 관리:

```bash
# 고래영어 마이그레이션 실행
php artisan migrate --database=mysql_whale --path=database/migrations/whale
```

마이그레이션 파일 위치: `database/migrations/whale/`

### 고래영어 사용자 제한 사항

- 라이브러리 구독 메뉴 접근 불가 (`/library-products`, `/library-payments`)
- 이캐시 조회만 가능 (수동 지급분)

## 주요 웹 페이지 (Blade)

| 경로 | 기능 |
|------|------|
| `/cards` | 카드 관리 |
| `/products` | 구독 상품 목록 |
| `/payments` | 결제 내역 |
| `/orders` | 주문 내역 |
| `/library-products` | 라이브러리 상품/구독 관리 |
| `/library-payments` | 라이브러리 결제 내역 |

## 주요 도메인

- **인증**: SSO, 앱 로그인 (`AuthController`, `SignInController`, `SSOController`)
- **결제/주문**: PortOne 웹훅, 주문 관리 (`OrderController`, `PortOneWebHookController`)
- **구독**: 멤버 구독, 라이브러리 구독 (`MemberSubscribeProductController`)
- **이캐시**: 캐시 충전/사용 (`MemberCashController`)
- **교육 컨텐츠**: 자료박사, 논술박사 (시리즈 > 볼륨 > 레슨 > 자료 구조)
- **알림톡**: 결제, 입금안내, 배송추적 (`OrderAlimTokController`)

## 멀티테넌트 아키텍처 (Factory 패턴)

파머스영어/고래영어를 동일한 서비스 레이어에서 처리하기 위해 Factory 패턴 사용:

```
Service → RepositoryFactory.create(member) → Repository (SEGIM/Whale)
```

### Repository Factory

| Factory | 생성하는 Repository |
|---------|---------------------|
| `MemberCashRepositoryFactory` | `MemberCashRepository` / `WhaleMemberCashRepository` |
| `MemberCardRepositoryFactory` | `MemberCardRepository` / `WhaleMemberCardRepository` |
| `MemberPaymentRepositoryFactory` | `MemberPaymentRepository` / `WhaleMemberPaymentRepository` |
| `MemberSubscribeProductRepositoryFactory` | `MemberSubscribeProductRepository` / `WhaleMemberSubscribeProductRepository` |

### 인터페이스

| Interface | 구현체 |
|-----------|--------|
| `MemberInterface` | `Member`, `WhaleMember` |
| `CardInterface` | `MemberCard`, `WhaleMemberCard` |
| `PaymentInterface` | `MemberPayment`, `WhaleMemberPayment` |
| `ProductInterface` | `Product`, `WhaleProduct` |
| `SubscribeProductInterface` | `MemberSubscribeProduct`, `WhaleMemberSubscribeProduct` |

### 사용 예시

```php
// Service에서 Factory를 통해 적절한 Repository 선택
public function getList(MemberInterface $member): Collection
{
    return $this->repositoryFactory->create($member)->getList($member);
}
```

## 프로젝트 구조

```
app/
├── Http/
│   ├── Controllers/     # 30+ 컨트롤러
│   ├── Requests/        # 폼 검증 (64개)
│   └── Resources/       # API 응답 포맷 (24개)
├── Models/
│   ├── Interfaces/      # MemberInterface, CardInterface 등
│   └── ...              # 38개 Eloquent 모델
├── Services/            # 35개 비즈니스 로직
├── Jobs/                # 18개 큐 작업
├── DTOs/                # 72개 데이터 전송 객체
├── Enums/               # 12개 열거형
├── Exceptions/          # 25개 커스텀 예외
└── Repositories/
    ├── Eloquent/        # Repository 구현체
    ├── Factories/       # Repository Factory
    └── Interfaces/      # Repository 인터페이스

resources/views/         # Blade 템플릿
├── layouts/             # 레이아웃
├── cards/               # 카드 관리
├── products/            # 상품/구독
├── payments/            # 결제 내역
├── orders/              # 주문 내역
└── modals/              # 모달 컴포넌트
```

## 주요 명령어

```bash
# 개발 서버
php artisan serve

# 마이그레이션
php artisan migrate

# 테스트
php artisan test

# 코드 포매팅 (반드시 변경한 파일만 인자로 지정할 것)
./vendor/bin/pint app/Services/SomeService.php
```

> `./vendor/bin/pint`를 인자 없이 실행하면 기존 파일 수백 개가 함께 재포매팅되어 불필요한 diff가 발생한다.

## 인증 미들웨어

- `CheckFromPamusMiddleware`: 파머스 시스템 요청 검증
- `Sanctum`: API 토큰 인증

## 외부 연동

- **PortOne**: 결제 웹훅 처리 (V2 API)
- **알림톡**: 카카오 알림톡 발송
- **SEGIM 티켓**: 티켓 발급/차감
- **S3**: `s3` 디스크(기본), `s3_edu` 디스크(강의 파일 → [강의 파일 업로드](#강의-파일-업로드-lecture-api))

## 정기결제 시스템

### 스케줄러 (Kernel.php)

| 커맨드 | 대상 | 실행 시간 |
|--------|------|-----------|
| `app:member-subscribe-product-make-start-command` | 파머스 구독 시작 | 매월 1일 00:10 |
| `app:whale-member-subscribe-product-make-start-command` | 고래 구독 시작 | 매월 1일 00:15 |
| `app:subscribe-product-payment-schedule-command` | 파머스 정기결제 | 매일 09:00 |
| `app:whale-subscribe-product-payment-schedule-command` | 고래 정기결제 | 매일 09:05 |
| `app:library-subscribe-payment-schedule-command` | 라이브러리 결제 | 매일 09:00 |
| `app:library-payment-remind-schedule-command` | 라이브러리 알림 | 매일 16:00 |

### 정기결제 흐름

```
스케줄러 → ProductBillingPaymentJob → MemberPaymentService::save()
                                              ↓
                                    RepositoryFactory.create(member)
                                              ↓
                            ┌─────────────────┴─────────────────┐
                            ↓                                   ↓
                  MemberPaymentRepository            WhaleMemberPaymentRepository
                  (member_payments)                  (whale_member_payments)
                            ↓                                   ↓
                  PortOneService.requestPaymentByBillingKey()
                            ↓
                  customData: {"isWhale": false/true}
```

### 구독 즉시 시작 API

스케줄러를 기다리지 않고 특정 구독을 즉시 시작하고 결제 실행:

```
POST /api/products/{productId}/subscribes/force-start
Body: { "mb_id": "member123" }
```

- `is_started=false` → `true` 변경 후 `ProductBillingPaymentJob` 디스패치
- 파머스/고래 모두 지원 (Sanctum 토큰의 `isWhale()`로 자동 구분)
- 검증: `is_started=true`이거나 `is_activated=false`면 412 에러

### PortOne 웹훅 처리

파머스/고래 결제를 구분하기 위해 PortOne의 `customData` 필드 사용:

```php
// 결제 요청 시 customData에 isWhale 플래그 추가
'customData' => json_encode(['isWhale' => $payment->member->isWhale()])

// 웹훅 수신 시 customData로 Repository 선택
$portOnePaymentDTO = $portOneService->getPaymentDetail($paymentId);
$repository = $repositoryFactory->createByIsWhale($portOnePaymentDTO->isWhale());
$payment = $repository->findByKey($paymentId);
```

### 제한사항 및 향후 작업

현재 **웹 UI를 통한 카드 등록 → 정기결제 → 웹훅 처리**는 파머스/고래 모두 정상 동작.

아래 **관리자단 API**는 파머스영어만 지원 (고래영어 지원 필요시 Factory 패턴 적용):

| API | 엔드포인트 | 이슈 |
|-----|-----------|------|
| 결제 재시도 | `POST /api/payments/retry` | `findFailedPayment()` 파머스만 조회 |
| 결제 취소 | `POST /api/payments/cancel` | `findByKey()` 파머스만 조회 |
| 결제 삭제 | `DELETE /api/payments/{id}` | `deleteFailedPayment()` 파머스만 조회 |

**해결 방안 (택1):**
1. 요청에 `is_whale` 파라미터 추가
2. Sanctum 인증으로 전환 (관리자 AccessToken 활용)

### 파머스/고래 모두 지원하는 관리자 API

| API | 엔드포인트 |
|-----|-----------|
| 구독 활성화/비활성화 | `PATCH /api/products/{productId}/subscribes/activate` |
| 구독 해지 | `POST /api/products/{productId}/unsubscribe` |
| 구독 즉시 시작 | `POST /api/products/{productId}/subscribes/force-start` |

## 마일리지 시스템

### 개요

마일리지 멤버스 회원을 위한 마일리지 적립/사용/전환 기능. 현재 파머스영어만 지원.

### 테이블 구조

| 테이블 | 파머스영어 | 고래영어 | 설명 |
|--------|:----------:|:--------:|------|
| `mileage_balance` | O | X (TODO) | 회원별 마일리지 잔액 현황 |
| `mileage_history` | O | X (TODO) | 마일리지 적립/사용/전환 이력 |
| `mileage_policy` | O | X (TODO) | 마일리지 정책 (적립률, 전환 한도 등) |

### 아키텍처

```
MileageController
       ↓
MileageService
       ↓
RepositoryFactory.create(member)
       ↓
┌──────────────────┬───────────────────┬──────────────────┐
↓                  ↓                   ↓
MileageBalanceRepo MileageHistoryRepo  MileagePolicyRepo
```

### Repository Factory

| Factory | 파머스 Repository | 고래 Repository |
|---------|-------------------|-----------------|
| `MileageBalanceRepositoryFactory` | `MileageBalanceRepository` | TODO |
| `MileageHistoryRepositoryFactory` | `MileageHistoryRepository` | TODO |
| `MileagePolicyRepositoryFactory` | `MileagePolicyRepository` | TODO |

### 주요 Enum

```php
// MileageActionEnum - 마일리지 액션 유형
Accrue   // 적립 (파란색, +금액)
Use      // 사용 (빨간색, -금액)
Convert  // 포인트 전환 (빨간색, -금액)

// MileageChannelEnum - 발생 채널
System   // 시스템 자동
Admin    // 관리자 수동
User     // 사용자 요청
```

### 웹 페이지

| 경로 | 기능 |
|------|------|
| `/mileage` | 나의 마일리지 (잔액 조회, 이력 목록) |

### 고래영어 확장 시 작업 목록

1. **DB 테이블 생성** (`mysql_whale`)
   - `mileage_balance`
   - `mileage_history`
   - `mileage_policy`

2. **모델 생성**
   - `WhaleMileageBalance`
   - `WhaleMileageHistory`
   - `WhaleMileagePolicy`

3. **Repository 생성**
   - `WhaleMileageBalanceRepository`
   - `WhaleMileageHistoryRepository`
   - `WhaleMileagePolicyRepository`

4. **Factory 분기 추가**
   - 각 Factory에서 `$member->isWhale()` 분기 처리

### 미구현 기능

- [ ] 포인트 전환 API (`POST /mileage/convert`)
- [ ] 마일리지 적립 API (교재 구매 시 자동 적립)
- [ ] 마일리지 사용 API

## 강의 파일 업로드 (lecture-api)

가비아 관리자 페이지에서 강의 영상/교육자료를 S3에 업로드하기 위한 API.
presign 발급은 가비아 서버가 중계하고, 파일 전송만 브라우저가 S3로 직접 수행한다.
라우트는 `routes/lecture-api.php`로 분리되어 있고 `/lecture-api` 프리픽스를 사용한다.

### 흐름

```
브라우저 --(1) presign 요청--> [가비아 adm/lecture_presign.php]  ← 관리자 세션 검증
                                       ↓ (2) 서버 간 호출
                              [이 앱의 presign API]
브라우저 <--(3) upload_url-----
브라우저 ==(4) PUT 파일 직접==> [S3 segim-edu]
브라우저 --(5) 파일명만 POST-> [가비아 lecture_form_update.php]
```

**파머스 Sanctum 토큰은 가비아 서버 안에만 존재하며 브라우저로 나가지 않는다.**
(2)가 서버 간 호출이라 이 앱으로 오는 요청에는 브라우저 CORS가 개입하지 않는다.
`config/cors.php`의 `paths`에 `lecture-api/*`가 남아 있으나 현 구조에서는 불필요하다
(브라우저가 직접 호출하던 시기의 잔재이며, 제거해도 동작에 영향 없음).

### 엔드포인트

| Method | 경로 | 기능 |
|--------|------|------|
| `POST` | `/lecture-api/lecture-files/presigned-url` | 업로드용 presigned PUT URL 발급 (유효시간 30분) |
| `DELETE` | `/lecture-api/lecture-files` | 강의 파일 삭제 (`type` + `file_name`) |

- **인증**: `auth:sanctum` + 파머스 회원만 (`authorize()`에서 `isWhale() === false` 검사). 고래영어 접근 불가
- **파일명**: 백엔드가 `Str::orderedUuid().'.'.{ext}`로 생성. 요청의 `ca_idx1`/`ca_idx2`는 가비아가 계속 전송하지만 검증 규칙에 없어 무시된다
- **확장자**: `LectureFileTypeEnum::allowedExtensions()` 화이트리스트로 제한

### S3 버킷 (segim-edu)

`config/filesystems.php`의 `s3_edu` 디스크. 기존 `s3` 디스크와 **다른 버킷**이며 크레덴셜만 동일하다.

| 항목 | 값 |
|------|-----|
| 버킷 / 리전 | `segim-edu` / `ap-northeast-2` |
| prefix | `media/lecture/video/`, `media/lecture/edufile/` |
| env | `AWS_EDU_REGION`, `AWS_EDU_BUCKET`, `AWS_EDU_URL` |

- 버킷 정책에 `Principal: *`의 `s3:GetObject`가 있어 객체는 **공개 읽기**다. 업로드 시 ACL 지정이 필요 없다
- **버킷 CORS는 이미 설정되어 있다** (PUT 허용, 모든 Origin). 브라우저가 S3로 직접 PUT하는 (4)단계에 필요한 설정이며 추가 작업 불필요
- CloudFront 배포(`E1ENGFTX79U4HJ`)가 `media/*`를 서빙하도록 열려 있으나, **재생 URL은 CloudFront가 아닌 S3 직접 URL을 사용**한다 (기존 강의 자료와 동일)
- presigned URL의 Content-Type은 서명에 포함되지 않아 강제되지 않는다. 응답의 `headers`는 권장값이며, 클라이언트가 보낸 값이 그대로 객체에 저장된다

### 미구현

- [ ] 고래영어 지원