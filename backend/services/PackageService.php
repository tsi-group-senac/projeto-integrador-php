<?php

declare(strict_types=1);

final class PackageService
{
    public function __construct(
        private readonly Package $packages,
        private readonly Destination $destinations,
        private readonly Purchase $purchases,
    ) {
    }

    public function listActive(): array
    {
        return $this->packages->findActiveWithDestinations();
    }

    public function listAll(): array
    {
        return $this->packages->findAllWithDestinations();
    }

    public function findById(int $id): array
    {
        $package = $this->packages->findById($id);

        if ($package === null) {
            throw new RuntimeException('Pacote não encontrado.', 404);
        }

        return $package;
    }

    public function findActiveById(int $id): array
    {
        $package = $this->packages->findActiveById($id);

        if ($package === null) {
            throw new RuntimeException('Pacote não encontrado.', 404);
        }

        return $package;
    }

    /**
     * @param array<string, mixed> $input
     * @return array{package: array, errors: array<string, string>}
     */
    public function validateInput(array $input): array
    {
        $errors = [];

        $destinationId = filter_var($input['destination_id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        $title = trim((string) ($input['title'] ?? ''));
        $summary = trim((string) ($input['summary'] ?? ''));
        $description = trim((string) ($input['description'] ?? ''));
        $durationDays = filter_var($input['duration_days'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 365],
        ]);
        $priceRaw = str_replace(',', '.', trim((string) ($input['price'] ?? '')));
        $isActive = isset($input['is_active']) && (string) $input['is_active'] === '1';

        if ($destinationId === false || $destinationId === null) {
            $errors['destination_id'] = 'Selecione um destino válido.';
        } elseif ($this->destinations->findById($destinationId) === null) {
            $errors['destination_id'] = 'Destino inexistente.';
        }

        if (mb_strlen($title) < 3 || mb_strlen($title) > 160) {
            $errors['title'] = 'Informe um título entre 3 e 160 caracteres.';
        }

        if ($summary === '' || mb_strlen($summary) > 255) {
            $errors['summary'] = 'Informe um resumo de até 255 caracteres.';
        }

        if ($description === '') {
            $errors['description'] = 'Informe a descrição do roteiro.';
        }

        if ($durationDays === false || $durationDays === null) {
            $errors['duration_days'] = 'Informe a duração entre 1 e 365 dias.';
        }

        if ($priceRaw === '' || !is_numeric($priceRaw) || (float) $priceRaw < 0) {
            $errors['price'] = 'Informe um preço zero ou positivo.';
        }

        $package = [
            'destination_id' => $destinationId === false ? 0 : (int) $destinationId,
            'title' => $title,
            'summary' => $summary,
            'description' => $description,
            'duration_days' => $durationDays === false ? 0 : (int) $durationDays,
            'price' => is_numeric($priceRaw) ? number_format((float) $priceRaw, 2, '.', '') : '0.00',
            'is_active' => $isActive,
        ];

        return ['package' => $package, 'errors' => $errors];
    }

    public function create(array $input, int $actorId): array
    {
        unset($actorId);
        $validated = $this->validateInput($input);

        if ($validated['errors'] !== []) {
            throw new InvalidArgumentException(json_encode($validated['errors'], JSON_UNESCAPED_UNICODE));
        }

        return $this->packages->create($validated['package']);
    }

    public function update(int $id, array $input, int $actorId): array
    {
        unset($actorId);
        $this->findById($id);
        $validated = $this->validateInput($input);

        if ($validated['errors'] !== []) {
            throw new InvalidArgumentException(json_encode($validated['errors'], JSON_UNESCAPED_UNICODE));
        }

        return $this->packages->update($id, $validated['package']);
    }

    public function delete(int $id, int $actorId): void
    {
        unset($actorId);
        $this->findById($id);

        if ($this->purchases->countByPackage($id) > 0) {
            $this->packages->deactivate($id);

            return;
        }

        try {
            $this->packages->hardDelete($id);
        } catch (PDOException $exception) {
            error_log('Falha ao excluir pacote, desativando: ' . $exception->getMessage());
            $this->packages->deactivate($id);
        }
    }
}
