<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
  use HasFactory;

  protected $fillable = [
  'user_id',
  'order_number',
  'customer_name',
  'customer_email',
  'customer_phone',
  'shipping_address',
  'billing_address',
  'city',
  'district',
  'ward',
  'subtotal',
  'shipping_fee',
  'discount',
  'discount_code',
  'total',
  'payment_method',
  'payment_status',
  'order_status',
  'payment_data',
  'admin_notes',
  'customer_notes',
  'paid_at',
  'confirmed_at',
  'shipped_at',
  'delivered_at',
  ];

  protected $casts = [
  'subtotal' => 'decimal:0',
  'shipping_fee' => 'decimal:0',
  'discount' => 'decimal:0',
  'total' => 'decimal:0',
  'payment_data' => 'array',
  'paid_at' => 'datetime',
  'confirmed_at' => 'datetime',
  'shipped_at' => 'datetime',
  'delivered_at' => 'datetime',
  ];

  const STATUS_PENDING = 'pending';
  const STATUS_CONFIRMED = 'confirmed';
  const STATUS_PROCESSING = 'processing';
  const STATUS_TAILORING = 'tailoring';
  const STATUS_SHIPPING = 'shipping';
  const STATUS_DELIVERED = 'delivered';
  const STATUS_CANCELLED = 'cancelled';

  const PAYMENT_PENDING = 'pending';
  const PAYMENT_PAID = 'paid';
  const PAYMENT_FAILED = 'failed';
  const PAYMENT_REFUNDED = 'refunded';

  const METHOD_MOMO = 'momo';
  const METHOD_VNPAY = 'vnpay';
  const METHOD_BANK_TRANSFER = 'bank_transfer';
  const METHOD_COD = 'cod';

  protected static function boot()
  {
  parent::boot();

  static::creating(function ($order) {
      if (empty($order->order_number)) {
    $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
      }
  });
  }

  public function user(): BelongsTo
  {
  return $this->belongsTo(User::class);
  }

  public function items(): HasMany
  {
  return $this->hasMany(OrderItem::class);
  }

  public function scopePending($query)
  {
  return $query->where('order_status', self::STATUS_PENDING);
  }

  public function scopePaid($query)
  {
  return $query->where('payment_status', self::PAYMENT_PAID);
  }

  public function getFormattedTotalAttribute(): string
  {
  return number_format($this->total, 0, ',', '.') . ' ₫';
  }

  public function getFormattedSubtotalAttribute(): string
  {
  return number_format($this->subtotal, 0, ',', '.') . ' ₫';
  }

  public function getStatusLabelAttribute(): string
  {
  return match ($this->order_status) {
      self::STATUS_PENDING => 'Chờ xác nhận',
      self::STATUS_CONFIRMED => 'Đã xác nhận',
      self::STATUS_PROCESSING => 'Đang xử lý',
      self::STATUS_TAILORING => 'Đang may',
      self::STATUS_SHIPPING => 'Đang giao hàng',
      self::STATUS_DELIVERED => 'Đã giao hàng',
      self::STATUS_CANCELLED => 'Đã hủy',
      default => 'Không xác định',
  };
  }

  public function getPaymentStatusLabelAttribute(): string
  {
  return match ($this->payment_status) {
      self::PAYMENT_PENDING => 'Chờ thanh toán',
      self::PAYMENT_PAID => 'Đã thanh toán',
      self::PAYMENT_FAILED => 'Thanh toán thất bại',
      self::PAYMENT_REFUNDED => 'Đã hoàn tiền',
      default => 'Không xác định',
  };
  }

  public function getPaymentMethodLabelAttribute(): string
  {
  return match ($this->payment_method) {
      self::METHOD_MOMO => 'Ví MoMo',
      self::METHOD_VNPAY => 'VNPay',
      self::METHOD_BANK_TRANSFER => 'Chuyển khoản ngân hàng',
      self::METHOD_COD => 'Thanh toán khi nhận hàng',
      default => 'Không xác định',
  };
  }

  public static function getStatusOptions(): array
  {
  return [
      self::STATUS_PENDING => 'Chờ xác nhận',
      self::STATUS_CONFIRMED => 'Đã xác nhận',
      self::STATUS_PROCESSING => 'Đang xử lý',
      self::STATUS_TAILORING => 'Đang may',
      self::STATUS_SHIPPING => 'Đang giao hàng',
      self::STATUS_DELIVERED => 'Đã giao hàng',
      self::STATUS_CANCELLED => 'Đã hủy',
  ];
  }

  public static function getPaymentStatusOptions(): array
  {
  return [
      self::PAYMENT_PENDING => 'Chờ thanh toán',
      self::PAYMENT_PAID => 'Đã thanh toán',
      self::PAYMENT_FAILED => 'Thanh toán thất bại',
      self::PAYMENT_REFUNDED => 'Đã hoàn tiền',
  ];
  }
}
