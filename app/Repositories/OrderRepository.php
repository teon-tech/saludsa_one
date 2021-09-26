<?php


namespace App\Repositories;


use App\Models\Order;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    /**
     * @param array $data
     * @param array $products
     * @return Order
     * @throws \Exception
     */
    public function create(array $data, array $products = [])
    {
        try {
            DB::beginTransaction();
            $tax = (float)Config::get('constants.tax'); //

            $order = new Order();
            $order->fill($data);

            $subtotal = 0;
            $taxCalculated = 0;
            $total = 0;

            $orderByProducts = [];
            foreach ($products as $item) {
                $productId = (int)$item['productId'];
                $quantity = (int)$item['quantity'];
                $product = Product::query()->find($productId);
                $product->count_order = $product->count_order + 1;
                $product->save();
                $price = $product->price;
                $subtotalProduct = $price * $quantity;
                $taxCalculatedProduct = $subtotalProduct * ($tax / 100);
                $totalProduct = $subtotalProduct + $taxCalculatedProduct;
                $orderByProducts[] = [
                    'product_id' => $productId,
                    'price' => $price,
                    'quantity' => $quantity,
                    'tax' => $tax,
                    'tax_calculated' => $taxCalculatedProduct,
                    'subtotal' => $subtotalProduct,
                    'total' => $totalProduct,
                    'unit_selected' => $item['unit'] ?? null
                ];
                $subtotal += $subtotalProduct;
                $taxCalculated += $taxCalculatedProduct;
            }
            $total += $subtotal + $taxCalculated;

            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->tax_calculated = $taxCalculated;
            $order->total = $total;
            $order->payment_status = Order::PAYMENT_STATUS_PENDING;
            $order->status = Order::STATUS_IN_PROGRESS;
            $order->save();
            $order->details()->createMany($orderByProducts);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw  $e;
        }
    }

    /**
     * @param $value
     * @param string $attr
     * @return Builder|Model|object|null
     */
    public function findBy($value, $attr = 'id')
    {
        return Order::query()
            ->where($attr, $value)
            ->first();
    }

    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function findAll($params = [])
    {
        $size = isset($params['size']) && $params['size'] ? $params['size'] : 10;

        $query = Order::query();
        $query->select('order.*');
        $uid = $params['uid'];

        $query->join('customer', 'customer.id', '=', 'order.customer_id');
        $query->where('customer.firebase_uid', $uid);

        return $query->paginate($size);
    }

    /**
     * @param $orderId
     * @param $qualification
     * @return Builder|Model|object|null
     */
    public function qualification($orderId, $qualification)
    {
        $order = $this->findBy($orderId, 'id');
        $order->qualification = (int)$qualification;
        $idProvider = $order->provider_id;
        $order->save();
        $this->calculateQualification($idProvider);
        return $order;
    }

    /**
     * @param $idProvider
     */
    public function calculateQualification($idProvider)
    {
        $queryOrder = Order::query();
        $qualification = $queryOrder->where('provider_id', $idProvider)->sum('qualification');
        if ($qualification > 0) {
            $countOrder = $queryOrder->where('provider_id', $idProvider)->count();
            $calculateQualification = $qualification / $countOrder;

            $queryProvider = Provider::query();
            $queryProvider->where('id', $idProvider)
                ->update(
                    [
                        'qualification' => $calculateQualification
                    ]
                );
        }
    }
}
