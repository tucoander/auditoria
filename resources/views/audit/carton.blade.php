@extends('layouts.main')

@section('title', 'Upload Auditoria')

@section('content')

<div style="margin: 10px; padding: 0 20px 20px 20px; max-height: 450px; overflow: auto;">
<h1>Auditoria</h1>
<hr>
   
   <div class="card text-dark bg-light mb-3">
        <div class="card-header">{{ $carton->shipping_hu }}</div>
        <div class="card-body">
            
            <div class="row">
                <div style="font-weight: bold;" class="col-2">Código Material</div>
                <div style="font-weight: bold;" class="col-2">Descrição</div>
                <div style="font-weight: bold;" class="col">Qtd embalada</div>
                <div style="font-weight: bold;" class="col">Qtd auditada</div>
                <div style="font-weight: bold;" class="col">Falta</div>
                <div style="font-weight: bold;" class="col">Sobra</div>
                <div style="font-weight: bold;" class="col">Avaria</div>
                <div style="font-weight: bold;" class="col">Status</div>
            </div>
            <hr>
    @foreach ( $carton->itemsPacked as $product)
            <form action="">
                <div class="row">
                    <div class="col-2">
                        <button 
                            type="button" 
                            class="btn btn-outline-dark btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#exampleModal" 
                            data-bs-partnumber="{{ $product->partnumber }}"
                            data-bs-description="{{ $product->description }}"
                            data-bs-packed_quantity="{{ $product->pivot->packed_quantity }}"
                            data-bs-audit_quantity="{{ $product->pivot->audit_quantity }}"
                            data-bs-remaining="{{ $product->pivot->remaining }}"
                            data-bs-exceed="{{ $product->pivot->exceed }}"
                            data-bs-damaged_quantity="{{ $product->pivot->damaged_quantity }}"
                            data-bs-items_status="{{ $product->pivot->items_status }}"
                            data-bs-carton="{{ $carton->shipping_hu }}">{{ $product->partnumber }}
                        </button>
                    </div>
                    <div class="col-2">{{ $product->description }}</div>
                    <div class="col">{{ $product->pivot->packed_quantity }}</div>
                    <div class="col">{{ $product->pivot->audit_quantity }}</div>
                    <div class="col">
                    {{ 
                    $product->pivot->packed_quantity - $product->pivot->audit_quantity > 0 && $product->pivot->audit_quantity > 0
                    ? $product->pivot->packed_quantity - $product->pivot->audit_quantity : 0 }}
                    </div>
                    <div class="col">{{ 
                    $product->pivot->audit_quantity - $product->pivot->packed_quantity > 0 && $product->pivot->audit_quantity > 0
                    ? $product->pivot->audit_quantity - $product->pivot->packed_quantity : 0 }}</div>
                    <div class="col">{{ $product->pivot->damaged_quantity }}</div>
                    <div class="col">{{ $product->pivot->items_status === '0' ? 'Aberta' : 'Finalizada' }}</div>
                   
                </div>
                <hr>
                </form>
    @endforeach
            
            </tbody>
            </table>
            </div>
        
        </div>
        </div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-ml">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
            
            <div class="mb-3 row">
                <label for="partnumber" class="col-sm-4 col-form-label"><b>Partnumber</b></label>
                <div class="col-sm-8">
                <input type="text" readonly class="form-control-plaintext" id="partnumber" value="">
                </div>
                
            </div>
            <div class="mb-3 row">
                <label for="description" class="col-sm-4 col-form-label"><b>Description</b></label>
                <div class="col-sm-8">
                <input type="text" readonly class="form-control-plaintext" id="description" value="">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="mb-3 row">
                        <label for="packed_quantity" class="col-sm-6 col-form-label"><b>Quantidade embalada:</b></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control-plaintext" id="packed_quantity" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3 row">
                        <label for="audit_quantity" class="col-sm-6 col-form-label"><b>Quantidade auditada:</b></label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="audit_quantity" value="">
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary">Auditar</button>
      </div>
    </div>
  </div>
</div>

<script>
    var exampleModal = document.getElementById('exampleModal')
    exampleModal.addEventListener('show.bs.modal', function (event) {
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
    var modalTitle = exampleModal.querySelector('.modal-title')
    var partnumber = exampleModal.querySelector('#partnumber')
    var description = exampleModal.querySelector('#description')
    var packed = exampleModal.querySelector('#packed_quantity')
 

    modalTitle.textContent = 'Auditoria'
    partnumber.value = data.partnumber
    description.value = data.description
    packed.value = data.packed
    console.log(data.packed);
    })

</script>


@endsection