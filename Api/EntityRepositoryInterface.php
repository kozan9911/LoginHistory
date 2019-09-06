<?php

namespace Wiserbrand\LoginHistory\Api;

use Wiserbrand\LoginHistory\Api\Data\EntityInterface;

interface EntityRepositoryInterface
{
    /**
     * Save entity.
     *
     * @param \Wiserbrand\LoginHistory\Api\Data\EntityInterface $entity
     *
     * @return \Wiserbrand\LoginHistory\Api\Data\EntityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(EntityInterface $entity);

    /**
     * Retrieve entity.
     *
     * @param int $entityId
     *
     * @return \Wiserbrand\LoginHistory\Api\Data\EntityInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Delete entity.
     *
     * @param \Wiserbrand\LoginHistory\Api\Data\EntityInterface $entity
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(EntityInterface $entity);

    /**
     * Delete entity by ID.
     *
     * @param int $entityId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);

}
