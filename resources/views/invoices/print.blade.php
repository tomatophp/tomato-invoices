<div class="flex justify-between xl:gap-60 lg:gap-48 md:gap-16 sm:gap-8 sm:flex-row flex-col gap-4">
    <div class="w-full">
        <div>
            <img src="{{setting('site_logo')}}" alt="{{setting('site_name')}}" class="w-1/4">
        </div>
        <div class="flex flex-col">
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
        <div class="mt-8">
            <div class="mt-4">
                <div class="text-sm text-gray-400">
                    {{__('Bill To')}}:
                </div>
                <div class="text-lg font-bold">
                    {{$model->for_id?->name}}
                </div>
                <div class="text-sm">
                    {{$model->for_id?->email}}
                </div>
                <div class="text-sm">
                    {{$model->for_id?->phone}}
                </div>
                @php
                    $address = $model->for_id?->locations()->first();
                @endphp
                @if($address)
                    <div class="text-sm">
                        {{$address->street}}
                    </div>
                    <div class="text-sm">
                        {{$address->zip}}, {{$address->city->name}}
                    </div>
                    <div class="text-sm">
                        {{$model->for_id?->locations()->first()?->country->name}}
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div class="w-full flex flex-col">
        <div class="flex justify-end font-bold">
            <div>
                <div>
                    <h1 class="text-3xl">{{__('INVOICE')}}</h1>
                </div>
                <div>
                    {{__('Invoice')}}# {{$model->uuid}}
                </div>
            </div>
        </div>
        <div class="flex justify-end h-full">
            <div class="flex flex-col justify-end">
                <div>
                    <div class="flex justify-between gap-4">
                        <div class="text-gray-400">{{__('Issue Date')}} : </div>
                        <div>{{$model->created_at->toDateString()}}</div>
                    </div>
                    <div class="flex justify-between gap-4">
                        <div class="text-gray-400">{{__('Due Date')}} : </div>
                        <div>{{$model->due_date->toDateString()}}</div>
                    </div>
                    <div class="flex justify-between gap-4">
                        <div class="text-gray-400">{{__('Status')}} : </div>
                        <div>{{$model->status}}</div>
                    </div>
                    <div class="flex justify-between gap-4">
                        <div class="text-gray-400">{{__('Type')}} : </div>
                        <div>{{$model->type}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="flex justify-start px-4 font-bold mt-4 bg-gray-900 text-white">
        <div class="border-gray-400 border-r p-2 w-full">
            #
        </div>
        <div class="border-gray-400 border-r p-2 w-full">
            {{__('Item')}}
        </div>
        <div class="border-r border-gray-400 p-2 w-full">
            {{__('Price')}}
        </div>
        <div class="border-r border-gray-400 p-2 w-full">
            {{__('Discount')}}
        </div>
        <div class="border-r border-gray-400 p-2 w-full">
            {{__('Tax')}}
        </div>
        <div class="border-r border-gray-400 p-2 w-full">
            {{__('QTY')}}
        </div>
        <div class="p-2 w-full">
            {{__('Total')}}
        </div>
    </div>
    <div class="flex flex-col gap-4">
        @foreach($model->invoicesitems as $key=>$item)
            <div class="flex justify-start border-b">
                <div class="p-2 w-full">
                    {{ $key+1 }}
                </div>
                <div class="w-full p-2">
                    <div>
                        {{ $item->item }}
                    </div>
                    @if($item->options)
                        <div class="text-gray-400">
                            @foreach($item->options  ?? [] as $label=>$options)
                                <span>{{  str($label)->ucfirst() }}</span> : {{$options}} <br>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="w-full p-2">
                    {{ number_format($item->price, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
                <div class="w-full p-2">
                    {{ number_format($item->discount, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
                <div class="w-full p-2">
                    {{ number_format($item->tax, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
                <div class="w-full p-2">
                    {{ $item->qty }}
                </div>
                <div class="w-full p-2">
                    {{ number_format($item->total, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
            </div>
        @endforeach

    </div>
    <div class="flex justify-between mt-2">
        <div class="flex flex-col justify-end gap-4">
            <div>
                <div class="mb-2 text-xl">
                    {{__('Signature')}}
                </div>
                <div class="text-sm text-gray-500">
                    <div>
                        {{ $model->from_id?->ceo }}
                    </div>
                    <div>
                        {{ $model->from_id?->email }}
                    </div>
                    <div>
                        {{ $model->from_id?->website }}
                    </div>
                    <div>
                        {{ $model->from_id?->phone }}
                    </div>
                </div>
            </div>


                @if($model->is_bank_transfer)
                    <div>
                        <div class="mb-2 text-xl">
                            {{__('Bank Account')}}
                        </div>
                        <div class="text-sm flex flex-col">
                            <div>
                                <span clas="text-gray-500">{{__('Name')}}</span> : <span class="font-bold">{{ $model->bank_name }}</span>
                            </div>
                            <div>
                                <span clas="text-gray-500">{{__('Address')}}</span> : <span class="font-bold">{{ $model->bank_address }}, {{ $model->bank_city }}, {{ $model->bank_country}}</span>
                            </div>
                            <div>
                                <span clas="text-gray-500">{{__('Branch')}}</span> : <span class="font-bold">{{ $model->bank_branch }}</span>
                            </div>
                            <div>
                                <span clas="text-gray-500">{{__('SWIFT')}}</span> : <span class="font-bold">{{ $model->bank_swift }}</span>
                            </div>
                            <div>
                                <span clas="text-gray-500">{{__('Account')}}</span> : <span class="font-bold">{{ $model->bank_account }}</span>
                            </div>
                            <div>
                                <span clas="text-gray-500">{{__('Owner')}}</span> : <span class="font-bold">{{ $model->bank_account_owner }}</span>
                            </div>
                            <div>
                                <span clas="text-gray-500">{{__('IBAN')}}</span> : <span class="font-bold">{{ $model->bank_iban }}</span>
                            </div>
                        </div>
                    </div>
                @endif
        </div>
        <div class="flex flex-col gap-2 mt-4  w-1/2">
            <div class="flex justify-between">
                <div class="font-bold">
                    {{__('Sub Total')}}
                </div>
                <div>
                    {{ number_format(($model->total + $model->discount) - ($model->vat + $model->shipping), 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="font-bold">
                    {{__('Tax')}}
                </div>
                <div>
                    {{ number_format($model->vat, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="font-bold">
                    {{__('Discount')}}
                </div>
                <div>
                    {{ number_format($model->discount, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-4">
                <div class="font-bold">
                    {{__('Paid')}}
                </div>
                <div>
                    {{ number_format($model->paid, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
            </div>
            <div class="flex justify-between text-3xl font-bold">
                <div>
                    {{__('Balance Due')}}
                </div>
                <div>
                    {{ number_format($model->total-$model->paid, 2) }}<small class="text-md font-normal">{{ $model->currency?->symbol }}</small>
                </div>
            </div>
        </div>
    </div>


    @if($model->notes)
        <div class="border-b my-4"></div>

        <div>
            <div class="mb-2 text-xl">
                {{__('Notes')}}
            </div>
            <div class="text-sm text-gray-500">
                {!! $model->notes !!}
            </div>
        </div>
    @endif
</div>
