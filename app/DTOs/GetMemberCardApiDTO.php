<?php

namespace App\DTOs;

use App\Http\Requests\GetMemberCardApiRequest;

class GetMemberCardApiDTO
{
    public function __construct(
        public ?string $search = null,
        public int $page = 1,
        public int $perPage = 10,
        public string $orderBy = 'created_at',
        public string $orderDirection = 'desc',
    ) {}

    /**
     * Create a new DTO instance from a request.
     */
    public static function createFromRequest(GetMemberCardApiRequest $request): self
    {
        return new self(
            search: $request->input('search'),
            page: (int) $request->input('page', 1),
            perPage: (int) $request->input('per_page', 10),
            orderBy: $request->input('order_by', 'created_at'),
            orderDirection: in_array($request->input('order_direction'), ['asc', 'desc'])
                ? $request->input('order_direction')
                : 'desc'
        );
    }

    /**
     * Get the pagination options for the query.
     */
    public function getPaginationOptions(): array
    {
        return [
            'perPage' => $this->perPage,
            'page' => $this->page,
        ];
    }

    /**
     * Get the order options for the query.
     */
    public function getOrderOptions(): array
    {
        return [
            'column' => $this->orderBy,
            'direction' => $this->orderDirection,
        ];
    }
}
