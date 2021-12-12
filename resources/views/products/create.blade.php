@extends('layouts.main')

@section('title', 'Produtos')

@section('content')


<div>
    <div id="product-create-container" class="col-md6 offset-md3">
        <h1>Cria seu produto</h1>
        <form action="/products" method="POST">
            @csrf
            <div class="form-group">
                <label for="partnumber"></label>
                <input type="text" class="form-control" id="partnumber" name="partnumber" placeholder="Part number ou código do Material">
            </div>
            <div class="form-group">
                <label for="description"></label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Descrição do produto">
            </div>
            
            <input type="submit" value="Criar Evento" class="btn btn-primary" style="margin-top:20px;">
        </form>
    </div>
    <div>
        @if(session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
        @endif
    </div>
</div>



@endsection