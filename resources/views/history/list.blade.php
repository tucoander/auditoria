@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')

    <div >
        <h1>Histórico</h1>
        <div class="card ">
            <div class="card-body">
                <form class="row g-3" method="GET" id="history">
                @csrf
                    <div class="col-md-3">
                        <label for="inputHu" class="form-label">HU</label>
                        <input type="text" class="form-control" id="inputHu" name="inputHu">
                    </div>
                    <div class="col-md-3">
                        <label for="inputDateFrom" class="form-label">De:</label>
                        <input type="date" class="form-control" id="inputDateFrom" name="inputDateFrom">
                    </div>
                    <div class="col-md-3">
                        <label for="inputDateTo" class="form-label">Para:</label>
                        <input type="date" class="form-control" id="inputDateTo" name="inputDateTo">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-dark form-control" style="margin: 1px;" onclick="exportHistory()">
                            Exportar
                            <i data-feather="download" style="height: 20px; width: 20px; padding: -5px"></i>
                        </button>
                        <button type="button" class="btn btn-dark form-control" style="margin: 1px;" onclick="filterHistory()">
                            Filtrar
                            <i data-feather="filter" style="height: 20px; width: 20px; padding: -5px" ></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card " style="min-height: 50vh; max-height: 61vh;">
            <div class="card-body">
            <table 
            class="table table-striped" 
            id="history-header" 
            >
                    <thead>
                        <tr>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Data de auditoria</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">HU</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Item</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Quantidade embalada</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Quantidade auditada</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Quantidade em falta</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Quantidade em sobra</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Quantidade avariada</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Auditor</th>
                            <th scope="col-2" style="position: sticky; top: 0; background: #fff">Informações</th>
                        </tr>
                    </thead>
                
                    <tbody>
                    <div style="max-height: 500px; overflow: auto;">
            @foreach($cartons as $carton)
                        @foreach ($carton->itemsPacked as $line)
                        <tr>
                            <td scope="col-2">{{ $carton->updated_at}}</td>
                            <td scope="col-2">{{ $carton->shipping_hu}}</td>
                            <td scope="col-2">{{ $line->partnumber}}</td>
                            <td scope="col-2">{{ $line->pivot->packed_quantity}}</td>
                            <td scope="col-2">{{ $line->pivot->audit_quantity}}</td>
                            <td scope="col-2">{{ $line->pivot->remaining_quantity}}</td>
                            <td scope="col-2">{{ $line->pivot->exceed_quantity}}</td>
                            <td scope="col-2">{{ $line->pivot->damaged_quantity}}</td>
                            <td scope="col-2">{{ $line->pivot->audit_user}}</td>
                            <td scope="col-2">{{ $carton->observations}}</td>
                        </tr>
                        @endforeach
            @endforeach
            </div>
                    </tbody>
                
            </table>
            </div>
        </div>
        <script>
        function filterHistory(){
            document.getElementById("history").submit(); 
        }
        function exportHistory(){
            // var inputHu = document.getElementById("inputHu").value;
            // var inputDateFrom = document.getElementById("inputDateFrom").value;
            // var inputDateTo = document.getElementById("inputDateTo").value;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);

            var data = {
                'inputHu': urlParams.get('inputHu'),
                'inputDateFrom':  urlParams.get('inputDateFrom'),
                'inputDateTo': urlParams.get('inputDateTo'),
            };

            const searchParams = new URLSearchParams(data);
            var url = "{{URL::to('/audit/history/export')}}?" + searchParams;
            console.log(url);
            window.location = url;
        }
        </script>
    @endsection
