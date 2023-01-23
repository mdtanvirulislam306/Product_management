<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with('product_variant_price');
       
        return view('products.index',compact('products'));
    }

    public function getProductList(Request $request){

        $data = Product::with('product_variant_price');
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('title', function ($row) {
                return  ''.$row->title.' <br> Created at : '.date('d-F-Y', strtotime($row->created_at)).'';
            })
            ->editColumn('description', function ($row) {
                 return substr($row->description,0,50).'....';
            })
            ->addColumn('varient', function ($row) {
                $html ='';
                    $html =
                        '<dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">';
                            foreach ($row->product_variant_price as $value) {

                                $one = $value->variant_one->variant ?? '';
                                $two = $value->variant_two->variant ?? '';
                                $three = $value->variant_three->variant ?? '';
                                $html .='
                                <dt class="col-sm-3 pb-0">
                                    '.$one.'/'.$two.'/'.$three.'
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : '.number_format($value->price,2) .'</dt>
                                        <dd class="col-sm-8 pb-0">InStock : '.number_format($value->stock,2) .'</dd>
                                    </dl>
                                </dd>';
                            }
                            
                        $html .= '</dl> <button id="showMore" class="btn btn-sm btn-link">Show more</button>';
                return $html;
            })

            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group btn-group-sm">
                            <a href="'.route('product.edit', $row->id).'" class="btn btn-success">Edit</a>
                        </div>';
                return $btn;
            })

            ->rawColumns(['title','description ','varient','action'])->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
       $product = new Product;
       $product->title = $request->product_name;
       $product->sku = $request->product_sku;
       $product->description = $request->product_description;
       $product->save();
       $product_id = $product->id;
       foreach($request->product_variant as $index=>$product_variant){
        $product_variant = new ProductVariant;
       for ($i=0; $i < sizeof($request->product_variant[$index]['value']); $i++) { 
        if(isset($request->product_variant[$index]['value'])){
            $product_variant->variant = $request->product_variant[$index]['value'][$i];
        }else{
            $product_variant->variant = null;
        }
       if(isset($request->product_variant[$index]['option'])){
        $product_variant->variant_id = $request->product_variant[$index]['option'];
       }else{
        $product_variant->variant_id = null;
       }
       $product_variant->product_id = $product_id;
       $product_variant->save();
    }
   }
      
       return redirect()->back(); 


    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $product_variant = ProductVariant::where('product_id',$id)->get();
        $product_variant_price = ProductVariantPrice::where('product_id',$id)->get();
        //dd($product_variant_price);
        return view('products.edit', compact('product','product_variant','product_variant_price'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
