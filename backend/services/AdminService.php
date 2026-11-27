<?php

declare(strict_types=1);

final class AdminService
{
    public function __construct(
        private readonly User $users,
        private readonly Purchase $purchases,
    ) {
    }

    public function dashboardTotals(): array
    {
        return [
            'customers' => $this->users->countCustomers(),
            'purchases' => $this->purchases->countAll(),
        ];
    }

    public function listCustomers(): array
    {
        return $this->users->listCustomersWithPurchaseCounts();
    }

    /**
     * @return array{items: list<array>, meta: array{page: int, per_page: int, total: int, pages: int, offset: int}}
     */
    public function listCustomersPage(?int $page = null, int $perPage = 10): array
    {
        $meta = pagination_meta($this->users->countCustomers(), $page, $perPage);

        return [
            'items' => $this->users->listCustomersWithPurchaseCountsPage($meta['per_page'], $meta['offset']),
            'meta' => $meta,
        ];
    }

    public function listReservations(): array
    {
        return $this->purchases->listAllWithDetails();
    }

    /**
     * @return array{items: list<array>, meta: array{page: int, per_page: int, total: int, pages: int, offset: int}}
     */
    public function listReservationsPage(?int $page = null, int $perPage = 10): array
    {
        $meta = pagination_meta($this->purchases->countAll(), $page, $perPage);

        return [
            'items' => $this->purchases->listAllWithDetailsPage($meta['per_page'], $meta['offset']),
            'meta' => $meta,
        ];
    }
}
