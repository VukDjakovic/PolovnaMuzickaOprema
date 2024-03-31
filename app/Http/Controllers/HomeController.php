<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(isset(request()->cat)){
            $all_products = Product::whereHas('category', function($query){
                $query->whereName(request()->cat);
            })->get();
        }else{
            $all_products = Product::all();
        }
        $all_categories = Category::all();
        return view('home', compact('all_products', 'all_categories'));
    }

    public function wallet()
    {
        return view('home.wallet');
    }

    public function addWallet(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'balance'=>'required|max:4'
        ],
        [
            'balance.max'=>'Ne možete dodati više od 10 000!'
        ]);

        $user->balance = $user->balance + $request->balance;
        $user->save();

        return redirect(route('home.wallet'))->with('message', 'Stanje ažurirano!');
    }

    public function newProduct()
    {
        $categories = Category::all();
        return view('home.newProduct', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'price'=>'required',
            'city'=>'required',
            'contact'=>'required',
            'image1'=>'mimes:png,jpg,jpeg',
            'image2'=>'mimes:png,jpg,jpeg',
            'image3'=>'mimes:png,jpg,jpeg',
            'image4'=>'mimes:png,jpg,jpeg',
            'image5'=>'mimes:png,jpg,jpeg',
            'category'=>'required'
        ]);

        if(request()->hasFile('image1')){
            $image1 = request()->file('image1');
            $image1_name = time().'1'.$image1->extension();
            $image1->move(public_path('product_images'),$image1_name);
        }

        if(request()->hasFile('image2')){
            $image2 = request()->file('image2');
            $image2_name = time().'2'.$image2->extension();
            $image2->move(public_path('product_images'),$image2_name);
        }

        if(request()->hasFile('image3')){
            $image3 = request()->file('image3');
            $image3_name = time().'3'.$image3->extension();
            $image3->move(public_path('product_images'),$image3_name);
        }

        if(request()->hasFile('image4')){
            $image4 = request()->file('image4');
            $image4_name = time().'4'.$image4->extension();
            $image4->move(public_path('product_images'),$image4_name);
        }

        if(request()->hasFile('image5')){
            $image5 = request()->file('image5');
            $image5_name = time().'5'.$image5->extension();
            $image5->move(public_path('product_images'),$image5_name);
        }

        Product::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'description'=>$request->description,
            'city'=>$request->city,
            'contact'=>$request->contact,
            'image1'=>(isset($image1_name)) ? $image1_name : null,
            'image2'=>(isset($image2_name)) ? $image2_name : null,
            'image3'=>(isset($image3_name)) ? $image3_name : null,
            'image4'=>(isset($image4_name)) ? $image4_name : null,
            'image5'=>(isset($image5_name)) ? $image5_name : null,
            'user_id'=>Auth::user()->id,
            'category_id'=>$request->category
        ]);

        return redirect(route('home'))->with('message','Proizvod dodat!');
    }

    public function singleProduct($id)
    {
        $singleProduct = Product::find($id);
        return view('home.singleProduct', compact('singleProduct'));
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        if (Auth::id() !== $product->user_id) {
            return abort(403);
        }

        return view('home.editProduct', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (Auth::id() !== $product->user_id) {
            return abort(403);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->city = $request->city;
        $product->contact = $request->contact;
        $product->category_id = $request->category;

        if(request()->hasFile('image1')){
            $image1 = request()->file('image1');
            $image1_name = time().'1'.$image1->extension();
            $image1->move(public_path('product_images'),$image1_name);
        }

        if(request()->hasFile('image2')){
            $image2 = request()->file('image2');
            $image2_name = time().'2'.$image2->extension();
            $image2->move(public_path('product_images'),$image2_name);
        }

        if(request()->hasFile('image3')){
            $image3 = request()->file('image3');
            $image3_name = time().'3'.$image3->extension();
            $image3->move(public_path('product_images'),$image3_name);
        }

        if(request()->hasFile('image4')){
            $image4 = request()->file('image4');
            $image4_name = time().'4'.$image4->extension();
            $image4->move(public_path('product_images'),$image4_name);
        }

        if(request()->hasFile('image5')){
            $image5 = request()->file('image5');
            $image5_name = time().'5'.$image5->extension();
            $image5->move(public_path('product_images'),$image5_name);
        }

        $product->save();

        return redirect()->route('home.singleProduct', $id)->with('success', 'Proizvod uspešno ažuriran!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if (Auth::id() !== $product->user_id) {
            return abort(403);
        }

        $product->delete();

        return redirect()->route('home');
    }
}
