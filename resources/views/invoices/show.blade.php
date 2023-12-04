<x-tomato-admin-container label="{{trans('tomato-admin::global.crud.view')}} {{__('Invoice')}} #{{$model->uuid}} | [{{$model->branch?->name}}]">

    <div class="flex justify-between xl:gap-60 lg:gap-48 md:gap-16 sm:gap-8 sm:flex-row flex-col gap-4">
        <div class="w-full">
            <div class=" my-4">
                <img src="{{setting('site_logo')}}" alt="{{setting('site_name')}}" class="h-12 ">
            </div>
            <div class="flex flex-col">
                <div>
                    {{__('From:')}}
                </div>
                <div class="text-lg font-bold mt-2">
                    {{$model->from_id->name}}
                </div>
                <div class="text-sm">
                    {{$model->from_id->ceo}}
                </div>
                <div class="text-sm">
                    {{$model->from_id->address}}
                </div>
                <div class="text-sm">
                    {{$model->from_id->zip}} {{$model->from_id->city}}
                </div>
                <div class="text-sm">
                    {{$model->from_id->country?->name}}
                </div>
            </div>
            <div class="mt-4">
                <div>
                    {{__('To:')}}
                </div>
                <div class="mt-4">
                    <div class="text-lg font-bold mt-2">
                        {{$model->for_id?->name}}
                    </div>
                    <div class="text-sm">
                        {{$model->for_id?->email}}
                    </div>
                    <div class="text-sm">
                        {{$model->for_id?->phone}}
                    </div>
                    <div class="text-sm">
                        {{$model->for_id?->address}}
                    </div>
                    <div class="text-sm">
                        {{$model->for_id?->country?->name}} , {{$model->for_id?->city}}, {{$model->for_id?->area?->name}}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-4 w-full">
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-center items-center">
                    {{__('Invoice')}}
                </div>
                <div>
                    {{$model->uuid}}
                </div>
            </div>
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-center items-center">
                    {{__('Issue date')}}
                </div>
                <div>
                    {{$model->created_at->toDateString()}}
                </div>
            </div>
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-center items-center">
                    {{__('Due date')}}
                </div>
                <div>
                    {{$model->due_date->toDateString()}}
                </div>
            </div>
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-center items-center">
                    {{__('Status')}}
                </div>
                <div>
                    {{str($model->status)->upper()}}
                </div>
            </div>
            <div class="flex justify-between gap-4">
                <div class="flex flex-col justify-center items-center">
                    {{__('Type')}}
                </div>
                <div class="font-bold text-primary-500">
                    {{str($model->type)->upper()}}
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="grid grid-cols-12 gap-4 border-b py-4 my-4 font-bold">
            <div class="col-span-4 ">
                {{__('Item')}}
            </div>
            <div>
                {{__('Price')}}
            </div>
            <div>
                {{__('Discount')}}
            </div>
            <div class="col-span-2">
                {{__('Tax')}}
            </div>
            <div>
                {{__('QTY')}}
            </div>
            <div>
                {{__('Total')}}
            </div>
        </div>
        <div class="flex flex-col gap-4">
            @foreach($model->invoicesitems as $item)
                <div class="grid grid-cols-12 gap-4 border-b py-4">
                    <div class="col-span-4 flex  flex-col justify-start">
                        <div>
                            {{ $item->item }}
                        </div>
                        <div class="text-gray-400">
                            @foreach($item->options  ?? [] as $label=>$options)
                                <span>{{  str($label)->ucfirst() }}</span> : {{$options}} <br>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        {!! dollar($item->price) !!}
                    </div>
                    <div>
                        {!! dollar($item->discount) !!}
                    </div>
                    <div class="col-span-2">
                        {!! dollar($item->tax) !!}
                    </div>
                    <div>
                        {{$item->qty}}
                    </div>
                    <div>
                        {!! dollar($item->total) !!}
                    </div>
                </div>
            @endforeach

        </div>
        <div class="flex flex-col gap-4 mt-4">
            <div class="flex justify-between gap-4 py-4 border-b">
                <div class="font-bold">
                    {{__('Sub Total')}}
                </div>
                <div>
                    {!! dollar(($model->total + $model->discount) - ($model->vat + $model->shipping)) !!}
                </div>
            </div>
            <div class="flex justify-between gap-4 py-4 border-b text-success-500">
                <div class="font-bold">
                    {{__('Tax')}}
                </div>
                <div>
                    {!! dollar($model->vat ) !!}
                </div>
            </div>
            <div class="flex justify-between gap-4 py-4 border-b text-danger-500">
                <div class="font-bold">
                    {{__('Discount')}}
                </div>
                <div>
                    {!! dollar($model->discount ) !!}
                </div>
            </div>
            <div class="flex justify-between gap-4 py-4 border-b text-danger-500">
                <div class="font-bold">
                    {{__('Paid')}}
                </div>
                <div>
                    {!! dollar($model->paid ) !!}
                </div>
            </div>
            <div class="flex justify-between gap-4 py-4 border-b text-primary-500">
                <div class="font-bold">
                    {{__('Total')}}
                </div>
                <div>
                    {!! dollar($model->total-$model->paid) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-start gap-2 pt-3">
        {{--        <x-tomato-admin-button  label="{{__('Print')}}" :href="route('admin.orders.print', $model->id)"/>--}}
        <x-tomato-admin-button warning label="{{__('Edit')}}" :href="route('admin.invoices.edit', $model->id)"/>
        <x-tomato-admin-button danger :href="route('admin.invoices.destroy', $model->id)"
                               confirm="{{trans('tomato-admin::global.crud.delete-confirm')}}"
                               confirm-text="{{trans('tomato-admin::global.crud.delete-confirm-text')}}"
                               confirm-button="{{trans('tomato-admin::global.crud.delete-confirm-button')}}"
                               cancel-button="{{trans('tomato-admin::global.crud.delete-confirm-cancel-button')}}"
                               method="delete"  label="{{__('Delete')}}" />
        <x-tomato-admin-button secondary :href="route('admin.invoices.index')" label="{{__('Cancel')}}"/>
    </div>
</x-tomato-admin-container>
