<x-tomato-admin-container label="{{trans('tomato-admin::global.crud.create')}} {{__('Invoice')}}">
    <x-splade-form class="flex flex-col space-y-4" action="{{route('admin.invoices.update', $model->id)}}" method="post" :default="$model">
        <div class="flex justify-between xl:gap-60 lg:gap-48 md:gap-16 sm:gap-8 sm:flex-row flex-col gap-4">
            <div class="w-full">
                <div class="my-4">
                    <img src="{{setting('site_logo')}}" alt="{{setting('site_name')}}" class="h-12 ">
                </div>
                <div class="flex flex-col">
                    <div>
                        {{__('From:')}}
                    </div>
                    <x-tomato-search
                        :remote-url="route('admin.invoices.company')"
                        remote-root="data"
                        name="from_id"
                        placeholder="{{__('Select Company')}}"
                        label="{{__('Company')}}"
                    />
                    <div v-if="form.from_id">
                        <div class="text-lg font-bold mt-2">
                            @{{form.from_id.name}}
                        </div>
                        <div class="text-sm">
                            @{{form.from_id.email}}
                        </div>
                        <div class="text-sm">
                            @{{form.from_id.phone}}
                        </div>
                        <div class="text-sm">
                            @{{form.from_id.address}}
                        </div>
                        <div class="text-sm">
                            @{{form.from_id.zip}} @{{form.from_id.city}}
                        </div>
                        <div class="text-sm">
                            @{{form.from_id.country?form.from_id.country.name:''}}
                        </div>
                        <div class="my-4">
                            <x-splade-select
                                choices
                                name="branch_id"
                                remote-root="data"
                                remote-url="`{{route('admin.invoices.branches') . '?company_id='}}${form.from_id.id}`"
                                option-label="name"
                                option-value="id"
                                placeholder="{{__('Select Branch')}}"
                                label="{{__('Branch')}}"
                            />
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div>
                        {{__('To:')}}
                    </div>
                    <div class="mt-4">
                        <x-tomato-search
                            :remote-url="route('admin.invoices.accounts')"
                            remote-root="data"
                            name="for_id"
                            placeholder="{{__('Select Account')}}"
                            label="{{__('Account')}}"
                        />
                        <div v-if="form.for_id">
                            <div class="text-lg font-bold mt-2">
                                @{{form.for_id.name}}
                            </div>
                            <div class="text-sm">
                                @{{form.for_id.email}}
                            </div>
                            <div class="text-sm">
                                @{{form.for_id.phone}}
                            </div>
                            <div class="text-sm">
                                @{{form.for_id.address}}
                            </div>
                            <div class="text-sm">
                                @{{form.for_id.zip}} @{{form.for_id.city}}
                            </div>
                            <div class="text-sm">
                                @{{form.for_id.country?form.for_id.country.name:''}}
                            </div>
                        </div>
                    </div>
                </div>
                <x-splade-textarea class="my-4" name="notes" label="{{__('Notes')}}" placeholder="{{__('Any Note About This Invoice')}}" autosize/>
            </div>
            <div class="flex flex-col gap-4 w-full">
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Invoice')}}
                    </div>
                    <div>
                        <x-splade-input disabled name="uuid" placeholder="{{__('Due date')}}" />
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Issue date')}}
                    </div>
                    <div>
                        <x-splade-input date time name="date" placeholder="{{__('Due date')}}" />
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Due date')}}
                    </div>
                    <div>
                        <x-splade-input date time name="due_date" placeholder="{{__('Due date')}}" />
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Paid Amount')}}
                    </div>
                    <div>
                        <x-splade-input type="number" name="paid" placeholder="{{__('Total Paid amount')}}"/>
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Type')}}
                    </div>
                    <div>
                        <x-splade-select choices name="type" placeholder="{{__('Type')}}">
                            <option value="invoice">{{__('Sales Invoice')}}</option>
                            <option value="push">{{__('Push Invoice')}}</option>
                            <option value="offer">{{__('Offer')}}</option>
                        </x-splade-select>
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Insert Into Inventory')}}
                    </div>
                    <div>
                        <x-splade-checkbox name="insert_inventory"/>
                    </div>
                </div>
                <div class="flex justify-between gap-4">
                    <div class="flex flex-col justify-center items-center">
                        {{__('Send By Email')}}
                    </div>
                    <div>
                        <x-splade-checkbox name="send_email"/>
                    </div>
                </div>

            </div>
        </div>
        <div>
            <x-tomato-items :options="['item'=>'', 'price'=>0, 'discount'=>0, 'tax'=>0, 'qty'=>1,'total'=>0, 'options' =>(object)[]]" name="items">
                <div class="grid grid-cols-12 gap-4 border-b py-4 my-4">
                    <div class="col-span-4">
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
                    <div class="grid grid-cols-12 gap-4" v-for="(item, key) in items.main">
                        <div class="col-span-4">
                            <x-splade-input
                                class="w-full"
                                type="text"
                                placeholder="Item Name"
                                v-model="items.main[key].item"
                            />
                        </div>
                        <x-splade-input
                            type="number"
                            placeholder="Price"
                            v-model="items.main[key].price"
                            @input="items.updateTotal(key)"
                        />
                        <x-splade-input
                            type="number"
                            placeholder="Item Name"
                            v-model="items.main[key].discount"
                            @input="items.updateTotal(key)"
                        />
                        <div class="col-span-2">
                            <x-splade-input
                                type="number"
                                placeholder="Tax"
                                v-model="items.main[key].tax"
                                @input="items.updateTotal(key, data.discount_type)"
                            />

                            {{--                            <x-splade-data :default="['discount_type'=>'per']" remember="tax" >--}}
                            {{--                                --}}
                            {{--                                <div class="flex justify-start gap-4">--}}
                            {{--                                    <div>--}}
                            {{--                                       --}}
                            {{--                                    </div>--}}
                            {{--                                    <div>--}}
                            {{--                                        <button v-show="data.discount_type === 'am'" @click.prevent="data.discount_type = 'per'" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 text-white border-transparent">--}}
                            {{--                                            <i class="bx bxs-discount"></i>--}}
                            {{--                                        </button>--}}
                            {{--                                        <button v-show="data.discount_type === 'per'" @click.prevent="data.discount_type = 'am'" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 text-white border-transparent">--}}
                            {{--                                            %--}}
                            {{--                                        </button>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </x-splade-data>--}}
                        </div>

                        <x-splade-input
                            type="number"
                            placeholder="QTY"
                            v-model="items.main[key].qty"
                            @input="items.updateTotal(key)"
                        />
                        <x-splade-input

                            type="text"
                            placeholder="Item Name"
                            v-model="items.main[key].total"
                            @input="items.updateTotal(key)"
                        />
                        <button @click.prevent="items.addItem" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 text-white border-transparent">
                            <i class="bx bx-plus"></i>
                        </button>
                        <button @click.prevent="items.removeItem(item)" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm shadow-sm focus:ring-white filament-page-button-action bg-danger-600 hover:bg-danger-500 focus:bg-danger-700 focus:ring-offset-danger-700 text-white border-transparent">
                            <i class="bx bx-trash"></i>
                        </button>
                        <div v-if="items.main[key].item.has_options" v-for="(option, optionIndex) in items.main[key].item.product_metas[0].value">
                            <div class-="col-span-4">
                                <label for="">
                                    @{{ optionIndex.charAt(0).toUpperCase() + optionIndex.slice(1) }}
                                </label>
                                <x-splade-select choices v-model="items.main[key].options[optionIndex]">
                                    <option v-for="(value, valueIndex) in option" :value="value">
                                        @{{ value.charAt(0).toUpperCase() + value.slice(1) }}
                                    </option>
                                </x-splade-select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4 mt-4">
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Tax')}}
                        </div>
                        <div>
                            @{{ items.tax }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Sub Total')}}
                        </div>
                        <div>
                            @{{ items.price }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Discount')}}
                        </div>
                        <div>
                            @{{ items.discount }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-4 border-b">
                        <div>
                            {{__('Total')}}
                        </div>
                        <div>
                            @{{ items.total }}
                        </div>
                    </div>
                </div>
            </x-tomato-items>
        </div>



        <div class="flex justify-start gap-2 pt-3">
            <x-tomato-admin-submit  label="{{__('Save')}}" :spinner="true" />
            <x-tomato-admin-button danger :href="route('admin.invoices.destroy', $model->id)"
                                   confirm="{{trans('tomato-admin::global.crud.delete-confirm')}}"
                                   confirm-text="{{trans('tomato-admin::global.crud.delete-confirm-text')}}"
                                   confirm-button="{{trans('tomato-admin::global.crud.delete-confirm-button')}}"
                                   cancel-button="{{trans('tomato-admin::global.crud.delete-confirm-cancel-button')}}"
                                   method="delete"  label="{{__('Delete')}}" />
            <x-tomato-admin-button secondary :href="route('admin.invoices.index')" label="{{__('Cancel')}}"/>
        </div>
    </x-splade-form>
</x-tomato-admin-container>
