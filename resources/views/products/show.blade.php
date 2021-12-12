@extends('layouts.main')

@section('title', 'Produtos')

@section('content')


<div>
    <div id="product-create-container" class="col-md6 offset-md3">
        <h1>Cria seu produto</h1>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Partnumber</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Criado em</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    
                    <tr>
                        <th scope="row">{{ $product->id }}</th>
                        <td>{{ $product->partnumber }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->created_at }}</td>
                        <td>
                            <a class="btn btn-primary" href="http://" target="_blank" rel="noopener noreferrer">Atualizar</a>
                            <a class="btn btn-primary" href="http://" target="_blank" rel="noopener noreferrer">Deletar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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