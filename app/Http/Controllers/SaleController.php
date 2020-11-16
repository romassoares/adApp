<?php

namespace App\Http\Controllers;

use App\Client;
use App\Product;
use App\Sale;
use App\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    private $obj;
    private $prod;
    public function __construct(Sale $obj, Product $prod, Items $item)
    {
        $this->obj = $obj;
        $this->prod = $prod;
        $this->item = $item;
    }

    public function index()
    {
        $sales = $this->obj->paginate(10);
        $clients = Client::withTrashed()->get();
        return view('system.sales.index', ['sales' => $sales, 'clients' => $clients]);
    }

    public function store(Request $request, Sale $sale)
    {
        $products = Product::all();
        $client = Client::withTrashed('client_id',$request->client_id)->get()->first();
        if($client){
            $sale->client_id = $client->id;
            $save = $sale->save();
                if($save){
                    $items = $this->item->get()->where('sale_id', $sale->id);
                    return view('system.sales.form', ['products' => $products, 'client' => $client, 'sale'=>$sale, 'items'=>$items]);
                }
            }else{
                $client = Client::create(['name' => 'default']);
                $save = new Sale();
                $result = $save->create(['client_id' => $client->id]);
                if($result){
                    $items = $this->item->get()->where('sale_id',$result->id);
                    return view('system.sales.form', ['products' => $products, 'client' => $client, 'sale'=>$result, 'items'=>$items]);
            }
        }
    }
    
    public function addProduct(Request $request, $id) {
        $product = $this->prod->find($request->product_id);
        if ($product && $product->amount >= $request->amount) {
            $item = Items::where('product_id', $product->id)->where('sale_id', $id)->get()->first();
            DB::beginTransaction();
            if ($item) {
                $item->amount += $request->amount;
            } else {
                $item = new Items();
                $item->amount = $request->amount;
                $item->sale_id = $id;
                $item->price = $product->price;
                $item->product_id = $product->id;
            }

            $save = $item->save();
            if ($save) {
                $product->amount -= $request->amount;
                $save = $product->save();
                if ($save) {
                    DB::commit();
                    return redirect()->route('venda.edit', $id)->with('success', 'Adicionado');
                } else {
                    DB::rollBack();
                    return redirect()->route('venda.edit', $id)->with('error', 'Falha ao atualizar quantidade no estoque');    
                }
            } else {
                DB::rollBack();
                return redirect()->route('venda.edit', $id)->with('error', 'Falha ao atualizar quantidade na venda');        
            }
        } else {
            return redirect()->route('venda.edit', $id)->with('error', 'estoque insuficiente');            
        }
    }

    public function edit($id)
    {
        $sale = Sale::findorfail($id);  
        $items = $this->item->get()->where('sale_id',$id);
        $client = Client::withTrashed('client_id','==',$sale->client_id)->get()->first(); 
        $products = Product::all();
        return view('system.sales.form', ['products' => $products, 'client' => $client, 'items'=>$items, 'sale'=>$sale]);
    }

    public function show($id)
    {
        $sale = Sale::findorfail($id);
        $items = Items::withTrashed('sale_id',$id)->get();
        return view('system.sales.show', ['items'=>$items, 'sale'=>$sale]);
    }
    public function destroy($id)
    {
        $product = $this->obj->findorfail($id);
        if($product){
            $result = $product->delete();
            if($result){
                return redirect()->route('venda')->with('success','venda removido com sucesso');
            }
        }else{
            return redirect()->route('venda')->with('warning', 'erro, venda não encontrado');
        }
    }

    public function archive()
    {
        $result = $this->obj->withTrashed()->where('deleted_at', '!=', null)->get();
        return view('system.sales.deleted', compact('result'));
    }

    public function restory($id){
        $result = $this->obj->withTrashed()->where('id',$id)->first();
        if($result){
            $res = $result->restore();
            if($res){
                return redirect()->route('venda.show',$id)->with('success','arquivo restaurado com sucesso');
            }
        }
    }
}


