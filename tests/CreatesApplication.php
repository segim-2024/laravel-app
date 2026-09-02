<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use RuntimeException;

trait CreatesApplication
{
    /**
     * 테스트에서 MySQL 커넥션을 대체할 정의.
     */
    private const SQLITE_CONNECTION = [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
        'foreign_key_constraints' => true,
    ];

    /**
     * 테스트가 접속해도 되는 DB 호스트.
     */
    private const ALLOWED_DB_HOSTS = ['127.0.0.1', 'localhost', '::1'];

    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->forceSqliteConnections($app);
        $this->guardAgainstRemoteDatabase($app);

        return $app;
    }

    /**
     * sqlite 가 아닌 모든 커넥션 정의를 sqlite 인메모리로 덮는다.
     *
     * 이 프로젝트의 .env 는 운영 RDS 를 가리키므로, 테스트가 실제 커넥션 설정을
     * 그대로 쓰면 RefreshDatabase 가 운영 데이터를 삭제할 수 있다. DB_CONNECTION 을
     * sqlite 로 바꾸는 것만으로는 부족하다 — WhaleMember 처럼 $connection 을 명시한
     * 모델은 default 를 따르지 않고 자기 커넥션 정의를 그대로 쓰기 때문이다.
     *
     * 커넥션 이름을 나열하지 않고 전부 덮으므로, 새 커넥션이 추가되어도 자동으로
     * 적용된다. .env 와 phpunit.xml 의 DB_* 값에 전혀 의존하지 않는다.
     */
    private function forceSqliteConnections(Application $app): void
    {
        foreach (array_keys($app['config']->get('database.connections', [])) as $name) {
            $app['config']->set("database.connections.{$name}", self::SQLITE_CONNECTION);
        }
    }

    /**
     * 원격(운영) DB를 가리키는 커넥션이 남아 있으면 테스트를 시작하지 않는다.
     *
     * forceSqliteConnections() 가 정상 동작하면 걸릴 일이 없다. 그 처리가 빠지거나
     * 새 커넥션이 추가되었을 때를 잡아내는 회귀 방어다.
     * 트레이트의 setUp(마이그레이션 실행)보다 앞선 이 시점에서 차단한다.
     *
     * @throws RuntimeException 원격 호스트를 가리키는 커넥션이 있을 때
     */
    private function guardAgainstRemoteDatabase(Application $app): void
    {
        $offenders = [];

        foreach ($app['config']->get('database.connections', []) as $name => $connection) {
            if (($connection['driver'] ?? null) === 'sqlite') {
                continue;
            }

            if (! empty($connection['url'])) {
                $offenders[] = "{$name} → DATABASE_URL 이 설정되어 host 를 우회함";

                continue;
            }

            $host = (string) ($connection['host'] ?? '');

            if ($host !== '' && ! in_array($host, self::ALLOWED_DB_HOSTS, true)) {
                $offenders[] = "{$name} → {$host}";
            }
        }

        if ($offenders === []) {
            return;
        }

        throw new RuntimeException(
            PHP_EOL
            .'원격 DB에 연결된 상태라 테스트를 중단했습니다.'.PHP_EOL
            .'  '.implode(PHP_EOL.'  ', $offenders).PHP_EOL
            .'tests/CreatesApplication.php 의 forceSqliteConnections() 를 확인하세요.'.PHP_EOL
        );
    }
}
