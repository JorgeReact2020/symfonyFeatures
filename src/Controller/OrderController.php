<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Order\Service\OrderProcessingService;
use App\Service\Order\Product\{PhysicalProduct, DigitalProduct, ServiceProduct};
use App\Service\Order\DTO\CreateOrderDTO;
use App\Service\Order\ValueObject\{Money, Weight};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order', name: 'order_')]
class OrderController extends AbstractController
{
    public function __construct(private readonly OrderProcessingService $orderService) {}

    #[Route('/', name: 'index')]
    public function index(): JsonResponse
    {
        return $this->json(['tests' => ['/order/physical', '/order/digital', '/order/service', '/order/promo']]);
    }

    #[Route('/physical', name: 'physical')]
    public function physical(): JsonResponse
    {
        $product = new PhysicalProduct('P1', 'Laptop', new Money(999, 'EUR'), new Weight(2.5, 'kg'));
        $order = new CreateOrderDTO('CUST-1', [$product]);
        $confirmation = $this->orderService->process($order);
        return $this->json(['message' => 'Physical product order', 'order' => [
            'id' => $confirmation->orderId,
            'total' => $confirmation->total->format(),
            'tracking' => $confirmation->trackingNumber
        ]]);
    }

    #[Route('/digital', name: 'digital')]
    public function digital(): JsonResponse
    {
        $product = new DigitalProduct('D1', 'Ebook', new Money(29, 'EUR'), 'https://dl.example.com/book.pdf');
        $order = new CreateOrderDTO('CUST-2', [$product]);
        $confirmation = $this->orderService->process($order);
        return $this->json(['message' => 'Digital product (no shipping)', 'order' => [
            'id' => $confirmation->orderId,
            'total' => $confirmation->total->format()
        ]]);
    }

    #[Route('/service', name: 'service')]
    public function service(): JsonResponse
    {
        $product = new ServiceProduct('S1', 'Subscription', new Money(49, 'EUR'));
        $order = new CreateOrderDTO('CUST-3', [$product]);
        $confirmation = $this->orderService->process($order);
        return $this->json(['message' => 'Service product (activable)', 'order' => [
            'id' => $confirmation->orderId,
            'total' => $confirmation->total->format()
        ]]);
    }

    #[Route('/promo', name: 'promo')]
    public function promo(): JsonResponse
    {
        $products = [
            new PhysicalProduct('P1', 'Item 1', new Money(100, 'EUR'), new Weight(1, 'kg')),
            new PhysicalProduct('P2', 'Item 2', new Money(100, 'EUR'), new Weight(1, 'kg')),
            new PhysicalProduct('P3', 'Item 3', new Money(100, 'EUR'), new Weight(1, 'kg')),
            new ServiceProduct('S1', 'Subscription', new Money(1000, 'EUR'))

        ];
        $order = new CreateOrderDTO('CUST-4', $products, 'WELCOME10');
        $confirmation = $this->orderService->process($order);


        return $this->json(['message' => 'OCP Demo: Multiple promotions applied', 'order' => [
            'id' => $confirmation->orderId,
            'total before discount' => $confirmation->subtotal?->format() ?? 'N/A',
            'total' => $confirmation->total->format(),
            'promotions' => 'Coupon 10% + Loyalty 5% + Bulk 15€'
        ]]);
    }
}
