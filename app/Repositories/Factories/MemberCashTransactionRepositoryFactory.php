<?php

namespace App\Repositories\Factories;

use App\Exceptions\MemberCashTransactionRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MemberCashTransactionRepository;
use App\Repositories\Eloquent\WhaleMemberCashTransactionRepository;
use App\Repositories\Interfaces\MemberCashTransactionRepositoryInterface;

class MemberCashTransactionRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return MemberCashTransactionRepositoryInterface
     * @throws MemberCashTransactionRepositoryFactoryException
     */
    public function create(MemberInterface $member): MemberCashTransactionRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleMemberCashTransactionRepository::class),
            $member instanceof Member => app(MemberCashTransactionRepository::class),
            default => throw new MemberCashTransactionRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}
