@extends('layouts.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>
    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" id="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="variant" class="form-control">
                        <option> -- Select Variant -- </option>
                        @foreach(App\Models\Variant::all() as $variant)
                            <optgroup label="{{ $variant->title }}">
                                @foreach($variant->product_variant as $variant_option)
                                    <option value="{{ $variant_option->variant}}">{{ $variant_option->variant}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" id="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" id="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" id="filter_date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="button" id="filterBtn" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Variant</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        
                    </tbody>

                </table>
            </div>

        </div>

        
    </div>


  


    <script type="text/javascript">
        
        function getData(title = '', variant = '', price_from='', price_to= '', filter_date= ''){
            var table = jQuery('#dataTable').DataTable({
                dom: 'Brftlip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                lengthChange:false,
                searching: false,
                ajax: {
                    url: "{{ url('fetech/product/data') }}"+"?title="+title+"&variant="+variant+"&price_from="+price_from+"&price_to="+price_to+"&filter_date="+filter_date,
                    type: 'GET',
                },
                
                columns: [{
                        data: 'id',
                        "className": "text-center",
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'title',
                        name: 'product.title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'varient',
                        name: 'varient',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        "className": "text-center"
                    },
                ]
            })
        }
        
        //jQuery('#dataTable').DataTable().destroy();
        getData();
        jQuery('#filterBtn').click(function(){
            let title = jQuery('#title').val();
            let variant = jQuery('#variant').val();
            let price_from = jQuery('#price_from').val();
            let price_to = jQuery('#price_to').val();
            let filter_date = jQuery('#filter_date').val();
            //jQuery('#dataTable').DataTable().destroy();
            // $('#dataTable').DataTable().ajax.reload();
            getData(title,variant,price_from,price_to,filter_date);
        })
        jQuery('#showMore').click(function(){
            $("#variant").addClass("h-auto");
        })

    </script>
@endsection