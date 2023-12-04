<?php

namespace TomatoPHP\TomatoInvoices\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\TomatoCrm\Models\Account;
use TomatoPHP\TomatoOrders\Models\Order;
use TomatoPHP\TomatoCategory\Models\Category;
use TomatoPHP\TomatoOrders\Models\Branch;

/**
 * @property integer $id
 * @property integer $from_id
 * @property string $from_type
 * @property integer $for_id
 * @property string $for_type
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $branch_id
 * @property integer $category_id
 * @property string $uuid
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $type
 * @property string $status
 * @property float $total
 * @property float $discount
 * @property float $vat
 * @property float $paid
 * @property string $date
 * @property string $due_date
 * @property boolean $is_activated
 * @property boolean $is_offer
 * @property boolean $send_email
 * @property string $notes
 * @property string $created_at
 * @property string $updated_at
 * @property InvoiceMeta[] $invoiceMetas
 * @property Account $account
 * @property Branch $branch
 * @property Category $category
 * @property Order $order
 * @property User $user
 * @property InvoicesItem[] $invoicesItems
 */
class Invoice extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['from_id','from_type','for_id', 'for_type', 'order_id', 'user_id', 'branch_id', 'category_id', 'uuid', 'name', 'phone', 'address', 'type', 'status', 'total', 'discount', 'vat', 'paid', 'date', 'due_date', 'is_activated', 'is_offer', 'send_email', 'notes', 'created_at', 'updated_at'];

    protected $casts = [
        'due_date' => 'datetime',
        'date' => 'datetime',
        'is_offer' => 'bool',
        'is_activated' => 'bool',
        'send_email' => 'bool'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoiceMetas()
    {
        return $this->hasMany('TomatoPHP\TomatoInvoices\Models\InvoiceMeta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoicesItems()
    {
        return $this->hasMany('TomatoPHP\TomatoInvoices\Models\InvoicesItem');
    }
}
