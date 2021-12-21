@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')


<div class="card" style="margin-top: 10px; height: 72vh;">
    <div class="card-body">
        <h1>{{$msg}}</h1>

        <div style="margin-top: 20px;">
            <table class="table table-dark table-striped">
                @foreach ($table as $key => $value)
                @if($key === 0)
                <thead>
                    <tr>
                        @foreach($value as $iter => $column_value)
                        @if(array_key_exists( $iter,$index))
                        <th scope="col">{{ $column_value}}</th>
                        @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @else
                
                    @if($key === 1)
                    @else
                    <tr>
                        @foreach($value as $iter => $column_value)
                        @if(array_key_exists( $iter,$index))
                        <td>{{ $column_value}}</td>
                        @endif
                        @endforeach
                    </tr>
                    @endif
                
                @endif

                @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>



@endsection