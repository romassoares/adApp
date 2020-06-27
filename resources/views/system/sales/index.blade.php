@extends('adminlte::page')
@section('title', 'Vendas')
@section('content_header')
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Venda</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="4">Itens</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>nome</th>
                                        <th>und</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($result as $sale)
                                    <tr>
                                        <td>{{$sale->id}}</td>
                                        <td>{{$sale->Product->description}}</td>
                                        <td>{{$sale->und}}</td>
                                        <td>
                                            <div class="form-group">
                                                <!-- <a href="{{route('venda.show',$ingredient->id)}}" class="text-primary m-1"><i class="fas fa-eye"></i></a> -->
                                                <a href="{{route('venda.edit',$product->id)}}" class="text-warning m-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{route('venda.remove',$product->id)}}" class="text-danger m-1"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="/venda/novo"><button class="btn btn-primary"><i class="fas fa-plus"></i> Novo</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection