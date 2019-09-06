<?php

namespace Wiserbrand\LoginHistory\Model;

use Wiserbrand\LoginHistory\Api\EntityRepositoryInterface;
use Wiserbrand\LoginHistory\Api\Data;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class EntityRepository
 */
class EntityRepository implements EntityRepositoryInterface
{
    /**
     * @var EntityFactory
     */
    protected $entityFactory;
    /**
     * @var ResourceModel\Entity
     */
    protected $resource;

    /**
     * EntityRepository constructor.
     *
     * @param ResourceModel\Entity $resource
     * @param EntityFactory        $entityFactory
     */
    public function __construct(
        \Wiserbrand\LoginHistory\Model\ResourceModel\Entity $resource,
        \Wiserbrand\LoginHistory\Model\EntityFactory $entityFactory
    ) {
        $this->entityFactory = $entityFactory;
        $this->resource = $resource;
    }

    /**
     * Save data.
     *
     * @throws CouldNotSaveException
     */
    public function save(Data\EntityInterface $entity)
    {
        try {
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $entity;
    }

    /**
     * Retrieve data.
     *
     * @throws NoSuchEntityException
     */
    public function getById($entityId)
    {
        $entity = $this->entityFactory->create();
        $entity->getResource()->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Unable to find entity with ID "%1"',
                $entityId));
        }
        return $entity;
    }

    /**
     * Delete test.
     *
     * @throws \Exception
     */
    public function delete(Data\EntityInterface $entity)
    {
        try {
            $this->resource->delete($entity);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete by ID.
     *
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     * @throws \Exception
     */
    public function deleteById($entityId)
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Unable to find entity with ID "%1"',
                $entityId));
        }
        $this->delete($entity);
        return true;
    }
}
