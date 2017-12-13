<div class="modal" id="confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Apagar confirmação</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza de que deseja excluir?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="delete-btn">Excluir</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script>
    $('table[data-form="deleteForm"]').on('click', '.form-delete', function(e){
        e.preventDefault();
        var curItem = this;
        $('#confirm').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#delete-btn', function(confirmed){
                if (confirmed) {
                    location.href = curItem.href;
                }
            });
    });
</script>

<!-- Para validar formulario exemplo em view lista -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
@endsection
