@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')

<div style="margin: 10px; padding-top: 20px; padding-bottom: 20px;overflow: auto;">
<h1>Upload de Volumes</h1>

<form action="/audit" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- <div class="mb-3">
        <label for="formFile" class="form-label">Faça o upload da caixa auditada</label>
        <input class="form-control" type="file" id="formFile">
    </div> -->

    <!-- <div class="mb-3">
        <label for="shipping_hu" class="form-label">Caixa Auditada</label>
        <input type="text" class="form-control" id="shipping_hu" name="shipping_hu" placeholder="Digite o número da caixa">
    </div> -->

    <div class="mb-3">
        <label for="formFile" class="form-label">Upload das caixas a serem auditadas</label>
        <input class="form-control" type="file" id="formFile" name="importedFile">
    </div>


    <!-- @foreach ($products as $product)
    <div class="row">
        <div class="col form-check">
            <input class="form-check-input" type="checkbox" value="{{ $product->id }}" id="{{ $product->id }}"  name="products[{{ $product->partnumber }}]" onchange="allowQuantity('{{$product->id}}')">
            <label class="form-check-label" for="flexCheckDefault">
                {{ $product->partnumber }}

            </label>

        </div>
        <input 
            name="packed_quantity[{{ $product->id }}]" 
            id="{{ $product->id }}-quantity" 
            class="col form-control form-control-sm" 
            type="text" 
            placeholder="Quantidade" 
            aria-label=".form-control-sm example" 
            style="display: none;"
            disabled>
    </div>
    @endforeach -->
    <input type="submit" value="Criar Caixa" class="btn btn-primary" style="margin-top:20px;">

</form>


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