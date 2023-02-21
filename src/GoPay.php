<?php

namespace Kasir\Kasir;

use Illuminate\Support\Arr;
use Kasir\Kasir\Exceptions\MidtransApiException;
use Kasir\Kasir\Helper\Request;
use Spatie\Macroable\Macroable;
use Str;

class GoPay
{
    use Macroable {
        __call as macroCall;
    }

    protected string | null $account_id = null;

    protected string | null $account_status = null;

    protected array | null $payment_options = null;

    protected array | null $actions = null;

    public function __construct(string | null $account_id)
    {
        $this->account_id = $account_id;
    }

    public static function make(string | null $account_id): static
    {
        return app(static::class, [
            'account_id' => $account_id,
        ]);
    }

    public function accountId(): string | null
    {
        return $this->account_id;
    }

    public function accountStatus(): string
    {
        return $this->account_status;
    }

    public function paymentOptions(): array | null
    {
        return $this->payment_options;
    }

    public function actions(): array | null
    {
        return $this->actions;
    }

    public function action(string $name): array | null
    {
        return $this->actions()[$name] ?? null;
    }

    public function wallet()
    {
        return $this->paymentOption('wallet');
    }

    public function paylater()
    {
        return $this->paymentOption('paylater');
    }

    public static function bind(string $phone_number, string $callback_url, string | int | null $country_code = 62)
    {
        $country_code = (string) $country_code;

        if (Str::start($phone_number, 0)) {
            $phone_number = Str::replaceFirst('0', '', $phone_number);
        } elseif (Str::start($phone_number, $country_code)) {
            $phone_number = Str::replaceFirst($country_code, '', $phone_number);
        } elseif (Str::start($phone_number, '+' . $country_code)) {
            $phone_number = Str::replaceFirst('+' . $country_code, '', $phone_number);
        }

        $params = [
            'payment_type' => 'gopay',
            'gopay_partner' => compact('phone_number', 'country_code', 'callback_url'),
        ];

        $response = Request::post(
            Kasir::getBaseUrl() . '/v2/pay/account',
            config('kasir.server_key'),
            $params
        );

        $static = static::make($response->json('account_id'));
        $static->account_status = $response->json('account_status');
        $static->payment_options = Arr::keyBy($response->json('metadata.payment_options'), 'name');
        $static->actions = Arr::keyBy($response->json('actions'), 'name');

        return $static;
    }

    public function status()
    {
        $response = Request::get(
            Kasir::getBaseUrl() . '/v2/pay/account/' . $this->accountId(),
            config('kasir.server_key')
        );

        $static = static::make($response->json('account_id'));
        $static->account_status = $response->json('account_status');
        $static->payment_options = Arr::keyBy($response->json('metadata.payment_options'), 'name');
        $static->actions = Arr::keyBy($response->json('actions'), 'name');

        return $static;
    }

    public function unbind()
    {
        $response = Request::post(
            Kasir::getBaseUrl() . '/v2/pay/account/' . $this->accountId() . '/unbind',
            config('kasir.server_key')
        );

        if ($response->failed()) {
            throw new MidtransApiException($response->json('status_message'), $response->json('status_code'));
        }

        $static = static::make($response->json('account_id'));
        $static->account_status = $response->json('account_status');

        return $static;
    }

    public function paymentOption(string $option): array | null
    {
        $alias = [
            'wallet' => 'GOPAY_WALLET',
            'paylater' => 'PAY_LATER',
        ];

        if (! $this->paymentOptions()) {
            $this->payment_options = $this->status()->paymentOptions();
        }

        return $this->paymentOptions()[$alias[$option]] ?? null;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->macroCall($name, $arguments);
    }
}
