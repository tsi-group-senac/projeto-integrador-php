<?php

declare(strict_types=1);

final class AdminController
{
    public function __construct(
        private readonly AdminService $admin,
        private readonly AuthService $auth,
    ) {
    }

    public function requireAdmin(): array
    {
        return $this->auth->requireRole('admin');
    }

    public function dashboard(): array
    {
        $this->requireAdmin();

        return $this->admin->dashboardTotals();
    }

    public function customers(): array
    {
        $this->requireAdmin();

        return $this->admin->listCustomers();
    }

    /**
     * @return array{items: list<array>, meta: array{page: int, per_page: int, total: int, pages: int, offset: int}}
     */
    public function customersPage(?int $page = null, int $perPage = 10): array
    {
        $this->requireAdmin();

        return $this->admin->listCustomersPage($page, $perPage);
    }

    public function reservations(): array
    {
        $this->requireAdmin();

        return $this->admin->listReservations();
    }

    /**
     * @return array{items: list<array>, meta: array{page: int, per_page: int, total: int, pages: int, offset: int}}
     */
    public function reservationsPage(?int $page = null, int $perPage = 10): array
    {
        $this->requireAdmin();

        return $this->admin->listReservationsPage($page, $perPage);
    }
}
