<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-2-2017
 */

if (! function_exists('isLoggedIn')) {
    /**
     * Checks if a user is currently logged in.
     *
     * @param CI_Session $session
     * @return bool True if a user is logged in, else false.
     */
    function isLoggedIn($session) {
        return $session->loginId !== null;
    }
}

if (! function_exists('getLoggedInLoginId')) {
    /**
     * Returns the login ID of the currently active user.
     *
     * @param CI_Session $session
     * @return int The ID of the currently logged in user.
     * @throws Exception If the user is not logged in.
     */
    function getLoggedInLoginId($session) {
        if (!isLoggedIn($session)) {
            throw new Exception('Tried to access user login id while not logged in.');
        }

        return $session->loginId;
    }
}

if (! function_exists('setLoggedIn')) {
    /**
     * Sets the ID of the currently logged in user.
     *
     * @param CI_Session $session
     * @param int $loginId
     */
    function setLoggedIn($session, $loginId) {
        $session->loginId = $loginId;
    }
}

if (! function_exists('setLoggedOut')) {
    /**
     * Log the current user out.
     *
     * @param CI_Session $session
     */
    function setLoggedOut($session) {
        $session->loginId = null;
    }
}

if (! function_exists('isLoggedInAndHasRole')) {
    /**
     * Checks if the user is logged in and has at least one of a set of roles.
     *
     * @param CI_Controller $ci
     * @param string|array $roles
     * @return bool True if a user is logged in and has (one of the) roles, else false.
     * @internal param CI_Session $session
     * @internal param LoginRole $loginRole
     */
    function isLoggedInAndHasRole($ci, $roles) {
        $ci->load->model(Role_LoginRole::class);

        if (!isLoggedIn($ci->session)) {
            return false;
        }

        if (is_array($roles)) {
            // Check for each of the roles if the user has this role.
            foreach ($roles as $role) {
                $hasRole = $ci->Role_LoginRole->userHasRole(getLoggedInLoginId($ci->session), $role);
                if ($hasRole) {
                    return true;
                }
            }

            return false; // Return false if the user has non of the roles.
        } else {
            return $ci->Role_LoginRole->userHasRole(getLoggedInLoginId($ci->session), $roles);
        }
    }
}