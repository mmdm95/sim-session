<?php

namespace Sim\Session;


interface ISession
{
    /**
     * Set a session data
     *
     * @param string $key
     * @param $value
     * @param bool $encrypt
     * @return ISession
     */
    public function set(string $key, $value, bool $encrypt = true): ISession;

    /**
     * Get a/all session/sessions
     * Note: To get all sessions, do not send any parameter to method
     *
     * @param string|null $key
     * @param mixed $prefer
     * @return mixed - If $key is not null search for $key if not exists, returns $prefer, if
     * $key is null return all items in session
     */
    public function get(string $key = null, $prefer = null);

    /**
     * Unset a session data
     *
     * @param string $key
     * @return ISession
     */
    public function remove(string $key): ISession;

    /**
     * Check that a session is set or not
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Set a timed session
     *
     * @param string $key
     * @param $value
     * @param int $time in seconds - default is 300s => 5min
     * @param bool $encrypt
     * @return ISession
     */
    public function setTimed(string $key, $value, $time = 300, bool $encrypt = true): ISession;

    /**
     * Get a timed session
     * Note: To get all timed sessions, do not send any parameter to method
     *
     * @param string|null $key
     * @param mixed $prefer
     * @return mixed - If $key is not null search for $key if not exists, returns $prefer, if
     * $key is null return all items in timed session
     */
    public function getTimed(string $key = null, $prefer = null);

    /**
     * Remove a timed session
     * Note: To remove all timed sessions, do not send any parameter to method
     *
     * @param string $key
     * @return ISession
     */
    public function removeTimed(?string $key): ISession;

    /**
     * Check if has specific timed session
     *
     * @param string $key
     * @return bool
     */
    public function hasTimed(string $key): bool;

    /**
     * Set a flash session data
     *
     * @param string $key
     * @param $value
     * @param bool $encrypt
     * @return ISession
     */
    public function setFlash(string $key, $value, bool $encrypt = true): ISession;

    /**
     * Get a flash session data
     * Note: To get all flash sessions, do not send any parameter to method
     *
     * @param string|null $key
     * @param null $prefer
     * @param bool $delete
     * @return mixed - If $key is not null search for $key if not exists, returns $prefer, if
     * $key is null return all items in flash session
     */
    public function getFlash(string $key = null, $prefer = null, $delete = true);

    /**
     * Unset a session flash data
     * Note: To remove all flash sessions, do not send any parameter to method
     *
     * @param string|null $key
     * @return ISession
     */
    public function removeFlash(?string $key = null): ISession;

    /**
     * Check if a flash session is set or not
     *
     * @param string $key
     * @return bool
     */
    public function hasFlash(string $key): bool;

    /**
     * Start/Restart a session
     *
     * @param bool $regenerate
     * @param bool $delete_old_session
     * @return ISession
     */
    public function start(bool $regenerate = false, bool $delete_old_session = false): ISession;

    /**
     * Destroy a started session
     *
     * @return ISession
     */
    public function close(): ISession;

    /**
     * Check if session is start
     *
     * @return bool|int If session was started, return session id otherwise return false
     */
    public function hasStart();
}