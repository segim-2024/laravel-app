<?php

namespace Tests\Feature\LibraryApi;

use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Interfaces\MemberRepositoryInterface;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * 라이브러리 서버 → 회원 조회 API 테스트.
 *
 * Repository 를 목으로 대체해 DB 에 접근하지 않는다.
 */
class ShowLibraryMemberTest extends TestCase
{
    private const API_KEY = 'library-inbound-test-key';

    private const ALLOWED_IP = '10.10.10.10';

    private const ALB_IP = '172.31.37.235';

    /** 평문 'password' 의 MySQL PASSWORD() 해시 */
    private const PASSWORD_HASH = '*2470C0C06DEE42FD1618BB99005ADCA2EC9D1E19';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.library.inbound.api_key' => self::API_KEY,
            'services.library.inbound.ips' => self::ALLOWED_IP.',10.10.10.11',
        ]);
    }

    /**
     * DB 접근 없이 회원 인스턴스를 만든다.
     */
    private function member(array $attributes = [], bool $isWhale = false): Member|WhaleMember
    {
        $model = $isWhale ? new WhaleMember : new Member;

        return $model->forceFill(array_merge([
            'mb_no' => 1,
            'mb_id' => 'student01',
            'mb_password' => self::PASSWORD_HASH,
            'mb_name' => '홍길동',
            'mb_level' => 3,
            'mb_type' => 4,
            'mb_4' => 'campus01',
            'withdrawn_at' => null,
            'mb_intercept_date' => '',
            'mb_leave_date' => '',
        ], $attributes));
    }

    /**
     * @param  string  $method  find(파머스) 또는 findFromWhale(고래)
     */
    private function mockRepository(?Member $member, string $method = 'find'): void
    {
        $this->mock(MemberRepositoryInterface::class, function (MockInterface $mock) use ($member, $method) {
            $mock->shouldReceive($method)->andReturn($member);
        });
    }

    private function basic(string $account = 'student01', string $password = 'password'): string
    {
        return 'Basic '.base64_encode("{$account}:{$password}");
    }

    /**
     * 라이브러리 서버 → ALB → 앱 경로를 재현한다.
     * ALB 는 XFF 뒤에 실제 peer IP 를 append 하므로 REMOTE_ADDR 는 ALB 노드가 된다.
     */
    private function callApi(string $target = 'pamus', array $headers = [], string $forwardedFor = self::ALLOWED_IP)
    {
        return $this->withServerVariables(['REMOTE_ADDR' => self::ALB_IP])
            ->getJson("/library-api/{$target}/member", array_merge([
                'X-Library-Api-Key' => self::API_KEY,
                'X-Forwarded-For' => $forwardedFor,
                'Authorization' => $this->basic(),
            ], $headers));
    }

    // ---------------------------------------------------------------- 200

    public function test_파머스_회원을_조회한다(): void
    {
        $this->mockRepository($this->member());

        $this->callApi()
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'account' => 'student01',
                    'name' => '홍길동',
                    // mb_level 이 3 이어도 학생의 등급은 1 이다 (mb_type 기반)
                    'level' => 1,
                    'type' => 'student',
                    'target' => 'pamus',
                    'campus_account' => 'campus01',
                ],
            ]);
    }

    public function test_고래_회원을_조회한다(): void
    {
        $whale = (new WhaleMember)->forceFill([
            'mb_id' => 'whale01',
            'mb_password' => self::PASSWORD_HASH,
            'mb_name' => '김고래',
            'mb_level' => 3,
            'mb_type' => 4,
            'mb_4' => 'whalecampus',
        ]);

        $this->mock(MemberRepositoryInterface::class, function (MockInterface $mock) use ($whale) {
            $mock->shouldReceive('findFromWhale')->andReturn($whale);
        });

        $this->callApi('whale', ['Authorization' => $this->basic('whale01')])
            ->assertOk()
            ->assertJson(['data' => ['target' => 'whale', 'campus_account' => 'whalecampus']]);
    }

    public function test_소속_캠퍼스가_없으면_null을_반환한다(): void
    {
        $this->mockRepository($this->member(['mb_4' => '']));

        $this->callApi()->assertOk()->assertJson(['data' => ['campus_account' => null]]);
    }

    /**
     * 학원 계정(mb_type=3)은 운영 데이터 전건이 mb_4 가 비어 있다.
     * 호출 측이 분기하지 않도록 campus_account 에 자기 계정을 채워 응답한다.
     */
    public function test_학원_계정은_campus_account에_자기_계정이_들어간다(): void
    {
        $this->mockRepository($this->member(['mb_id' => 'campus01', 'mb_type' => 3, 'mb_4' => '']));

        $this->callApi(headers: ['Authorization' => $this->basic('campus01')])
            ->assertOk()
            ->assertJson(['data' => [
                'account' => 'campus01',
                'level' => 2,
                'type' => 'campus',
                'campus_account' => 'campus01',
            ]]);
    }

    /**
     * 학원이 아닌 회원은 소속이 없으면 그대로 null 이다.
     */
    public function test_학원이_아니면_소속이_없을_때_null을_반환한다(): void
    {
        $this->mockRepository($this->member(['mb_type' => 4, 'mb_4' => '']));

        $this->callApi()->assertOk()->assertJson(['data' => ['campus_account' => null]]);
    }

    /**
     * 강사(mb_type=5)는 파머스 전용이며 학생처럼 mb_4 에 소속 학원을 담는다.
     * 클라우봇이 신설한 등급 4 로 응답해야 한다.
     */
    public function test_강사_계정은_type이_teacher이고_등급이_4다(): void
    {
        $this->mockRepository($this->member(['mb_type' => 5, 'mb_4' => 'campus01']));

        $this->callApi()
            ->assertOk()
            ->assertJson(['data' => [
                'level' => 4,
                'type' => 'teacher',
                'campus_account' => 'campus01',
            ]]);
    }

    public function test_정의되지_않은_회원_구분은_unknown으로_응답한다(): void
    {
        $this->mockRepository($this->member(['mb_type' => 99]));

        $this->callApi()->assertOk()->assertJson(['data' => ['type' => 'unknown']]);
    }

    public function test_응답이_캐시되지_않도록_헤더를_설정한다(): void
    {
        $this->mockRepository($this->member());

        $this->callApi()->assertOk()->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_비밀번호는_응답에_포함되지_않는다(): void
    {
        $this->mockRepository($this->member());

        $this->callApi()->assertOk()->assertJsonMissingPath('data.mb_password');
    }

    // ---------------------------------------------------------------- 204

    public function test_회원이_없으면_204를_반환한다(): void
    {
        $this->mockRepository(null);

        $this->callApi()->assertNoContent();
    }

    public function test_탈퇴한_회원은_204를_반환한다(): void
    {
        $this->mockRepository($this->member(['withdrawn_at' => '2026-01-01 00:00:00']));

        $this->callApi()->assertNoContent();
    }

    public function test_차단된_회원은_204를_반환한다(): void
    {
        $this->mockRepository($this->member(['mb_intercept_date' => '20260101']));

        $this->callApi()->assertNoContent();
    }

    public function test_탈퇴일이_기록된_회원은_204를_반환한다(): void
    {
        $this->mockRepository($this->member(['mb_leave_date' => '20260101']));

        $this->callApi()->assertNoContent();
    }

    // ---------------------------------------------------------------- 403

    public function test_비밀번호가_틀리면_403을_반환한다(): void
    {
        $this->mockRepository($this->member());

        $this->callApi(headers: ['Authorization' => $this->basic('student01', 'wrong-password')])
            ->assertForbidden()
            ->assertJson(['code' => 'PASSWORD_MISMATCH']);
    }

    /**
     * 운영에 비밀번호 미설정 회원이 존재한다 (파머스 262건, 고래 68건).
     */
    public function test_비밀번호가_미설정된_회원은_403을_반환한다(): void
    {
        $this->mockRepository($this->member(['mb_password' => '']));

        $this->callApi()->assertForbidden()->assertJson(['code' => 'PASSWORD_MISMATCH']);
    }

    public function test_빈_비밀번호로는_인증할_수_없다(): void
    {
        $this->mockRepository($this->member(['mb_password' => '']));

        $this->callApi(headers: ['Authorization' => $this->basic('student01', '')])
            ->assertForbidden();
    }

    // ---------------------------------------------------------------- 401

    public function test_api_키가_없으면_401을_반환한다(): void
    {
        $this->callApi(headers: ['X-Library-Api-Key' => ''])
            ->assertUnauthorized()
            ->assertJson(['code' => 'INVALID_API_KEY']);
    }

    public function test_api_키가_틀리면_401을_반환한다(): void
    {
        $this->callApi(headers: ['X-Library-Api-Key' => 'wrong-key'])
            ->assertUnauthorized()
            ->assertJson(['code' => 'INVALID_API_KEY']);
    }

    public function test_허용되지_않은_ip는_401을_반환한다(): void
    {
        $this->callApi(forwardedFor: '203.0.113.9')
            ->assertUnauthorized()
            ->assertJson(['code' => 'IP_NOT_ALLOWED']);
    }

    /**
     * 허용 IP 목록은 쉼표와 파이프를 모두 구분자로 받는다.
     *
     * @dataProvider 허용_ip_목록_구분자
     */
    public function test_구분자로_나뉜_허용_ip_목록을_인식한다(string $ips): void
    {
        config(['services.library.inbound.ips' => $ips]);
        $this->mockRepository($this->member());

        $this->callApi()->assertOk();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function 허용_ip_목록_구분자(): array
    {
        return [
            '단일 IP' => ['10.10.10.10'],
            '쉼표' => ['10.10.10.11,10.10.10.10'],
            '파이프' => ['10.10.10.11|10.10.10.10'],
            '혼용' => ['10.10.10.11|10.10.10.12,10.10.10.10'],
            '공백 포함' => [' 10.10.10.11 | 10.10.10.10 , 10.10.10.12 '],
            '후행 구분자' => ['10.10.10.10,'],
        ];
    }

    /**
     * 허용 IP 목록이 비어 있으면 모든 요청을 거부한다.
     */
    public function test_허용_ip_목록이_비어있으면_401을_반환한다(): void
    {
        config(['services.library.inbound.ips' => '']);

        $this->callApi()->assertUnauthorized()->assertJson(['code' => 'IP_NOT_ALLOWED']);
    }

    /**
     * ALB 는 클라이언트가 보낸 XFF 뒤에 실제 peer IP 를 append 한다.
     * TrustProxies 는 REMOTE_ADDR 만 신뢰하므로 사칭한 IP 가 아닌 실제 IP 가 남는다.
     */
    public function test_xff를_위조해_허용_ip를_사칭해도_401을_반환한다(): void
    {
        $this->callApi(forwardedFor: self::ALLOWED_IP.', 203.0.113.9')
            ->assertUnauthorized()
            ->assertJson(['code' => 'IP_NOT_ALLOWED']);
    }

    public function test_xff_체인을_여러_단계_위조해도_401을_반환한다(): void
    {
        $this->callApi(forwardedFor: self::ALLOWED_IP.', 10.10.10.11, 203.0.113.9')
            ->assertUnauthorized()
            ->assertJson(['code' => 'IP_NOT_ALLOWED']);
    }

    public function test_basic_인증_헤더가_없으면_401을_반환한다(): void
    {
        $this->callApi(headers: ['Authorization' => ''])
            ->assertUnauthorized()
            ->assertJson(['code' => 'CREDENTIALS_REQUIRED']);
    }

    public function test_basic이_아닌_인증_방식은_401을_반환한다(): void
    {
        $this->callApi(headers: ['Authorization' => 'Bearer some-token'])
            ->assertUnauthorized()
            ->assertJson(['code' => 'CREDENTIALS_REQUIRED']);
    }

    public function test_계정이_비어있으면_401을_반환한다(): void
    {
        $this->callApi(headers: ['Authorization' => $this->basic('', 'password')])
            ->assertUnauthorized()
            ->assertJson(['code' => 'CREDENTIALS_REQUIRED']);
    }

    /**
     * API 키 검증이 회원 조회보다 먼저 일어나야 한다 (Repository 가 호출되면 안 됨).
     */
    public function test_인증_실패_시_회원을_조회하지_않는다(): void
    {
        $this->mock(MemberRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('find');
            $mock->shouldNotReceive('findFromWhale');
        });

        $this->callApi(headers: ['X-Library-Api-Key' => 'wrong-key'])->assertUnauthorized();
    }

    // ---------------------------------------------------------------- 404

    public function test_정의되지_않은_target은_404를_반환한다(): void
    {
        $this->callApi('segim')->assertNotFound();
    }
}
