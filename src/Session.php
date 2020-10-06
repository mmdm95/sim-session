<?php

namespace Sim\Session;

use Sim\Crypt\ICrypt;
use Sim\Session\Utils\ArrayUtil;

class Session implements ISession
{
    /**
     * @var ICrypt|null $crypt
     */
    protected $crypt = null;

    /**
     * Session flash data identifier
     * @var $flash_prefix string
     */
    protected $flash_prefix = '__simplicity_flash_data';

    protected $timed_prefix = '__simplicity_timed_data';

    /**
     * Session constructor.
     * @param ICrypt|null $crypt
     */
    public function __construct(?ICrypt $crypt = null)
    {
        $this->crypt = $crypt;
        $this->start();
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value, bool $encrypt = true): ISession
    {
        if ($this->hasStart()) {
            ArrayUtil::set($_SESSION, $key, $this->prepareSetSessionValue($value, $encrypt));
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key = null, $prefer = null)
    {
        // To key specific session
        if ($this->hasStart()) {
            if (!is_null($key)) {
                if ($this->has($key)) {
                    return $this->prepareGetSessionValue(ArrayUtil::get($_SESSION, $key));
                }
            } else {
                // To get all sessions
                $sessions = [];
                foreach ($_SESSION as $k => $value) {
                    if ($this->flash_prefix != $k && $this->timed_prefix != $k) {
                        $sessions[$k] = $this->prepareGetSessionValue($value);
                    }
                }
                return $sessions;
            }
        }
        return $prefer;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $key): ISession
    {
        if ($this->hasStart()) {
            ArrayUtil::remove($_SESSION, $key);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $key): bool
    {
        if ($this->hasStart()) {
            return ArrayUtil::has($_SESSION, $key);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimed(string $key, $value, $time = 300, bool $encrypt = true): ISession
    {
        if ($this->hasStart()) {
            ArrayUtil::set($_SESSION, $this->_dotConcatenation($this->timed_prefix, $key), $this->prepareSetSessionValue($value, $encrypt, time() + $time, true));
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimed(string $key = null, $prefer = null)
    {
        if ($this->hasStart()) {
            if (!is_null($key)) {
                if ($this->hasTimed($key)) {
                    $timedSess = $this->prepareGetSessionValue(ArrayUtil::get($_SESSION, $this->_dotConcatenation($this->timed_prefix, $key)));
                    return $timedSess;
                }
            } else {
                $timed = $_SESSION[$this->timed_prefix] ?? [];
                foreach ($timed as $key => $value) {
                    $timed[$key] = $this->prepareGetSessionValue($value);
                }
                return $timed;
            }
        }
        return $prefer;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTimed(?string $key): ISession
    {
        if ($this->hasStart()) {
            if (!is_null($key)) {
                ArrayUtil::remove($_SESSION, $this->_dotConcatenation($this->timed_prefix, $key));
            } else {
                foreach ($_SESSION[$this->timed_prefix] as $k => $v) {
                    $this->removeTimed($k);
                }
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTimed(string $key): bool
    {
        if ($this->hasStart()) {
            $has = ArrayUtil::has($_SESSION, $this->_dotConcatenation($this->timed_prefix, $key));
            if ($has) {
                $timedSess = $this->prepareGetSessionValue(ArrayUtil::get($_SESSION, $this->_dotConcatenation($this->timed_prefix, $key)));
                if (is_null($timedSess)) {
                    $this->removeTimed($key);
                    return false;
                }
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setFlash(string $key, $value, bool $encrypt = true): ISession
    {
        if ($this->hasStart()) {
            ArrayUtil::set($_SESSION, $this->_dotConcatenation($this->flash_prefix, $key), $this->prepareSetSessionValue($value, $encrypt));
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFlash(string $key = null, $prefer = null, $delete = true)
    {
        if ($this->hasStart()) {
            if (!is_null($key)) {
                if ($this->hasFlash($key)) {
                    $flashSess = $this->prepareGetSessionValue(ArrayUtil::get($_SESSION, $this->_dotConcatenation($this->flash_prefix, $key)));
                    if (true == (bool)$delete) {
                        $this->removeFlash($key);
                    }
                    return $flashSess;
                }
            } else {
                $flashes = $_SESSION[$this->flash_prefix] ?? [];
                foreach ($flashes as $key => $value) {
                    $flashes[$key] = $this->prepareGetSessionValue($value);
                    if (true == (bool)$delete) {
                        $this->removeFlash($key);
                    }
                }
                if ((bool)$delete) {
                    unset($_SESSION[$this->flash_prefix]);
                }
                return $flashes;
            }
        }
        return $prefer;
    }

    /**
     * {@inheritdoc}
     */
    public function removeFlash(?string $key = null): ISession
    {
        if ($this->hasStart()) {
            if (!is_null($key)) {
                ArrayUtil::remove($_SESSION, $this->_dotConcatenation($this->flash_prefix, $key));
            } else {
                foreach ($_SESSION[$this->flash_prefix] as $k => $v) {
                    $this->removeFlash($k);
                }
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFlash(string $key): bool
    {
        if ($this->hasStart()) {
            return ArrayUtil::has($_SESSION, $this->_dotConcatenation($this->flash_prefix, $key));
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function start(bool $regenerate = false, bool $delete_old_session = false): ISession
    {
        if (!$this->hasStart()) {
            session_start();
        }
        if ($regenerate) {
            session_regenerate_id($delete_old_session);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function close(): ISession
    {
        if ($this->hasStart()) {
            session_unset();
            session_destroy();
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasStart()
    {
        if (session_id()) {
            return session_id();
        }
        return false;
    }


    /**
     * Prepare session value to store (check if encryption need)
     *
     * @param $value
     * @param bool $encrypt
     * @param int $ttl
     * @param bool $timed
     * @return mixed
     */
    protected function prepareSetSessionValue($value, bool $encrypt, $ttl = PHP_INT_MAX, $timed = false)
    {
        if (is_string($value)) {
            $value = htmlspecialchars($value);
        }

        $val = json_encode([
            'data' => $value,
            'is_timed' => $timed,
            'ttl' => $ttl <= PHP_INT_MAX ? $ttl : PHP_INT_MAX,
        ]);
        if (!is_null($this->crypt) && $encrypt) {
            $val = $this->crypt->encrypt($val);
            $val = $this->crypt->hasError() ? "" : $val;
        }

        return json_encode([
            'simplicity_data' => $val,
            'simplicity_is_encrypted' => $encrypt,
        ]);
    }

    /**
     * Prepare session value to retrieve (check if decryption need)
     *
     * @param $theArray
     * @return mixed
     */
    protected function prepareGetSessionValue($theArray)
    {
        if (is_array($theArray)) {
            foreach ($theArray as $k => $v) {
                $theArray[$k] = $this->prepareGetSessionValue($v);
            }
        } elseif (is_string($theArray)) {
            $arr = json_decode($theArray, true);
            if (is_null($arr) || !isset($arr['simplicity_data']) || !isset($arr['simplicity_is_encrypted'])) return $theArray;

            $arr2 = $arr['simplicity_data'];
            if (!is_null($this->crypt) && $arr['simplicity_is_encrypted']) {
                $arr2 = $this->crypt->decrypt($arr2);
                if ($this->crypt->hasError()) {
                    return null;
                }
            }
            $arr2 = json_decode($arr2, true);
            if ($arr2['is_timed']) {
                if ($arr2['ttl'] < time()) return null;
            }
            $value = $arr2['data'];
            if (is_string($value)) {
                $value = htmlspecialchars_decode($value);
            }
            return $value;
        }
        return $theArray;
    }

    /**
     * Concat two strings with dot
     *
     * @param $str1
     * @param $str2
     * @return string
     */
    private function _dotConcatenation($str1, $str2)
    {
        return $str1 . '.' . $str2;
    }
}