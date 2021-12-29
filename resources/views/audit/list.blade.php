@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')

<div style="margin: 10px; padding-top: 20px; padding-bottom: 20px;overflow: auto;">
<h1>Volumes</h1>
<hr>

<form action="/audit/perform">
@csrf
    <div class="mb-3">
        <label for="searchCarton" class="form-label">Qual caixa deseja</label>
        <input type="email" class="form-control" id="searchCarton" aria-describedby="searchCarton">
        <div id="searchCarton" name="searchCarton" class="form-text">Shiping HU</div>
    </div>
    <button type="submit" class="btn btn-primary">Procurar</button>
</form>
<hr>
@foreach ($cartons as $carton)
    <!--<h3>{{ $carton->shipping_hu }}</h3>
      -->
    <div class="card text-dark mb-3">
        <a href="/audit/show/{{ $carton->id }}"><div class="card-header">{{ $carton->shipping_hu }}</div></a>
        <div class="card-body">
            <table class="table">
            <thead>
                <tr>
                <th scope="col">Código Material</th>
                <th scope="col">Descrição</th>
                <th scope="col">Quantidade Embalada</th>
                <th scope="col">Documento</th>
                </tr>
            </thead>
            <tbody>
    @foreach ( $carton->itemsPacked as $product)
            <tr>
                <th scope="row">{{ $product->partnumber }}</th>
                <td>{{ $product->description }}</td>
                <td>{{ $product->pivot->packed_quantity }}</td>
                <td>{{ $carton->document }}</td>
            </tr>
    @endforeach
    </tbody>
    </table>
    </div>
</div>
@endforeach

@endsection