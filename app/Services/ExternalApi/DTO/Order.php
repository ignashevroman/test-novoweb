<?php


namespace App\Services\ExternalApi\DTO;

/**
 * Class Order
 * @package App\Services\ExternalApi\DTO
 */
final class Order
{
    /**
     * @var string
     */
    private $link;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var int
     */
    private $service;

    /**
     * @var string
     */
    private $operationId;

    /**
     * Order constructor.
     * @param string $link
     * @param int $quantity
     * @param int $service
     * @param string $operationId
     */
    public function __construct(string $link, int $quantity, int $service, string $operationId)
    {
        $this->link = $link;
        $this->quantity = $quantity;
        $this->service = $service;
        $this->operationId = $operationId;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getService(): int
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getOperationId(): string
    {
        return $this->operationId;
    }

    public function toArray(): array
    {
        return [
            'link' => $this->getLink(),
            'quantity' => $this->getQuantity(),
            'service' => $this->getService(),
            'operation_id' => $this->getOperationId(),
        ];
    }
}
