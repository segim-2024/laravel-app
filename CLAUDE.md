# SG-APP

고래영어와 파머스영어(새김)의 레거시 PHP(그누보드) 시스템을 통합 관리하기 위한 Laravel 백엔드 애플리케이션.

## 기술 스택

- **Backend**: Laravel 10.10 (PHP 8.1+)
- **Auth**: Laravel Sanctum (토큰 기반 API 인증)
- **Frontend**: Blade 템플릿
- **Payment**: Toss, PortOne(포트원)
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
| `member_cards` | O | X | 빌링용 카드 |
| `member_payments` | O | X | 결제 내역 |
| `member_subscribe_products` | O | X | 정기 구독 |
| `library_products` | O | X | 라이브러리 상품 |
| `member_subscribe_library_products` | O | X | 라이브러리 구독 |

> 고래영어에 빌링 시스템 추가 시 해당 테이블들을 `mysql_whale`에 마이그레이션 필요

### 고래영어 사용자 제한 사항

- 라이브러리 구독 메뉴 접근 불가 (`/library-products`, `/library-payments`)
- 정기 결제 카드/상품 관리 불가 (빌링 시스템 미구현)
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
- **결제/주문**: Toss/PortOne 웹훅, 주문 관리 (`OrderController`, `TossWebHookController`)
- **구독**: 멤버 구독, 라이브러리 구독 (`MemberSubscribeProductController`)
- **이캐시**: 캐시 충전/사용 (`MemberCashController`)
- **교육 컨텐츠**: 자료박사, 논술박사 (시리즈 > 볼륨 > 레슨 > 자료 구조)
- **알림톡**: 결제, 입금안내, 배송추적 (`OrderAlimTokController`)

## 프로젝트 구조

```
app/
├── Http/
│   ├── Controllers/     # 30+ 컨트롤러
│   ├── Requests/        # 폼 검증 (64개)
│   └── Resources/       # API 응답 포맷 (24개)
├── Models/              # 38개 Eloquent 모델
├── Services/            # 35개 비즈니스 로직
├── Jobs/                # 18개 큐 작업
├── DTOs/                # 72개 데이터 전송 객체
├── Enums/               # 12개 열거형
├── Exceptions/          # 25개 커스텀 예외
└── Repositories/        # 저장소 패턴

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

# 코드 포매팅
./vendor/bin/pint
```

## 인증 미들웨어

- `CheckFromPamusMiddleware`: 파머스 시스템 요청 검증
- `Sanctum`: API 토큰 인증

## 외부 연동

- **Toss/PortOne**: 결제 웹훅 처리
- **알림톡**: 카카오 알림톡 발송
- **SEGIM 티켓**: 티켓 발급/차감