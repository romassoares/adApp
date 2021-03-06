@extends('adminlte::page')
@section('title', 'Vendas')
@section('content_header')
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vendas</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>R$</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            @endphp
                            @foreach($sales as $sale)
                            <tr>
                                <td>{{$i++}}</td>
                                @foreach($clients as $client)
                                @if($sale->client_id == $client->id)
                                <td {{$client->deleted_at != null ? "class=text-danger ": ''}}>
                                    {{$client->name}}
                                </td>
                                @endif
                                @endforeach
                                <td>{{$sale->getPrice()}}</td>
                                <td>
                                    <div class="form-group">
                                        <a href="{{route('venda.show',$sale->id)}}" class="text-primary m-1"><i class="fas fa-eye"></i></a>
                                        <a href="{{route('venda.edit',$sale->id)}}" class="text-warning m-1"><i class="fas fa-edit"></i></a>
                                        <a href="{{route('venda.remove',$sale->id)}}" class="text-danger m-1"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4 p-1">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                                Nova venda
                            </button>
                            <a href="{{route('venda.report')}}" class="btn btn-primary">
                                <i class="fas fa-chart-line"></i> Relatorio
                            </a>
                        </div>
                        <div class="col-md-4 p-1">
                            {{ $sales->links() }}
                        </div>
                        <div class="col-md-4 p-1">
                            <a href="{{route('venda.archive')}}"> <button class="btn btn-danger"> <i class="fas fa-archive"></i> Arquivos Removidos</button> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- clientes -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Escolher cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="{{route('venda.store')}}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group p-1" id="item">
                        <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true" name="client_id" id="client_id">
                            @foreach ($clients as $client)
                            <option value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group  p-1">
                        <button type="submit" class="btn btn-primary">iniciar venda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    function selecionar(e) {
        const item = document.querySelector('#client_id');
        if (item != '') {
            window.location.href = '/venda/' + item.value + '/novo'
        }
    }
</script> -->
@endsection