<?php

declare(strict_types=1);

final class PurchaseService
{
    public function __construct(
        private readonly Purchase $purchases,
        private readonly Package $packages,
    ) {
    }

    public function simulate(int $userId, int $packageId): array
    {
        $package = $this->packages->findActiveById($packageId);

        if ($package === null) {
            throw new RuntimeException('Pacote não encontrado ou indisponível.', 404);
        }

        return $this->purchases->create($userId, $packageId);
    }

    public function listByUser(int $userId): array
    {
        return $this->purchases->listByUser($userId);
    }

    public function totals(): array
    {
        return [
            'purchases' => $this->purchases->countAll(),
        ];
    }
}
