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

### 테스트와 운영 DB 격리

`.env`의 DB 설정은 **운영 RDS를 가리킨 이력이 있다**. 테스트가 운영 데이터를 삭제하는 사고를 막기 위해 3중으로 차단되어 있다.

둘 다 `tests/CreatesApplication.php`에 있다.

| 방어선 | 메서드 | 내용 |
|--------|--------|------|
| 1 | `forceSqliteConnections()` | `database.connections` **전부**를 sqlite `:memory:`로 덮는다 |
| 2 | `guardAgainstRemoteDatabase()` | 그래도 원격 호스트를 가리키는 커넥션이 남아 있으면 예외로 중단 |

- `DB_CONNECTION`만 sqlite로 바꾸는 것으로는 **부족하다.** `WhaleMember`처럼 `$connection`을 명시한 모델은 default를 따르지 않고 자기 커넥션 정의를 그대로 쓴다
- 커넥션 이름을 나열하지 않고 전부 덮으므로 **새 커넥션이 추가돼도 자동 적용**되고, `.env`·`phpunit.xml`의 `DB_*` 값에 **전혀 의존하지 않는다**
- 두 처리 모두 `RefreshDatabase`가 마이그레이션을 실행하기 **전** 시점(`createApplication()`)에서 일어난다. `setUp()` 이후로 옮기면 이미 늦다
- 방어선 2는 1이 빠지거나 잘못됐을 때를 잡는 회귀 방어다. 정상 상태에서는 걸리지 않는다
- **`config/database.php`는 의도적으로 건드리지 않았다.** 커넥션 driver를 env로 빼면 테스트 격리에 쓸 수는 있지만, 운영에서 mysql로 고정인 값을 변수화해 "env가 빈 값이면 driver가 깨진다"는 실패 모드만 새로 생긴다. 격리는 테스트 코드 안에서 해결하는 것이 맞다

## 인증 미들웨어

- `CheckFromPamusMiddleware`: 파머스 시스템 요청 검증
- `CheckLibraryServerMiddleware`: 라이브러리 서버 요청 검증 (IP + API 키) → [라이브러리 API](#라이브러리-서버-회원-조회-api-library-api)
- `Sanctum`: API 토큰 인증

## 외부 연동

- **PortOne**: 결제 웹훅 처리 (V2 API)
- **알림톡**: 카카오 알림톡 발송
- **SEGIM 티켓**: 티켓 발급/차감
- **S3**: `s3` 디스크(기본), `s3_edu` 디스크(강의 파일·게시판 첨부 → [S3 파일 업로드](#s3-파일-업로드-file-api))

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

## S3 파일 업로드 (file-api)

가비아 관리자에서 S3(`segim-edu`)에 파일을 올리기 위한 presigned URL 발급/삭제 API.
강의박사(영상·교육자료)와 본부장 게시판(첨부파일)이 **같은 엔드포인트를 `type`으로 구분해** 사용한다.

### 라우트

| 프리픽스 | 파일 | 상태 |
|----------|------|------|
| `/file-api` | `routes/file-api.php` | 정식 |
| `/lecture-api` | `routes/lecture-api.php` | **DEPRECATED** — 가비아 전환 완료 후 제거 |

두 경로는 같은 컨트롤러를 쓰며 동작이 완전히 동일하다. 구 경로는 하위 호환용으로만 남아 있다.

### 흐름

```
브라우저 --(1) presign 요청--> [가비아 PHP]  ← 관리자 세션 검증
                                  ↓ (2) 서버 간 호출
                          [이 앱의 presign API]
브라우저 <--(3) upload_url----
       ==(4) PUT 파일=======> [S3 segim-edu]
브라우저 --(5) 파일명만 POST-> [가비아 저장 처리]
```

(4)를 누가 수행하는지는 용도에 따라 다르다.

| 용도 | (4) PUT 주체 | 이유 |
|------|--------------|------|
| 강의 영상 | **브라우저**가 S3로 직접 | 300MB 초과라 PHP 업로드 제한을 넘음 |
| 게시판 첨부 | **가비아 PHP**가 서버에서 | 실측 최대 1.2MB로 제한과 무관, 기존 폼 흐름 유지 |

**파머스 Sanctum 토큰은 가비아 서버 안에만 존재하며 브라우저로 나가지 않는다.**
(2)가 서버 간 호출이라 이 앱으로 오는 요청에는 브라우저 CORS가 개입하지 않는다.
`config/cors.php`의 `paths`에 `lecture-api/*`가 남아 있으나 현 구조에서는 불필요하다
(브라우저가 직접 호출하던 시기의 잔재이며, 제거해도 동작에 영향 없음).

### 엔드포인트

| Method | 경로 | 기능 |
|--------|------|------|
| `POST` | `/file-api/files/presigned-url` | 업로드용 presigned PUT URL 발급 (유효시간 30분) |
| `DELETE` | `/file-api/files` | 파일 삭제 (`type` + `file_name`) |

- **인증**: `auth:sanctum` + 파머스 회원만 (`authorize()`에서 `isWhale() === false` 검사). 고래영어 접근 불가
- **파일명**: 백엔드가 `Str::orderedUuid().'.'.{ext}`로 생성 (36+1+확장자 = **최대 41자**)
  - 게시판의 `BRANCH_BOARD_FILE.server_file_name`이 `varchar(50)`이라 **접두사를 붙이면 안 된다**
  - 요청의 `ca_idx1`/`ca_idx2`는 가비아가 전송하더라도 검증 규칙에 없어 무시된다
- **확장자**: `S3FileTypeEnum::allowedExtensions()` 화이트리스트로 type별 제한

### type별 prefix / 확장자

| type | prefix | 허용 확장자 |
|------|--------|-------------|
| `video` | `media/lecture/video/` | mp4, mov, m4v |
| `edufile` | `media/lecture/edufile/` | pdf, zip, hwp, hwpx, ppt, pptx, doc, docx, xls, xlsx |
| `board_file` | `board/files/` | 32종 (아래) |

`board_file`은 기존에 확장자 제한이 없던 기능이라 업무 파일을 넓게 허용한다.

| 분류 | 확장자 |
|------|--------|
| 문서 | pdf, hwp, hwpx, hwt, doc, docx, xls, xlsx, ppt, pptx, odt, ods, odp, rtf, txt, csv |
| 이미지·디자인 | jpg, jpeg, png, gif, bmp, webp, heic, tif, tiff, ai, psd |
| 압축 | zip, 7z, rar |
| 미디어 | mp4, mp3 |

**의도적으로 제외한 확장자** (요청이 와도 추가하지 말 것)

| 확장자 | 이유 |
|--------|------|
| `svg`, `html`, `htm` | 버킷이 공개 읽기라 브라우저에서 열리면 스크립트가 실행된다 |
| `xlsm`, `docm`, `pptm` | VBA 매크로 포함 |
| `exe`, `bat`, `sh`, `php`, `js` 등 | 실행 파일·스크립트 |

> **파일 크기는 백엔드에서 강제할 수 없다** — presigned PUT URL은 `Content-Length`를 서명에 포함하지 못한다. 크기 제한이 필요하면 호출하는 쪽에서 검사해야 한다.

### S3 버킷 (segim-edu)

`config/filesystems.php`의 `s3_edu` 디스크. 기존 `s3` 디스크와 **다른 버킷**이며 크레덴셜만 동일하다.

| 항목 | 값 |
|------|-----|
| 버킷 / 리전 | `segim-edu` / `ap-northeast-2` |
| env | `AWS_EDU_REGION`, `AWS_EDU_BUCKET`, `AWS_EDU_URL` |

- 버킷 정책에 `Principal: *`의 `s3:GetObject`가 있어 객체는 **공개 읽기**다. 업로드 시 ACL 지정이 필요 없다
  - **이 공개 읽기에 의존하는 기능이 있다.** 게시판 첨부 다운로드는 브라우저가 S3 URL로 직접 XHR GET을 보내 Blob으로 받고(원본 한글 파일명 유지 목적), 강의 영상 재생도 S3 직접 URL을 쓴다. **`GetObject`를 막으면 둘 다 중단된다** (`ListBucket`만 제거하는 것은 무관)
- **버킷 CORS는 이미 설정되어 있다** (GET/PUT 등 허용, 모든 Origin). 브라우저의 직접 PUT과 첨부 다운로드 XHR 양쪽에 필요하다
- CloudFront 배포(`E1ENGFTX79U4HJ`)가 `media/*`를 서빙하도록 열려 있으나, **재생 URL은 CloudFront가 아닌 S3 직접 URL을 사용**한다 (기존 강의 자료와 동일)
- IAM 사용자 `arn:aws:iam::343030089446:user/s3`에 prefix별 권한이 필요하다. **새 type을 추가할 때 IAM Resource에 해당 prefix를 추가하지 않으면 presign은 발급되지만 실제 PUT이 403으로 실패한다**
- presigned URL의 Content-Type은 서명에 포함되지 않아 강제되지 않는다. 응답의 `headers`는 권장값이며, 클라이언트가 보낸 값이 그대로 객체에 저장된다

### 미구현

- [ ] 고래영어 지원

## 라이브러리 서버 회원 조회 API (library-api)

외부 외주 업체가 운영하는 라이브러리 서버가 파머스/고래 회원의 자격증명을 검증하고 프로필을 조회하는 API.

### 엔드포인트

```
GET /library-api/{target}/member          target ∈ {pamus, whale}

X-Library-Api-Key: <서버 간 API 키>              → 미들웨어 검증
Authorization: Basic base64(account:password)    → 회원 인증
```

- **라우트**: `routes/library-api.php` (`RouteServiceProvider`에 `library-api` prefix 등록)
- **미들웨어**: `CheckLibraryServerMiddleware` (alias `library.server`) — IP 허용 목록 + API 키를 `hash_equals`로 검증
- **문서**: 외주 업체 전달용 산출물이며 `docs/`는 **gitignore 대상이라 저장소에 없다**. 현재 **v2.0.0**

  | 버전 | 변경 |
  |------|------|
  | 1.0.0 | 최초 배포 |
  | 2.0.0 | `level`을 `mb_level` → `mb_type` 변환값으로 교체, `type`에 `teacher` 추가·`other` 제거 |

  응답 필드의 **의미가 바뀌거나 `type` enum 값이 제거되면 major를 올린다.** 2.0.0이 그런 경우였다 — 문서화된 `level`의 의미가 바뀌었고 `other`가 사라져, 호출 측이 코드를 고쳐야 했다. 필드 추가처럼 하위 호환이면 minor로 충분하다.

  - `docs/openapi/library-api.yaml` — OAS 3.1 스펙
  - `docs/library-api.html` / `.pdf` — 영문판
  - `docs/library-api-ko.html` / `.pdf` — 한글판

  HTML이 원본이고 PDF는 headless Chrome 출력물이다. **문서를 고칠 때 영문·한글 양쪽을 함께 수정해야 한다.**

  ```bash
  # PDF 재생성 (macOS)
  for f in library-api library-api-ko; do
    "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" --headless --disable-gpu \
      --no-pdf-header-footer --print-to-pdf="$PWD/docs/$f.pdf" "file://$PWD/docs/$f.html"
  done
  ```

> **전용 rate limiter를 쓴다.** `api` 그룹의 `throttle:api`(60/min) 대신 `throttle:library-api`를 적용한다. `api` limiter는 키가 IP라 **고정 IP 한 곳에서 호출하는 외부 서버는 전체 트래픽이 한 버킷을 공유**하게 되어 부적합하다.
>
> 현재 `library-api` limiter는 `Limit::none()`이다 — 라이브러리 서버의 예상 호출량을 알 수 없기 때문이며, 접근 제어는 IP 허용 목록 + API 키가 담당한다. **제한이 필요해지면 `RouteServiceProvider`의 `library-api` limiter 반환값만 `Limit::perMinute(n)`으로 바꾸면 된다** (라우트 정의는 손댈 필요 없음).
>
> `Limit::none()`은 `Unlimited`를 반환하고 `ThrottleRequests`가 이를 만나면 캐시 접근 없이 즉시 통과시키므로(`ThrottleRequests.php:121`) 오버헤드가 없다. 제한을 걸면 **429가 새 응답 코드로 추가되므로 `docs/openapi/library-api.yaml`과 PDF도 함께 갱신할 것** (현재 문서에는 429가 없다).
>
> 같은 문제가 `file-api`/`lecture-api`에도 있다. 가비아 서버 단일 IP가 `throttle:api` 60/min을 공유하므로, 관리자가 첨부를 연속 업로드하면 걸릴 수 있다. 필요해지면 동일한 방식으로 전용 limiter를 분리할 것.

> **호출 측은 `Accept: application/json`을 보내야 한다.** 잘못된 `target`으로 인한 404는 라우트 매칭 실패라 라우트 미들웨어보다 앞서 발생하고, 이 헤더가 없으면 본문이 HTML이다. 코드로 강제할 수 없어 문서로만 안내한다.

### 응답 코드

| 상황 | 코드 | `code` |
|------|:----:|--------|
| 성공 | 200 | — (`Cache-Control: no-store, private`) |
| 회원 없음 / 탈퇴 / 차단 | 204 | — (본문 없음) |
| 비밀번호 불일치 · 미설정 | 403 | `PASSWORD_MISMATCH` |
| IP 불허 | 401 | `IP_NOT_ALLOWED` |
| API 키 불일치·누락 | 401 | `INVALID_API_KEY` |
| Basic 헤더 누락·형식 오류 | 401 | `CREDENTIALS_REQUIRED` |
| 잘못된 `target` | 404 | — (라우트 미매칭) |

탈퇴·차단 회원을 403이 아닌 **204로 응답해 존재 여부를 노출하지 않는다.**

> `Cache-Control: no-store, private`는 **200 응답에만** 붙인다(컨트롤러에서 설정). 204/401/403은 Laravel 기본값 `no-cache, private`이다. **의도적으로 통일하지 않았다** — 경로상 캐시 계층이 전혀 없고(CloudFront 미배치, ALB는 캐시 기능 없음, nginx에 `proxy_cache`/`fastcgi_cache` 없음), 개인정보가 담긴 응답은 200뿐이며, `no-cache`도 재사용 전 재검증을 강제해 stale 응답 위험이 없기 때문이다. **CloudFront 등 캐시 계층을 도입하면 재검토할 것.**

### 응답 필드 ↔ g5_member 컬럼

응답은 `JsonResource` 기본 동작대로 **`data` 키로 래핑된다**(`{"data": {...}}`). 이 프로젝트는 전역 `withoutWrapping()`을 쓰지 않으므로 다른 API와 동일한 형태다.

| 응답 필드 | 컬럼 | 비고 |
|-----------|------|------|
| `account` | `mb_id` | |
| `name` | `mb_name` | |
| `level` | **`mb_type`** | 클라우봇 등급 체계로 변환 (`MemberTypeEnum::libraryLevel()`). 학생 1 / 학원 2 / 어드민 3 / 강사 4 / 그 외 0. **`mb_level`이 아니다** |
| `type` | **`mb_type`** | `MemberTypeEnum`으로 변환 (student/campus/teacher/admin/headquarters/unknown) |
| `target` | — | path 파라미터를 에코 (`MemberTargetEnum`) |
| `campus_account` | **`mb_4`** | 학원이면 자기 `mb_id`, 그 외에는 `mb_4`(빈 문자열이면 `null`) |

**`mb_level`로 회원 유형을 판별하면 안 된다.** 파머스 기준 `mb_level=1`에 학원 766명과 학생 183명이 섞여 있다. 유형 판별은 `mb_type`(→ `type`)으로만 한다.

`mb_type` 의미는 파머스 관리자 화면 라벨(`/yc5/adm/member_form.php`, `member_list_new_t.php`)로 **전부 확정**되었다.

| `mb_type` | 라벨 | `level` | 파머스 | 고래 | `mb_4` 채워짐 |
|:---------:|------|:-------:|-------:|-----:|--------------|
| 4 | 학생 | 1 | 54,692 | 7,603 | 100% |
| 3 | 학원 | 2 | 1,359 | 366 | **0% (전원 비어 있음)** |
| 1 | 어드민 | 3 | 6 | 1 | 0% |
| 5 | **강사** | **4** | 2,682 | 0 (파머스 전용) | 97.5% |
| 2 | 본부장 | 0 | 42 | 34 | 0% |
| 0 | 미설정 | 0 | 1 | — | 0% |

강사는 학생과 같이 `mb_4`에 소속 학원의 `mb_id`를 담는다. 강의박사 `user` 테이블 연결률이 **100%**(2,679/2,679)로, 학생 97.9%·학원 86.7%와 대비된다.

- **학원 계정은 `mb_4`가 전원 비어 있어**(전건 확인) `campus_account`에 자기 `mb_id`를 채워 응답한다(`LibraryMemberResource::resolveCampusAccount()`). 호출 측이 유형별로 분기하지 않고 캠퍼스 단위로 묶을 수 있게 하기 위함이다. 컬럼값을 그대로 내보내면 학원 계정에서 항상 빈 값이 되어, 레거시 `userLogin.php`가 겪는 문제를 그대로 물려받는다
- `mb_4`는 **FK도 인덱스도 없는 비공식 참조**다. 고래 학생의 약 30%가 존재하지 않는 학원 계정을 가리킨다(매칭률 69.3% 실측). 학원 마스터와 INNER JOIN 하면 그만큼 소실된다

`mb_4`가 캠퍼스 계정인 근거: 파머스 57,151건(97.5%)이 채워져 있고 그중 **95.5%가 다른 회원의 `mb_id`와 일치**한다. 서로 다른 값은 1,052개(캠퍼스 수). 고래 DB에도 같은 컬럼이 있다. `mb_recommend`(14건)와 `mb_2`(캠퍼스 회원만)는 무관하다.

### 회원 조회 범위 (조사 완료, 재논의 불필요)

**`target`은 조회할 DB만 고르고 별도 필터를 걸지 않는다.** 아래는 그렇게 정한 근거다.

**두 DB는 상당 부분 미러링 관계다** (실측)

| 항목 | 건수 |
|------|-----:|
| 고래 DB(`englishwhale`) 전체 | 8,004 |
| 파머스 DB의 `mb_is_whale='Y'` | 8,050 |
| 양쪽에 같은 `mb_id` 존재 | 7,855 |
| 파머스에만 있는 고래 회원 | **216** |
| 고래에만 있는 회원 | 149 |

겹치는 7,855명은 `mb_type` 100% 일치, 소속 캠퍼스 7,854, 이름 7,847, **비밀번호 99.8% 일치**(상이 17건). 즉 겹치는 회원은 `target`을 어느 쪽으로 보내든 인증 결과가 사실상 같다.

**`mb_is_segim`과 `mb_is_whale`은 상호배타적이다** (둘 다 `Y`는 1건뿐)

| `mb_is_segim` | `mb_is_whale` | 건수 | 고래 DB에도 존재 |
|:---:|:---:|-----:|-----:|
| Y | N | 48,552 | 1 |
| N | Y | 8,049 | 7,834 |
| N | N | 2,180 | 20 |

**결정과 이유**

- `target=whale` → **고래 DB만** 조회한다. 파머스 fallback을 두지 않는다
- `target=pamus` → **조건 없이** 파머스 DB 전체를 조회한다. `mb_is_whale='N'`이나 `mb_is_segim='Y'`를 붙이지 않는다
- 조건을 붙이면 논리적으로는 깔끔해지지만, **파머스에만 있는 고래 회원 216명이 어느 경로로도 조회되지 않는다.** 현행에서는 이들을 `target=pamus`로 찾을 수 있다
- `mb_is_segim='Y'`까지 거는 안은 특히 위험하다. 플래그가 없는 2,180명(학생 1,132·학원 995)이 통째로 빠진다
- 대가로 **고래 전용 회원 8,049명이 `target=pamus`로도 조회된다.** 호출 측이 회원 소속을 알고 `target`을 지정하는 구조이므로 실질적 문제로 보지 않는다
- 호출 측이 **두 `target`을 각각 조회해 합치면 7,855건이 중복**된다. `account` 기준 dedup이 필요하고, 비밀번호가 다른 케이스가 17건 있어 어느 레코드를 우선할지도 정해야 한다. 이 주의사항은 외부 문서(PDF 6장)에도 넣었다

### `level`은 레거시 `user_level` 체계를 따른다

`level`은 **`mb_type`을 변환한 값**이며 `mb_level` 컬럼과 무관하다.

| `mb_type` | 라벨 | `level` |
|:---------:|------|:-------:|
| 4 | 학생 | 1 |
| 3 | 학원 | 2 |
| 1 | 어드민 | 3 |
| 5 | 강사 | 4 |
| 2, 0 | 본부장·미설정 | 0 |

고래영어 레거시의 `api/userLogin.php`와 `whale/index.php`가 쓰던 변환과 동일하다. 이 API의 소비자인 **클라우봇(외부 학습프로그램)이 이 체계로 구현되어 있다.**

> **초기에는 `mb_level`을 그대로 내보냈고 그것이 잘못이었다.** 당시 근거는 "`user_level`은 코드베이스에서 생성 1곳·소비 0곳"이었으나, 그 grep은 **사내 코드 기준**이었고 클라우봇은 외부 시스템이라 잡히지 않았다. 실제 소비자가 있었다.
>
> 증상은 학생의 `mb_level`이 99.4% 확률로 `3`이라 "학생인데 level이 3으로 온다"로 접수되었다. 학생만 틀린 게 아니라 학원은 7/1/6, 관리자는 10이 나가고 있었다.

**`mb_level`로 회원 유형을 판별하면 안 된다**는 원칙은 그대로다. `mb_level`은 그누보드 게시판 권한 레벨(0~10)이며 응답에 포함하지 않는다.

**강사(`mb_type=5`)는 `level` 4로 매핑한다.** 클라우봇이 신설한 등급이며, 파머스 관리자 화면에서 `mb_type=5`가 강사로 확정되었다. 파머스 전용이라 `target=whale`에서는 나오지 않는다.

> 다만 **클라우봇에 "강사 = mb_type 5" 확인은 아직 받지 못했다.** 또한 레거시 SSO(`whale/index.php`)가 `mb_type` 1·3·4만 통과시켜 강사 2,682명은 현재 클라우봇에 진입할 수 없다. 차단 해제는 레거시 PHP 쪽 작업이다.

> 고래 레거시 API가 `englishwhale UNION segim WHERE mb_is_whale='N'`으로 조회하는 것은 **중복 없이 전체 회원**을 만들기 위한 것이다(8,004 + 50,732). 고래 학습프로그램은 파머스 회원도 로그인 대상으로 삼는다. 이 API는 그 구조를 따르지 않는다.

### 비밀번호 검증 (`GnuboardPasswordVerifier`)

`mb_password`는 운영 전량이 **길이 41 = MySQL `PASSWORD()` 출력**이다 (`*` + 40자리 대문자 HEX). 그누보드4 계열 `sql_password()` 방식이며, bcrypt·sha256(40자)는 **0건**이다.

```php
'*'.strtoupper(sha1(sha1($plain, true)))   // MySQL 8.0 에서 PASSWORD() 가 제거되어 PHP 로 재현
```

- 길이 41이 아니거나 `*`로 시작하지 않으면 **무조건 거부**한다
- 비밀번호가 비어 있는 회원이 존재한다 (파머스 262건 + 이상치 1건, 고래 68건). 빈 평문·빈 해시는 항상 거부

### 인프라 전제 (조사 완료, 재확인 불필요)

> **AWS 계정은 `343030089446`이고 aws cli `pamus` 프로필로 조회한다.** 기본(`default`) 프로필은 아누타 계정(`989126025677`)이라 **이 앱과 무관한 리소스가 조회된다.** 인프라를 확인할 때 프로필을 반드시 지정할 것.

| 항목 | 사실 |
|------|------|
| 호스트 / ALB | `app.epamus.com` → `lb-epamus-app` (타겟에 EC2 `i-0ff5094ecf4af47d3` 등록) |
| ALB `xff_header_processing.mode` | `append` — 클라이언트 XFF 뒤에 실제 peer IP를 덧붙임 |
| ALB 앞단 | CloudFront **없음**. hop = 1 |
| EC2 직접 접근 | 80 포트 차단됨(22만 열림) → ALB 우회 불가 |
| `TrustProxies::$proxies = '*'` | **안전하다.** Laravel은 `'*'`를 "모든 프록시 신뢰"가 아니라 `setTrustedProxyIpAddressesToTheCallingIp()` → REMOTE_ADDR 하나만 신뢰로 처리한다. XFF를 위조해도 `$request->ip()`는 공격자의 실제 IP를 반환한다 |
| nginx | `real_ip_module` 미사용, 기본 combined 로그 |

→ **`$request->ip()`를 그대로 쓰면 되고, XFF를 직접 파싱할 필요가 없다.**

자격증명을 쿼리스트링에 두면 안 된다. combined 로그는 `"$request"`에 쿼리스트링을 포함하지만 `Authorization` 헤더는 기록하지 않는다.

### nginx User-Agent 차단 (외주 업체에 반드시 고지)

`/etc/nginx/sites-available/laravel-app`에 봇 차단 규칙이 있어 **애플리케이션에 닿기 전에 403(HTML)** 이 반환된다.

```nginx
if ($http_user_agent ~* (python-requests|wget|scanner|nikto|sqlmap)) { return 403; }
```

운영 서버 실측 — 차단: `python-requests/2.31.0`, `Wget/1.21.3` / 통과: `Python-urllib`, `python-httpx`, `PycURL`, `curl`, `GuzzleHttp`, UA 없음.

Python이라서 막히는 게 아니라 `requests` 라이브러리의 기본 UA가 걸리는 것이다. 이 403은 `PASSWORD_MISMATCH` 403과 상태 코드로 구분되지 않고 Laravel 로그에도 남지 않는다. **정상 API 오류는 항상 `code` 필드를 가진 JSON이고, 차단은 HTML이다.**

### 환경변수

```
LIBRARY_INBOUND_API_KEY=      # 비워두면 모든 요청이 401
LIBRARY_INBOUND_IPS=          # 쉼표(,) 또는 파이프(|)로 다중 IP 지정
```

허용 IP 목록 파싱은 `preg_split('/[,|]/')` 이며 항목의 앞뒤 공백은 무시한다. **둘 중 어느 구분자를 써도 되고 섞어 써도 된다.** 빈 값·공백뿐인 값·구분자뿐인 값은 모두 빈 목록이 되어 **모든 요청이 401로 거부된다**(안전한 기본값). 부분 문자열 일치는 하지 않는다(`10.10.10.100`은 `10.10.10.10`을 허용하지 않는다).

기존 `LIBRARY_API_KEY`(`services.library.api_key`)는 **아웃바운드 서명용**(`LibraryPaymentDoneApiRequestDTO`)이며 방향이 다르므로 재사용하지 않는다.

### 미구현

- [ ] 운영에서 문서를 열람하는 라우트 (`GET /library-api/docs`)
