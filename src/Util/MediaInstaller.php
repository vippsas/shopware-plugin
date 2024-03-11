<?php declare(strict_types=1);

namespace Vipps\Mobilepay\Util;

use Shopware\Core\Checkout\Payment\Exception\UnknownPaymentMethodException;
use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\File\MediaFile;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class MediaInstaller
{
    private const PAYMENT_METHOD_MEDIA_DIR = 'Resources/icons';

    public function __construct(
        protected EntityRepository      $mediaRepository,
        protected EntityRepository      $mediaFolderRepository,
        protected EntityRepository      $paymentMethodRepository,
        protected FileSaver             $fileSaver
    ) {
    }

    public function installPaymentMethodMedia(
        string $fileName,
        string $paymentMethodId,
        Context $context,
        bool $replace = false
    ): void {
        $criteria = new Criteria([$paymentMethodId]);
        $criteria->addAssociation('media');
        /** @var PaymentMethodEntity $paymentMethod */
        $paymentMethod = $this->paymentMethodRepository->search($criteria, $context)->first();
        if ($paymentMethod === null) {
            throw new UnknownPaymentMethodException($paymentMethodId);
        }

        if (!$replace && $paymentMethod->getMediaId()) {
            return;
        }

        $mediaFile = $this->getMediaFile($fileName);
        $savedFileName = \sprintf('vipps_mobilepay_%s', $fileName);

        $this->fileSaver->persistFileToMedia(
            $mediaFile,
            $savedFileName,
            $this->getMediaId($savedFileName, $paymentMethod, $context),
            $context
        );
    }

    private function getMediaDefaultFolderId(Context $context): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter(
                'media_folder.defaultFolder.entity',
                $this->paymentMethodRepository->getDefinition()->getEntityName()
            )
        );
        $criteria->addAssociation('defaultFolder');
        $criteria->setLimit(1);

        return $this->mediaFolderRepository->searchIds($criteria, $context)->firstId();
    }

    private function getMediaId(string $fileName, PaymentMethodEntity $paymentMethod, Context $context): string
    {
        $media = $paymentMethod->getMedia();
        if ($media !== null && $media->getFileName() === $fileName) {
            return $media->getId();
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('fileName', $fileName));
        $mediaId = $this->mediaRepository->searchIds($criteria, $context)->firstId();

        if ($mediaId === null) {
            $mediaId = Uuid::randomHex();
        }

        $this->paymentMethodRepository->update(
            [[
                'id' => $paymentMethod->getId(),
                'media' => [
                    'id' => $mediaId,
                    'mediaFolderId' => $this->getMediaDefaultFolderId($context),
                ],
            ]],
            $context
        );

        return $mediaId;
    }

    private function getMediaFile(string $fileName, int $level = 1): MediaFile
    {
        $filePath = \sprintf('%s/%s/%s.svg', \dirname(__DIR__, $level), self::PAYMENT_METHOD_MEDIA_DIR, $fileName);

        return new MediaFile(
            $filePath,
            \mime_content_type($filePath) ?: '',
            \pathinfo($filePath, \PATHINFO_EXTENSION),
            \filesize($filePath) ?: 0
        );
    }
}
