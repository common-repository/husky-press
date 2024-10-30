<?php

namespace HuskyPress;

class Errors
{
    /**
     * @var string
     */
    const SAVED_ERRORS_KEY = 'husky-press-errors';

    /**
     * @var string
     */
    const ERROR_ALREADY_SENT = 'husky-press-sent-errors';

    /**
     * Store errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Store sent errors.
     *
     * @var array
     */
    protected $sent_errors = [];

    /**
     * Errors Constructor.
     */
    public function __construct()
    {
        $this->get_stored_errors();
        $this->get_sent_errors();
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->errors;
    }

    /**
     * @return void
     */
    public function delete()
    {
        if (! empty($this->errors)) {
            delete_transient(self::SAVED_ERRORS_KEY);

            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @param array $data
     * @return void
     */
    public function set(string $key, array $data)
    {
        $this->errors[$key] = $data;

        set_transient(self::SAVED_ERRORS_KEY, $this->errors);
    }

    /**
     * @return boolean
     */
    public function has(string $key)
    {
        return array_key_exists($key, $this->errors);
    }

    /**
     * Mark error as sent.
     *
     * @param string $key
     *
     * @return void
     */
    public function mark_as_sent(string $key)
    {
        $this->sent_errors[] = $key;

        set_transient(self::ERROR_ALREADY_SENT, $this->sent_errors);
    }

    /** */
    public function already_sent(string $key)
    {
        return in_array($key, $this->sent_errors);
    }

    /**
     * @return void
     */
    protected function get_sent_errors()
    {
        $errors = get_transient(self::ERROR_ALREADY_SENT);

        $this->sent_errors = ! empty($errors) ? $errors : [];
    }

    /**
     * @return void
     */
    protected function get_stored_errors()
    {
        $errors = get_transient(self::SAVED_ERRORS_KEY);

        $this->errors = ! empty($errors) ? $errors : [];
    }

    /**
     * @return void
     */
    public function save()
    {
        update_option(self::SAVED_ERRORS_KEY, array_merge($this->get_saved(), $this->errors), false);
    }

    /**
     * @return array
     */
    public function get_saved()
    {
        if (is_array(get_option(self::SAVED_ERRORS_KEY))) {
            return get_option(self::SAVED_ERRORS_KEY);
        }

        return [];
    }

    /**
     * @param string $id
     * @return array
     */
    public function remove_saved($id)
    {
        $saved = $this->get_saved();

        if (isset($saved[$id])) {
            unset($saved[$id]);
        }

        update_option(self::SAVED_ERRORS_KEY, $saved, false);
    }
}