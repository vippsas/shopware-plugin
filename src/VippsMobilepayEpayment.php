<?php declare(strict_types=1);

namespace Vipps\Mobilepay;

use Shopware\Core\Checkout\Payment\PaymentMethodDefinition;
use Shopware\Core\Content\Media\Aggregate\MediaFolder\MediaFolderDefinition;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\ActivateContext;
use Shopware\Core\Framework\Plugin\Context\DeactivateContext;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\Framework\Plugin\Util\PluginIdProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Vipps\Mobilepay\Service\Payments\MobilepayPayment;
use Vipps\Mobilepay\Service\Payments\VippsPayment;
use Vipps\Mobilepay\Util\MediaInstaller;

class VippsMobilepayEpayment extends Plugin
{
    public const DEFAULT_PAYMENT_METHODS = [
        MobilepayPayment::VIPPS_MOBILEPAY_NAME => [
            'handler' => MobilepayPayment::class,
            'description' => MobilepayPayment::VIPPS_MOBILEPAY_DESCRIPTION,
            'technicalName' => MobilepayPayment::VIPPS_MOBILEPAY_TECHNICALNAME,
            'mediaFileName' => MobilepayPayment::VIPPS_MOBILEPAY_MEDIA_FILE_NAME
        ],
        VippsPayment::VIPPS_MOBILEPAY_NAME => [
            'handler' => VippsPayment::class,
            'description' => VippsPayment::VIPPS_MOBILEPAY_DESCRIPTION,
            'technicalName' => VippsPayment::VIPPS_MOBILEPAY_TECHNICALNAME,
            'mediaFileName' => VippsPayment::VIPPS_MOBILEPAY_MEDIA_FILE_NAME
        ],
    ];

    public function install(InstallContext $installContext): void
    {
        $this->addPaymentMethods(Context::createDefaultContext());
        foreach (self::DEFAULT_PAYMENT_METHODS as $props) {
            $paymentMethodId = $this->getPaymentMethodId($props['handler']);
            $mediaInstaller = new MediaInstaller(
                $this->getRepository($this->container, MediaDefinition::ENTITY_NAME),
                $this->getRepository($this->container, MediaFolderDefinition::ENTITY_NAME),
                $this->getRepository($this->container, PaymentMethodDefinition::ENTITY_NAME),
                $this->container->get(FileSaver::class)
            );

            $mediaInstaller->installPaymentMethodMedia(
                $props['mediaFileName'],
                $paymentMethodId,
                Context::createDefaultContext()
            );
        }
    }

    public function uninstall(UninstallContext $uninstallContext): void
    {
        foreach (self::DEFAULT_PAYMENT_METHODS as $props) {
            $paymentMethodId = $this->getPaymentMethodId($props['handler']);
            $this->setPaymentMethodIsActive(false, $uninstallContext->getContext(), $paymentMethodId);
        }
    }

    public function activate(ActivateContext $activateContext): void
    {
        foreach (self::DEFAULT_PAYMENT_METHODS as $props) {
            $paymentMethodId = $this->getPaymentMethodId($props['handler']);
            $this->setPaymentMethodIsActive(true, $activateContext->getContext(), $paymentMethodId);
        }
        parent::activate($activateContext);
    }

    public function deactivate(DeactivateContext $deactivateContext): void
    {
        foreach (self::DEFAULT_PAYMENT_METHODS as $props) {
            $paymentMethodId = $this->getPaymentMethodId($props['handler']);
            $this->setPaymentMethodIsActive(false, $deactivateContext->getContext(), $paymentMethodId);
        }
        parent::deactivate($deactivateContext);
    }

    private function getPaymentMethodId($identifier): ?string
    {
        /** @var EntityRepository $paymentRepository */
        $paymentRepository = $this->container->get('payment_method.repository');

        $paymentCriteria = (new Criteria())->addFilter(new EqualsFilter('handlerIdentifier', $identifier));
        $paymentId = $paymentRepository->searchIds($paymentCriteria, Context::createDefaultContext())->firstId();
        if (empty($paymentId)) {
            return null;
        }
        return $paymentId;
    }

    private function setPaymentMethodIsActive(bool $active, Context $context, $paymentMethodId): void
    {
        /** @var EntityRepository $paymentRepository */
        $paymentRepository = $this->container->get('payment_method.repository');

        if (!$paymentMethodId) {
            return;
        }
        $paymentMethod = [
            'id' => $paymentMethodId,
            'active' => $active,
        ];
        $paymentRepository->update([$paymentMethod], $context);
    }

    private function addPaymentMethods(Context $context): void
    {
        $paymentRepository = $this->container->get('payment_method.repository');
        $pluginIdProvider = $this->container->get(PluginIdProvider::class);
        $pluginId = $pluginIdProvider->getPluginIdByBaseClass(VippsMobilepayEpayment::class, $context);

        foreach (self::DEFAULT_PAYMENT_METHODS as $name => $props) {
            $paymentMethodExists = $this->getPaymentMethodId($props['handler']);

            if ($paymentMethodExists) {
                continue;
            }

            $paymentMethodData = [
                'handlerIdentifier' => $props['handler'],
                'name' => $name,
                'technicalName' => $props['technicalName'],
                'description' => $props['description'],
                'pluginId' => $pluginId,
                'active' => true
            ];
            $paymentRepository->upsert([$paymentMethodData], $context);
        }
    }

    private function getRepository(ContainerInterface $container, string $entityName): EntityRepository
    {
        $repository = $container->get(
            \sprintf('%s.repository', $entityName),
            ContainerInterface::NULL_ON_INVALID_REFERENCE
        );

        if (!$repository instanceof EntityRepository) {
            throw new ServiceNotFoundException(\sprintf('%s.repository', $entityName));
        }

        return $repository;
    }
}
