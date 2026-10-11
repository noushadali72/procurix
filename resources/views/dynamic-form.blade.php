<x-layouts.app>
    <h1>Dynamic Forms</h1>


    <div>
        <form action="" id="dynamicForm">
            <div class="mb-3">
                <label for="">Customer Name:</label>
                <input class="border-1 border-gray-400 rounded-md py-3 px-4 w-full text-gray-600 text-lg" type="text" name="customer_name" placeholder="Customer Name">

            </div>

            <div class="mb-3">
                <label for="">Email:</label>
                <input class="border-1 border-gray-400 rounded-md py-3 px-4 w-full text-gray-600 text-lg" type="email" name="customer_email" placeholder="Customer Email">
            </div>

            <div class="mb-3">
                <label for="">Phone: </label>
                <input class="border-1 border-gray-400 rounded-md py-3 px-4 w-full text-gray-600 text-lg" type="text" name="customer_phone" placeholder="Customer Phone no">
            </div>

            <div class="border-1 border-black p-4 my-5">
                <div class="material-row gap-3 grid grid-cols-12">
                    <select  class="material-select col-span-6 border-1 border-gray-400 rounded-md py-3 px-4 w-full text-gray-600 text-lg" name="materials[0][material_id]" id="materials" >
                        <option value="" selected>Select Material</option>
                        @foreach ($materials as $material)
                        <option data-cost-price="{{$material->cost_price}}" data-unit="{{$material->unit->short_name}}" value="{{$material->id}}">{{$material->name}}</option>
                        @endforeach
                    </select>
                    <input class="material-price col-span-3 border-1 border-gray-400 rounded-md py-3 px-4 w-full text-gray-600 text-lg"  type="text" value="" placeholder="Unit Cost">
                    <input class="material-unit col-span-3 border-1 border-gray-400 rounded-md py-3 px-4 w-full text-gray-600 text-lg"  type="text" value="" placeholder="Unit of Measure">
                </div>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            // console.log(@json($materials))

            $(document).on('change','.material-select',function(e){
                let select = $(this);
                let row = $(this).closest('.material-row');
                let selected = select.find(':selected');

                let price = parseFloat(selected.data('cost-price')).toFixed(2);
                row.find('.material-price').val(price||0)
                row.find('.material-unit').val(selected.data('unit')||0)
                
            });

        </script>
    @endpush
</x-layouts.app>