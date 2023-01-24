<?php

namespace Kasir\Kasir\Helper;

class MidtransResponse extends Response
{
    /**
     * Get response actions.
     *
     * @return mixed
     */
    public function actions(): mixed
    {
        return $this->json('actions');
    }

    /**
     * Get response action names.
     *
     * @return array|null
     */
    public function actionsName(): array | null
    {
        return $this->actions() ? array_column($this->actions(), 'name') : null;
    }

    /**
     * Get response action by name.
     *
     * @param  string  $name
     * @return mixed|null
     */
    public function action($name): mixed
    {
        if (! $this->actions()) {
            return null;
        }

        $array = array_filter($this->actions(), function ($action) use ($name) {
            return $action['name'] === $name;
        });

        $result = call_user_func_array('array_merge', $array);

        return ! empty($result) ? $result : null;
    }
}
