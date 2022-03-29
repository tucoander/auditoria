@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')

<div style="margin: 10px; padding-top: 20px; padding-bottom: 20px;overflow: auto;">
<h1>Upload de Volumes</h1>

<form action="/audit" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="formFile" class="form-label">Upload das caixas a serem auditadas</label>
        <input class="form-control" type="file" id="formFile" name="importedFile">
    </div>
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