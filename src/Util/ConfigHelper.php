<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Util;

use Shopware\Core\System\SystemConfig\SystemConfigService;

class ConfigHelper
{
    final public const CONFIG_PATH = 'VippsMobilepayEpayment.config';
    final public const API_URL = 'apiUrl';
    final public const MSN = 'vippsMobilepayMSN';
    final public const CLIENT_ID = 'vippsMobilepayClientId';
    final public const CLIENT_SECRET = 'vippsMobilepayClientSecret';
    final public const OCP_APIM_KEY_PRIMARY = 'vippsMobilepayOcpApimSubscriptionKeyPrimary';
    final public const OCP_APIM_KEY_SECONDARY = 'vippsMobilepayOcpApimSubscriptionKeySecondary';
    final public const ACCESS_TOKEN = "accessToken";
    final public const TOKEN_EXPIRE = "expire";
    final public const DEBUG = "debug";

    public function __construct(protected SystemConfigService $systemConfigService)
    {
    }

    protected function getConfig(string $path, ?string $salesChannelId = null): array|bool|float|int|string|null
    {
        return $this->systemConfigService->get(
            sprintf(
                '%s.%s',
                self::CONFIG_PATH,
                $path
            ),
            $salesChannelId
        );
    }

    protected function setConfig(string $path, ?string $value, ?string $salesChannelId = null): void
    {
        $this->systemConfigService->set(
            sprintf(
                '%s.%s',
                self::CONFIG_PATH,
                $path
            ),
            $value,
            $salesChannelId
        );
    }

    public function getApiUrl(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::API_URL, $salesChannelId);
    }

    public function getMSN(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::MSN, $salesChannelId);
    }

    public function getClientId(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::CLIENT_ID, $salesChannelId);
    }

    public function getClientSecret(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::CLIENT_SECRET, $salesChannelId);
    }

    public function getOCPPrimary(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::OCP_APIM_KEY_PRIMARY, $salesChannelId);
    }

    public function getOCPSecondary(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::OCP_APIM_KEY_SECONDARY, $salesChannelId);
    }

    public function getAccessToken(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::ACCESS_TOKEN, $salesChannelId);
    }

    public function setAccessToken(?string $value = null, ?string $salesChannelId = null): void
    {
        $this->setConfig(self::ACCESS_TOKEN, $value, $salesChannelId);
    }

    public function getTokenExpire(?string $salesChannelId = null): ?string
    {
        return $this->getConfig(self::TOKEN_EXPIRE, $salesChannelId);
    }

    public function setTokenExpire(?string $value = null, ?string $salesChannelId = null): void
    {
        $this->setConfig(self::TOKEN_EXPIRE, $value, $salesChannelId);
    }

    public function getDebug(?string $salesChannelId = null): ?bool
    {
        return $this->getConfig(self::DEBUG, $salesChannelId);
    }
}
