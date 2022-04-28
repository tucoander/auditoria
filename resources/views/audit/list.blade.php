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
        <div class="row m-1">
            @foreach ($cartons as $carton)
                <!--<h3>{{ $carton->shipping_hu }}</h3>
          -->
                <div class="col-md-6 p-1">
                    <div class="card">
                        <div class="card-header">
                            <div class="container-fluid m-0">
                                <div class="d-flex justify-content-between">
                                    <div>{{ $carton->shipping_hu }}</div>
                                    @if (is_null($carton->status))
                                        <div>
                                            <span class="badge bg-danger" style="right: 0;">Pendente</span>
                                        </div>
                                    @elseif($carton->status == 2)
                                        <div>
                                            <span class="badge bg-warning" style="right: 0;">Em andamento</span>
                                        </div>
                                    @else
                                        <div>
                                            <span class="badge bg-sucess" style="right: 0;">Completo</span>
                                        </div>
                                    @endif
                                </div>
                            </div>



                        </div>
                        <div class="card-body">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Código Material</th>
                                        <th scope="col">Descrição</th>
                                        <th scope="col">Documento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carton->itemsPacked as $product)
                                        <tr>
                                            <th scope="row">{{ $product->partnumber }}</th>
                                            <td>{{ $product->description }}</td>
                                            <td>{{ $carton->document }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row justify-content-end p-1">
                                <a href="/audit/show/{{ $carton->id }}" class="btn btn-dark w-25">Auditar</a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endsection
