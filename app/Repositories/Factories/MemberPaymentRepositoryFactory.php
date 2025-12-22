<?php

namespace App\Repositories\Factories;

use App\Exceptions\MemberPaymentRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MemberPaymentRepository;
use App\Repositories\Eloquent\WhaleMemberPaymentRepository;
use App\Repositories\Interfaces\MemberPaymentRepositoryInterface;

class MemberPaymentRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return MemberPaymentRepositoryInterface
     * @throws MemberPaymentRepositoryFactoryException
     */
    public function create(MemberInterface $member): MemberPaymentRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleMemberPaymentRepository::class),
            $member instanceof Member => app(MemberPaymentRepository::class),
            default => throw new MemberPaymentRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}