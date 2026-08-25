<?php

namespace Tests\Unit;

use App\Gateways\RazorpayGateway;
use App\Models\GiftCard;
use App\Services\GiftCardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GiftCardServiceTest extends TestCase
{
    use RefreshDatabase;

    private GiftCardService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $gateway = $this->createMock(RazorpayGateway::class);
        $this->service = new GiftCardService($gateway);
    }

    #[Test]
    public function create_gift_card_order_creates_gift_card_and_returns_order_data(): void
    {
        $mockGateway = $this->createMock(RazorpayGateway::class);
        $mockGateway->method('createOrderWithNotes')->willReturn([
            'id' => 'order_test_123',
            'amount' => 50000,
            'currency' => 'INR',
            'status' => 'created',
            'receipt' => 'gc_1234567890_123',
        ]);

        $service = new GiftCardService($mockGateway);

        $result = $service->createGiftCardOrder([
            'amount' => 500,
            'theme' => 'purple',
            'sender_name' => 'John Doe',
            'sender_email' => 'john@example.com',
            'recipient_name' => 'Jane Doe',
            'recipient_email' => 'jane@example.com',
            'message' => 'Happy Birthday!',
            'send_at' => now()->addDay()->toDateString(),
        ]);

        $this->assertSame('order_test_123', $result['order_id']);
        $this->assertArrayHasKey('gift_card_id', $result);
        $this->assertSame(50000, $result['amount']);

        $this->assertDatabaseHas('gift_cards', [
            'order_id' => 'order_test_123',
            'amount' => 500.00,
            'theme' => 'purple',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function create_gift_card_order_throws_on_gateway_failure(): void
    {
        $mockGateway = $this->createMock(RazorpayGateway::class);
        $mockGateway->method('createOrderWithNotes')->willThrowException(new \RuntimeException('Gateway error'));

        $service = new GiftCardService($mockGateway);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Gateway error');

        $service->createGiftCardOrder([
            'amount' => 500,
            'theme' => 'purple',
            'sender_name' => 'John Doe',
            'sender_email' => 'john@example.com',
            'recipient_name' => 'Jane Doe',
            'recipient_email' => 'jane@example.com',
            'send_at' => now()->addDay()->toDateString(),
        ]);
    }

    #[Test]
    public function verify_gift_card_payment_marks_card_as_completed(): void
    {
        $giftCard = GiftCard::factory()->create([
            'order_id' => 'order_test_123',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $mockGateway = $this->createMock(RazorpayGateway::class);
        $mockGateway->method('verifyPaymentSignature')->willReturnCallback(function () {});
        $mockGateway->method('createOrderWithNotes')->willReturn(['id' => 'order_test_123']);

        $service = new GiftCardService($mockGateway);
        $result = $service->verifyGiftCardPayment(
            'order_test_123',
            'pay_test_456',
            'sig_test_789',
            $giftCard->id
        );

        $this->assertSame('completed', $result->payment_status);
        $this->assertSame('sent', $result->status);
        $this->assertSame('pay_test_456', $result->payment_id);

        $this->assertDatabaseHas('gift_cards', [
            'id' => $giftCard->id,
            'payment_status' => 'completed',
            'status' => 'sent',
        ]);
    }

    #[Test]
    public function verify_gift_card_payment_is_idempotent_when_already_completed(): void
    {
        $giftCard = GiftCard::factory()->paid()->create([
            'order_id' => 'order_test_123',
            'payment_id' => 'pay_test_456',
        ]);

        $mockGateway = $this->createMock(RazorpayGateway::class);
        $mockGateway->method('verifyPaymentSignature')->willReturnCallback(function () {});

        $service = new GiftCardService($mockGateway);
        $result = $service->verifyGiftCardPayment(
            'order_test_123',
            'pay_test_new',
            'sig_test_789',
            $giftCard->id
        );

        $this->assertSame('completed', $result->payment_status);
        $this->assertSame('pay_test_456', $result->payment_id);
    }

    #[Test]
    public function verify_gift_card_payment_throws_on_invalid_signature(): void
    {
        $giftCard = GiftCard::factory()->create([
            'order_id' => 'order_test_123',
            'payment_status' => 'pending',
        ]);

        $mockGateway = $this->createMock(RazorpayGateway::class);
        $mockGateway->method('verifyPaymentSignature')->willThrowException(new \RuntimeException('Invalid signature'));

        $service = new GiftCardService($mockGateway);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid signature');

        $service->verifyGiftCardPayment(
            'order_test_123',
            'pay_test_456',
            'sig_test_789',
            $giftCard->id
        );
    }
}
