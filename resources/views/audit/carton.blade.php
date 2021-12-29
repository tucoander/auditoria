@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')

<div >
<h1>Auditoria de {{ $carton->shipping_hu }}</h1>
<hr>

   <div class="card mb-3" id="table-div" >
        <div class="card-header">
            <div class="" >
                <label for="teste" class="form-label">Item</label>
                <input type="text" class="form-control" id="teste" aria-describedby="textHelp" onkeyup="cartonTableAudit()">
                <div id="textHelp" class="form-text" >Filtre o item a ser auditado.</div>
            </div>
        </div>
        <div class="card-body" style="border-bottom: 1px solid #aaa;" >
            
            <div class="row">
                <div style="font-weight: bold;" class="col-2 align-self-center text-center">Código Material</div>
                <div style="font-weight: bold;" class="col-2 align-self-center text-center">Descrição</div>
                <div style="font-weight: bold;" class="col align-self-center text-center">Qtd embalada</div>
                <div style="font-weight: bold;" class="col align-self-center text-center">Qtd auditada</div>
                <div style="font-weight: bold;" class="col align-self-center text-center">Falta</div>
                <div style="font-weight: bold;" class="col align-self-center text-center">Sobra</div>
                <div style="font-weight: bold;" class="col align-self-center text-center">Avaria</div>
                <div style="font-weight: bold;" class="col align-self-center text-center">Status</div>
            </div>
            </div>
            <div class="card-body" style="max-height: 250px; overflow: auto;">
    @foreach ( $carton->itemsPacked as $product)
            <form action="">
                <div class="row" style="border-bottom: 1px solid #aaa; padding: 10px 10px 10px 10px ;">
                    <div class="col-2 align-self-center text-center">
                        <button 
                            type="button" 
                            class="btn btn-outline-dark btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#itemAudit" 
                            data-bs-partnumber="{{ $product->partnumber }}"
                            data-bs-description="{{ $product->description }}"
                            data-bs-packed_quantity="{{ $product->pivot->packed_quantity }}"
                            data-bs-audit_quantity="{{ $product->pivot->audit_quantity }}"
                            data-bs-remaining="{{ $product->pivot->remaining }}"
                            data-bs-exceed="{{ $product->pivot->exceed }}"
                            data-bs-damaged_quantity="{{ $product->pivot->damaged_quantity }}"
                            data-bs-items_status="{{ $product->pivot->items_status }}"
                            data-bs-carton="{{ $carton->id }}">{{ $product->partnumber }}
                        </button>
                    </div>
                    <div class="col-2">{{ $product->description }}</div>
                    <div class="col align-self-center text-center">{{ $product->pivot->packed_quantity }}</div>
                    <div class="col align-self-center text-center">{{ $product->pivot->audit_quantity }}</div>
                    <div class="col align-self-center text-center">
                    {{ 
                    $product->pivot->packed_quantity - $product->pivot->audit_quantity > 0 && $product->pivot->audit_quantity > 0
                    ? $product->pivot->packed_quantity - $product->pivot->audit_quantity : 0 }}
                    </div>
                    <div class="col align-self-center text-center">{{ 
                    $product->pivot->audit_quantity - $product->pivot->packed_quantity > 0 && $product->pivot->audit_quantity > 0
                    ? $product->pivot->audit_quantity - $product->pivot->packed_quantity : 0 }}</div>
                    <div class="col align-self-center text-center">{{ $product->pivot->damaged_quantity }}</div>
                    
                    @if($product->pivot->items_status === '0')
                    <div class="col align-self-center text-center text-warning">
                    <i data-feather="alert-circle" ></i>
                    </div>
                    @else
                    <div class="col align-self-center text-center text-success" >
                    <i data-feather="check-circle"></i>
                    </div>
                    @endif
                </div>
                </form>
    @endforeach
            
            </tbody>
            </table>
            </div>
        
        </div>
        </div>
<div class="modal fade" id="itemAudit" tabindex="-1" aria-labelledby="itemAuditLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-ml">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemAuditLabel">Nova auditoria</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form  action="/audit/teste"  method="POST" id="modal-audit">
            @csrf
            <div class="mb-3 row">
                <label for="partnumber" class="col-sm-4 col-form-label"><b>Partnumber</b></label>
                <div class="col-sm-8">
                <input type="text" readonly class="form-control-plaintext" id="partnumber" name="partnumber">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="description" class="col-sm-4 col-form-label"><b>Description</b></label>
                <div class="col-sm-8">
                <input type="text" readonly class="form-control-plaintext" id="description" name="description">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3 row">
                        <label for="packed_quantity" class="col-sm-6 col-form-label"><b>Quantidade embalada:</b></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control-plaintext" id="packed_quantity" name="packed_quantity">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3 row">
                        <label for="audit_quantity" class="col-sm-6 col-form-label"><b>Quantidade auditada:</b></label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="audit_quantity" name="audit_quantity">
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="carton" name="carton" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" onclick="fecha()">Auditar</button>
      </div>
    </div>
  </div>
</div>

<script>
    function cartonTableAudit(){
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("teste");
    filter = input.value.toUpperCase();
    table = document.getElementById("table-div");
    tr = table.getElementsByClassName("row");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByClassName("col-2 align-self-center text-center")[0];
    
    if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
        }
    }

    }
    function fecha(){
        var myModalEl = document.querySelector('#itemAudit');
        var modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
        var getInput = document.getElementById('audit_quantity').valueAsNumber;
        if(!!getInput || getInput === 0){
            console.log(getInput+' não é nulo');
            if(getInput >= 0){
                salvaItem();
            }
            else{
                console.log('Número inserido não é valido, tipo '+ typeof getInput + ', Valor inserido: '+getInput);
                console.log(document.getElementById('audit_quantity'));
                //inserir mensagem de erro para campo com número negativo
            }
        }
        else{
            //inserir mensagem de erro para campo nulo ou com caractere errado
            console.log(getInput+' é nulo')
        }
        
    }

    function salvaItem() {
        var elements = document.getElementById("modal-audit")
        var token = document.querySelector("meta[name][content]").attributes[1].value 
        var formData = new FormData(); 
        for(var i=0; i<elements.length; i++) {
            formData.append(elements[i].name, elements[i].value);
        }
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                window.location.reload(true);
            }
        }
        xmlHttp.open("post", "/audit/item"); 
        xmlHttp.setRequestHeader('X-CSRF-Token', token);
        xmlHttp.send(formData);
    }
    

    var itemAudit = document.getElementById('itemAudit')
    itemAudit.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    
    // Extract info from data-bs-* attributes
    var recipient = button.getAttribute('data-bs-whatever')
    var data = {
        partnumber: button.getAttribute('data-bs-partnumber'),
        description: button.getAttribute('data-bs-description'),
        packed: button.getAttribute('data-bs-packed_quantity'),
        audit: button.getAttribute('data-bs-audit_quantity'),
        remaing: button.getAttribute('data-bs-remaining'),
        exceed: button.getAttribute('data-bs-exceed'),
        damaged: button.getAttribute('data-bs-damaged_quantity'),
        status: button.getAttribute('data-bs-items_status'),
        carton: button.getAttribute('data-bs-carton'),
    }

    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
    var modalTitle = itemAudit.querySelector('.modal-title')
    var partnumber = itemAudit.querySelector('#partnumber')
    var description = itemAudit.querySelector('#description')
    var packed = itemAudit.querySelector('#packed_quantity')
    var carton = itemAudit.querySelector('#carton')

    modalTitle.textContent = 'Auditoria'
    partnumber.value = data.partnumber
    description.value = data.description
    packed.value = data.packed
    carton.value = data.carton
    })
</script>


@endsection