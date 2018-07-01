<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\CartItem
 *
 * @property int $id
 * @property int $user_id 关联用户ID
 * @property int $product_sku_id sku id
 * @property int $amount 商品数量
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ProductSku $productSku
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereProductSkuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CartItem whereUserId($value)
 * @mixin \Eloquent
 */
	class CartItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property string $no 订单号
 * @property int $user_id 下单user_id
 * @property array $address 地址存json
 * @property float $total_amount 订单总金额
 * @property string $note 订单备注
 * @property \Carbon\Carbon|null $paid_at 支付时间
 * @property string|null $payment_method 支付方式
 * @property string|null $payment_no 支付平台订单号
 * @property string|null $refund_status 退款状态
 * @property string|null $refund_no 退款单号
 * @property bool $closed 订单是否关闭
 * @property bool $reviewed 订单是否评价
 * @property string|null $ship_status 物流状态
 * @property array $ship_data 物流数据
 * @property array $extra 额外数据
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereRefundNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereRefundStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereReviewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereShipData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereShipStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $order_id order_id
 * @property int $product_id product_id
 * @property int $product_sku_id
 * @property int $amount 数量
 * @property float $price 单价
 * @property int $rating 用户打分
 * @property string|null $review review
 * @property \Carbon\Carbon|null $reviewed_at 评价时间
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ProductSku $product_sku
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereProductSkuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\ProductSku $productSku
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property bool $on_sale
 * @property float $rating
 * @property int $sold_count
 * @property int $review_count
 * @property float $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductSku[] $skus
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereReviewCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductSku
 *
 * @property int $id
 * @property string $title SKU 名称
 * @property string $description SKU 描述
 * @property float $price SKU 价格
 * @property int $stock
 * @property int $product_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartItem[] $cartItem
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ProductSku extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property bool $email_verified 邮箱验证
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserAddress[] $addresses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CartItem[] $cartItems
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $favoriteProducts
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $address 具体地址
 * @property string $contact_name 联系人姓名
 * @property string $contact_phone 联系人电话
 * @property int $zip 邮编
 * @property \Carbon\Carbon|null $last_used_at 使用时间
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $full_address
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereZip($value)
 * @mixin \Eloquent
 */
	class UserAddress extends \Eloquent {}
}

