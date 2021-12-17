@extends('layouts.main')

@section('title', 'Produtos')

@section('content')


<div>
    <div id="product-create-container" class="col-md6 offset-md3">
        <h1>Cria sua caixa</h1>
        <form action="/cartons" method="POST">
            @csrf
            <div class="form-group">
                <label for="shipping_hu"></label>
                <input type="text" class="form-control" id="shipping_hu" name="shipping_hu" placeholder="Identificação do volume">
            </div>

            <div class="form-group">
                <label for="document"></label>
                <input type="text" class="form-control" id="document" name="document" placeholder="Documento de venda">
            </div>
            
            <input type="submit" value="Criar Caixa" class="btn btn-primary" style="margin-top:20px;">
        </form>
    </div>
    <div style="margin-top: 20px;">
        @if(session('msg'))
        @if(session('status') === 1)
        <div class="alert alert-danger" role="alert">
            {{ session('msg') }}
            
        </div>
        @else
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
            
        </div>
        @endif
        @endif
    </div>
</div>



@endsection