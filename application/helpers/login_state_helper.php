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