<?php

namespace HuskyPress;

defined( 'ABSPATH' ) || exit;

class Settings
{
    /**
     * Hold options data.
     *
     * @var array
     */
    private $keys = [];

    /**
     * Options holder.
     *
     * @var array
     */
    private $options = [];

    /**
     * Settings Constructor.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init']);

        $this->add_options('auth', 'husky-press-auth');
    }

    /**
     * Init.
     *
     * @return void
     */
	public function init()
    {
        $this->get_options();
	}

    /**
     * Add new option data.
     *
     * @param string $id
     * @param string $key
     * @return void
     */
    public function add_options($id, $key)
    {
        if (! empty($id) && ! empty($key)) {
            $this->keys[$id] = $key;

            if (! is_null($this->options)) {
                $options = get_option($key, []);
                $options = $this->normalize($options);

                $this->options[$id] = $options;
            }
        }
    }

    /**
     * Get setting.
     *
     * @param string $field_id
     * @param mixed $default
     * @return mixed
     */
    public function get($field_id = '', $default = false)
    {
        $options = $this->get_options();
        $ids = explode('.', $field_id);

        foreach ($ids as $id) {
            if (is_null($options)) {
                break;
            }

            $options = isset($options[$id]) ? $options[$id] : null;
        }

        if (is_null($options)) {
            return $default;
        }

        return $options;
    }

	/**
	 * Set value.
	 *
	 * @param string $group
	 * @param string $id
	 * @param string $value
     * @return void
	 */
	public function set($group, $id, $value)
    {
		$this->options[$group][$id] = $value;
	}

    /**
     * Get all settings.
     *
     * @return array
     */
    public function all()
    {
        return $this->get_options();
    }

    /**
     * Save all settings.
     *
     * @return void
     */
    public function save()
    {
        foreach ($this->keys as $id => $key) {
            update_option($key, $this->get($id));
        }
    }

	/**
	 * Get settings keys.
	 *
	 * @return array
	 */
	public function get_keys()
    {
		return $this->keys;
	}

    /**
     * Get options once.
     *
     * @return array
     */
    private function get_options()
    {
        if (is_null($this->options) && ! empty($this->keys)) {
            $options = [];

            foreach ($this->keys as $id => $key) {
                $options[$id] = get_option($key, []);
            }

            $this->options = $this->normalize($options);
        }

        return (array) $this->options;
    }

    /**
     * Normalize option data.
     *
     * @param array $options
     * @return mixed
     */
    private function normalize($options)
    {
        foreach ((array) $options as $key => $value) {
            $options[$key] = is_array($value) ? $this->normalize($value) : $this->normalize_value($value);
        }

        return $options;
    }

    /**
     * Normalize option value.
     *
     * @param mixed $value
     */
    private function normalize_value($value)
    {
        if ($value === 'true' || $value === 'on') {
            $value = true;
        } elseif ($value === 'false' || $value === 'off') {
            $value = false;
        } elseif ($value === '0' || $value === '1') {
            $value = intval($value);
        }

        return $value;
    }
}