<?php

declare(strict_types=1);

final class AuthService
{
    public function __construct(private readonly User $users)
    {
    }

    public function login(string $email, string $password): array
    {
        $email = strtolower(trim($email));
        $user = $this->users->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            throw new InvalidArgumentException('E-mail ou senha inválidos.');
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];

        return $_SESSION['user'];
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
        }

        session_destroy();
    }

    public function requireAuthenticated(): array
    {
        if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
            redirect(app_url('/frontend/pages/login.php'));
        }

        return $_SESSION['user'];
    }

    public function requireRole(string $role): array
    {
        $user = $this->requireAuthenticated();

        if (($user['role'] ?? null) !== $role) {
            http_response_code(403);
            exit('Acesso não autorizado.');
        }

        return $user;
    }
}
