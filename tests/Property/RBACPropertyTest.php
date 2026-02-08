<?php

namespace Tests\Property;

use App\Http\Middleware\DashboardRoleMiddleware;
use stdClass;
use Tests\TestCase;

/**
 * Property-Based Tests for Role-Based Access Control
 *
 * These tests verify the correctness properties of the RBAC middleware
 * by running multiple iterations with randomly generated test data.
 *
 * Uses mock user objects to test middleware logic without database dependencies.
 */
class RBACPropertyTest extends TestCase
{
    protected DashboardRoleMiddleware $middleware;

    /**
     * Available dashboard roles for testing
     */
    protected array $availableRoles = [
        'admin',
        'it',
        'hr',
        'cs',
        'finance',
        'delivery_supervisor',
        'store_owner',
        'accountant',
    ];

    /**
     * Role to flag mapping
     */
    protected array $roleFlags = [
        'admin' => 'is_admin',
        'it' => 'is_it',
        'hr' => 'is_hr',
        'cs' => 'is_cs',
        'finance' => 'is_finance',
        'accountant' => 'is_accountant',
        'delivery_supervisor' => 'is_driver_supervisor',
        'store_owner' => 'is_trader',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new DashboardRoleMiddleware;
    }

    /**
     * Create a mock user object with specific roles
     * Uses stdClass to avoid database dependencies
     */
    protected function createMockUserWithRoles(array $roles): object
    {
        $user = new stdClass;
        $user->id = rand(1, 10000);
        $user->role = null;

        foreach ($this->roleFlags as $role => $flag) {
            $user->$flag = in_array($role, $roles);
        }

        return $user;
    }

    /**
     * Create a mock user without any dashboard roles
     */
    protected function createMockUserWithoutRoles(): object
    {
        $user = new stdClass;
        $user->id = rand(1, 10000);
        $user->role = null;

        foreach ($this->roleFlags as $role => $flag) {
            $user->$flag = false;
        }

        return $user;
    }

    /**
     * Get a random subset of roles (excluding a specific role)
     */
    protected function getRandomRolesExcluding(string $excludeRole): array
    {
        $roles = array_filter($this->availableRoles, fn ($r) => $r !== $excludeRole);
        $roles = array_values($roles); // Re-index array
        $count = rand(1, count($roles));
        shuffle($roles);

        return array_slice($roles, 0, $count);
    }

    /**
     * Get a random role
     */
    protected function getRandomRole(): string
    {
        return $this->availableRoles[array_rand($this->availableRoles)];
    }

    /**
     * Test if user would have access using middleware's public methods
     * Tests the core logic without HTTP request simulation
     */
    protected function checkAccess(object $user, array $requiredRoles): bool
    {
        // Admin override check
        if ($this->middleware->isAdmin($user)) {
            return true;
        }

        // Check if user has any of the required roles
        return $this->middleware->hasAnyRole($user, $requiredRoles);
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 1: Role-Based Access Control Enforcement**
     * **Validates: Requirements 2.1, 2.2**
     *
     * *For any* user without the required role and *for any* protected dashboard route,
     * the middleware SHALL deny access and return HTTP 403 status.
     *
     * @test
     */
    public function property_rbac_enforcement_denies_users_without_required_role(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Pick a random required role (excluding admin to test denial)
            $nonAdminRoles = array_filter($this->availableRoles, fn ($r) => $r !== 'admin');
            $nonAdminRoles = array_values($nonAdminRoles);
            $requiredRole = $nonAdminRoles[array_rand($nonAdminRoles)];

            // Create a user with roles that do NOT include the required role AND not admin
            $possibleUserRoles = array_filter($this->availableRoles, fn ($r) => $r !== $requiredRole && $r !== 'admin');
            $possibleUserRoles = array_values($possibleUserRoles);

            // Pick 0-2 random roles from possible roles
            $numRoles = rand(0, min(2, count($possibleUserRoles)));
            shuffle($possibleUserRoles);
            $userRoles = array_slice($possibleUserRoles, 0, $numRoles);

            // Create user with these roles (or no roles)
            if (empty($userRoles)) {
                $user = $this->createMockUserWithoutRoles();
            } else {
                $user = $this->createMockUserWithRoles($userRoles);
            }

            // Check access
            $accessGranted = $this->checkAccess($user, [$requiredRole]);

            // Assert: User without required role should be denied
            $this->assertFalse(
                $accessGranted,
                "Iteration $i: User with roles [".implode(', ', $userRoles).
                "] should NOT have access to route requiring [$requiredRole]"
            );
        }
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 2: Multi-Role Access Grant**
     * **Validates: Requirements 2.3**
     *
     * *For any* user with multiple roles, the user SHALL have access to all dashboards
     * corresponding to each of their assigned roles.
     *
     * @test
     */
    public function property_multi_role_access_grants_access_to_all_assigned_roles(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Pick random number of roles (2-4) for the user (excluding admin for cleaner test)
            $nonAdminRoles = array_filter($this->availableRoles, fn ($r) => $r !== 'admin');
            $nonAdminRoles = array_values($nonAdminRoles);

            $numRoles = rand(2, min(4, count($nonAdminRoles)));
            shuffle($nonAdminRoles);
            $userRoles = array_slice($nonAdminRoles, 0, $numRoles);

            // Create user with these roles
            $user = $this->createMockUserWithRoles($userRoles);

            // Test access to each of the user's roles
            foreach ($userRoles as $role) {
                $accessGranted = $this->checkAccess($user, [$role]);

                $this->assertTrue(
                    $accessGranted,
                    "Iteration $i: User with roles [".implode(', ', $userRoles).
                    "] should have access to route requiring [$role]"
                );
            }
        }
    }

    /**
     * **Feature: dashboard-system-rebuild, Property 4: Admin Full Access Override**
     * **Validates: Requirements 2.5**
     *
     * *For any* user with admin role and *for any* dashboard route,
     * access SHALL be granted regardless of other role requirements.
     *
     * @test
     */
    public function property_admin_override_grants_access_to_all_routes(): void
    {
        // Run 100 iterations with random data
        for ($i = 0; $i < 100; $i++) {
            // Create an admin user (may or may not have other roles)
            $additionalRoles = [];
            if (rand(0, 1)) {
                // Sometimes add additional roles
                $nonAdminRoles = array_filter($this->availableRoles, fn ($r) => $r !== 'admin');
                $nonAdminRoles = array_values($nonAdminRoles);
                $numAdditional = rand(0, 3);
                shuffle($nonAdminRoles);
                $additionalRoles = array_slice($nonAdminRoles, 0, $numAdditional);
            }

            $userRoles = array_merge(['admin'], $additionalRoles);
            $user = $this->createMockUserWithRoles($userRoles);

            // Pick a random required role (could be any role)
            $requiredRole = $this->getRandomRole();

            // Check access
            $accessGranted = $this->checkAccess($user, [$requiredRole]);

            // Assert: Admin should always have access
            $this->assertTrue(
                $accessGranted,
                "Iteration $i: Admin user should have access to route requiring [$requiredRole]"
            );
        }
    }

    /**
     * Additional test: Verify OR logic for multiple required roles
     *
     * @test
     */
    public function property_or_logic_grants_access_when_user_has_any_required_role(): void
    {
        // Run 100 iterations
        for ($i = 0; $i < 100; $i++) {
            // Pick 2-3 required roles for the route (excluding admin)
            $nonAdminRoles = array_filter($this->availableRoles, fn ($r) => $r !== 'admin');
            $nonAdminRoles = array_values($nonAdminRoles);

            $numRequired = rand(2, 3);
            shuffle($nonAdminRoles);
            $requiredRoles = array_slice($nonAdminRoles, 0, $numRequired);

            // Create user with exactly ONE of the required roles
            $userRole = $requiredRoles[array_rand($requiredRoles)];
            $user = $this->createMockUserWithRoles([$userRole]);

            // Check access with multiple required roles
            $accessGranted = $this->checkAccess($user, $requiredRoles);

            // Assert: User with any one of the required roles should have access
            $this->assertTrue(
                $accessGranted,
                "Iteration $i: User with role [$userRole] should have access to route requiring [".
                implode(', ', $requiredRoles).'] (OR logic)'
            );
        }
    }
}
