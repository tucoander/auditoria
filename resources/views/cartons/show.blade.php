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
                        <th scope="col">HU</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Criado em</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartons as $carton)
                    
                    <tr>
                        <th scope="row">{{ $carton->id }}</th>
                        <td>{{ $carton->shipping_hu }}</td>
                        <td>{{ $carton->document }}</td>
                        <td>{{ $carton->created_at }}</td>
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