<?php

namespace TomatoPHP\TomatoInvoices\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use TomatoPHP\TomatoAdmin\Facade\Tomato;
use TomatoPHP\TomatoOrders\Models\Branch;
use TomatoPHP\TomatoOrders\Models\Company;
use TomatoPHP\TomatoProducts\Models\Product;

class InvoiceController extends Controller
{
    public string $model;

    public function __construct()
    {
        $this->model = \TomatoPHP\TomatoInvoices\Models\Invoice::class;
    }

    /**
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View|JsonResponse
    {
        return Tomato::index(
            request: $request,
            model: $this->model,
            view: 'tomato-invoices::invoices.index',
            table: \TomatoPHP\TomatoInvoices\Tables\InvoiceTable::class
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function api(Request $request): JsonResponse
    {
        return Tomato::json(
            request: $request,
            model: \TomatoPHP\TomatoInvoices\Models\Invoice::class,
        );
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return Tomato::create(
            view: 'tomato-invoices::invoices.create',
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'from_id' => 'required|array',
            'for_id' => 'required|array',
            'items' => 'required|array|min:1',
            'uuid' => 'required|max:255|string|unique:invoices,uuid',
        ]);

        $request->merge([
            "user_id" => auth('web')->user()->id,
            "name" => $request->get('for_id')['name'],
            "phone" => $request->get('for_id')['phone'],
            "address" => $request->get('for_id')['address'],
            "status" => 'pending',
            "total" => collect($request->get('items'))->sum('total'),
            "vat" => collect($request->get('items'))->map(function ($item) {
                return $item['tax'] * $item['qty'];
            })->sum(),
            "discount" => collect($request->get('items'))->map(function ($item) {
                return $item['discount'] * $item['qty'];
            })->sum(),
            "is_activated" => true,
            "is_offer" => $request->get('type') === 'offer',
            "from_id" => $request->get('from_id')['id'],
            "from_type" => Company::class,
            "for_id" => $request->get('for_id')['id'],
            "paid" => $request->get('paid') ?? 0,
            "for_type" => array_key_exists('website',$request->get('for_id')) ? Company::class : config('tomato-crm.model'),
        ]);

        $request->validate([
            'from_id' => 'required|exists:companies,id',
            'for_id' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'uuid' => 'required|max:255|string|unique:invoices,uuid',
            'name' => 'required|max:255|string',
            'phone' => 'nullable|max:255',
            'address' => 'nullable|max:255|string',
            'type' => 'nullable|max:255|string',
            'status' => 'required|max:255|string',
            'total' => 'required',
            'discount' => 'required',
            'vat' => 'required',
            'paid' => 'required',
            'date' => 'nullable',
            'due_date' => 'nullable',
            'is_activated' => 'nullable',
            'is_offer' => 'nullable',
            'send_email' => 'nullable',
            'notes' => 'nullable|max:65535'
        ]);

        $response = Tomato::store(
            request: $request,
            model: \TomatoPHP\TomatoInvoices\Models\Invoice::class,
            message: __('Invoice updated successfully'),
            redirect: 'admin.invoices.index',
        );

        foreach ($request->get('items') as $item){
            if(is_array($item['item'])){
                $name = $item['item']['name'][app()->getLocale()];
                $type = isset($item['item']['barcode']) ? 'product' : 'material';
                if($type === 'product'){
                    $item_type = Product::class;
                    $item_id = $item['item']['id'];
                }
                else {
                    $item_type = "\Modules\TomatoMaterials\Entities\Material::class";
                    $item_id = $item['item']['id'];
                }
            }
            else {
                $name = $item['item'];
                $type = 'item';
            }

            $response->record->invoicesItems()->create([
                'item' => $name,
                'qty' => $item['qty'],
                'price' => $item['price'],
                'discount' => $item['discount'],
                'tax' => $item['tax'],
                'total' => $item['total'],
                'type' => $type,
                'item_id' => $item_id??null,
                'item_type' => $item_type??null,
                'options' => $item['options'] ?? null,
            ]);
        }

        if($request->has('insert_inventory') && $request->get('insert_inventory')){
            //TODO: handel insert into inventory items
        }

        if($request->has('send_email') && $request->get('send_email')){
            //TODO: handel send email on invoice
        }

        if($response instanceof JsonResponse){
            return $response;
        }

        return $response->redirect;
    }

    /**
     * @param \TomatoPHP\TomatoInvoices\Models\Invoice $model
     * @return View|JsonResponse
     */
    public function show(\TomatoPHP\TomatoInvoices\Models\Invoice $model): View|JsonResponse
    {
        $model->from_id = $model->from_type::find($model->from_id);
        $model->for_id = $model->for_type::find($model->for_id);
        return Tomato::get(
            model: $model,
            view: 'tomato-invoices::invoices.show',
        );
    }

    /**
     * @param \TomatoPHP\TomatoInvoices\Models\Invoice $model
     * @return View
     */
    public function edit(\TomatoPHP\TomatoInvoices\Models\Invoice $model): View
    {
        $model->items = $model->invoicesItems;
        $model->from_id = $model->from_type::find($model->from_id);
        $model->for_id = $model->for_type::find($model->for_id);
        return Tomato::get(
            model: $model,
            view: 'tomato-invoices::invoices.edit',
        );
    }

    /**
     * @param Request $request
     * @param \TomatoPHP\TomatoInvoices\Models\Invoice $model
     * @return RedirectResponse|JsonResponse
     */
    public function update(Request $request, \TomatoPHP\TomatoInvoices\Models\Invoice $model): RedirectResponse|JsonResponse
    {
        $request->validate([
            'from_id' => 'sometimes|array',
            'for_id' => 'sometimes|array',
            'items' => 'sometimes|array|min:1',
            'uuid' => 'sometimes|max:255|string|unique:invoices,uuid,'.$model->id,
        ]);

        $request->merge([
            "user_id" => auth('web')->user()->id,
            "name" => $request->get('for_id')['name'],
            "phone" => $request->get('for_id')['phone'],
            "address" => $request->get('for_id')['address'],
            "status" => 'pending',
            "total" => collect($request->get('items'))->sum('total'),
            "vat" => collect($request->get('items'))->map(function ($item) {
                return $item['tax'] * $item['qty'];
            })->sum(),
            "discount" => collect($request->get('items'))->map(function ($item) {
                return $item['discount'] * $item['qty'];
            })->sum(),
            "is_activated" => true,
            "is_offer" => $request->get('type') === 'offer',
            "from_id" => $request->get('from_id')['id'],
            "from_type" => Company::class,
            "for_id" => $request->get('for_id')['id'],
            "paid" => $request->get('paid') ?: 0,
            "for_type" => (array_key_exists('website',$request->get('for_id')) ? Company::class : config('tomato-crm.model')),
        ]);

        $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'category_id' => 'nullable|exists:categories,id',
            'phone' => 'nullable|max:255',
            'address' => 'nullable|max:255|string',
            'type' => 'nullable|max:255|string',
            'date' => 'nullable',
            'due_date' => 'nullable',
            'is_activated' => 'nullable',
            'is_offer' => 'nullable',
            'send_email' => 'nullable',
            'notes' => 'nullable|max:65535'
        ]);

        $response = Tomato::update(
            request: $request,
            model: $model,
            message: __('Invoice updated successfully'),
            redirect: 'admin.invoices.index',
        );

        foreach ($request->get('items') as $item){
            if(is_array($item['item'])){
                $name = $item['item']['name'][app()->getLocale()];
                $type = isset($item['item']['barcode']) ? 'product' : 'material';
                if($type === 'product'){
                    $item_type = Product::class;
                    $item_id = $item['item']['id'];
                }
                else {
                    $item_type = "\Modules\TomatoMaterials\Entities\Material::class";
                    $item_id = $item['item']['id'];
                }
            }
            else {
                $name = $item['item'];
                $type = 'item';
            }
            if(array_key_exists('id', $item)){
                $response->record->invoicesItems()->where('id', $item['id'])->first()?->update([
                    'item' => $name,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'tax' => $item['tax'],
                    'total' => $item['total'],
                    'type' => $type,
                    'item_id' => $item_id??null,
                    'item_type' => $item_type??null,
                ]);
            }
            else {
                $response->record->invoicesItems()->create([
                    'item' => $name,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'tax' => $item['tax'],
                    'total' => $item['total'],
                    'type' => $type,
                    'item_id' => $item_id??null,
                    'item_type' => $item_type??null,
                ]);
            }

        }

        if($request->has('insert_inventory') && $request->get('insert_inventory')){
            //TODO: handel insert into inventory items
        }

        if($request->has('send_email') && $request->get('send_email')){
            //TODO: handel send email on invoice
        }

         if($response instanceof JsonResponse){
             return $response;
         }

         return $response->redirect;
    }

    /**
     * @param \TomatoPHP\TomatoInvoices\Models\Invoice $model
     * @return RedirectResponse|JsonResponse
     */
    public function destroy(\TomatoPHP\TomatoInvoices\Models\Invoice $model): RedirectResponse|JsonResponse
    {
        $response = Tomato::destroy(
            model: $model,
            message: __('Invoice deleted successfully'),
            redirect: 'admin.invoices.index',
        );

        if($response instanceof JsonResponse){
            return $response;
        }

        return $response->redirect;
    }

    public function company(Request $request){
        $request->validate([
            "search" => "required|max:255|string",
        ]);

        $q = $request->get('search');

        $company = Company::where(function ($query) use ($q) {
            $query->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('phone', 'like', "%$q%");
        })->get();
        if($company){
            return response()->json($company);
        }
    }

    public function accounts(Request $request){
        $request->validate([
            "search" => "required|max:255|string",
        ]);

        $q = $request->get('search');

        $account = config('tomato-crm.model')::where(function ($query) use ($q) {
            $query->where('name', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
                ->orWhere('phone', 'like', "%$q%");
        })->with('locations')->get();

        if(count($account) > 0){
            return response()->json($account);
        }
        else {
            $company = Company::where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            })->get();
            if($company){
                return response()->json($company);
            }
        }
    }

    public function branches(Request $request){
        $request->validate([
            "company_id" => "required|exists:companies,id",
        ]);

        $branches = Branch::where('company_id', $request->get('company_id'))->get();
        if($branches){
            return response()->json([
                'data' => $branches
            ]);
        }
    }

    public function product(Request $request){
        $request->validate([
            "search" => "required|max:255|string",
        ]);

        $q = $request->get('search');

        $account = Product::where(function ($query) use ($q) {
            $query->whereJsonContains('name', $q)
                ->orWhere('sku', 'like', "%$q%")
                ->orWhere('barcode', 'like', "%$q%");
        })->with('productMetas', function ($q){
            $q->where('key', 'options')->first();
        })->get();
        if($account){
            return response()->json($account);
        }
    }
}
