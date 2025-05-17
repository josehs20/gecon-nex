<!-- Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">Escaneie o QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="qrCodeImage" src="" alt="QR Code" style="max-width: 200px; display: none;">
                <p id="qrLoadingText">Gerando QR Code, por favor aguarde...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var routaGerarQrCode = @json(route('gerar.qr.code.recebimento'));
    function modalQR() {
        $('#qrModal').modal('show');
        // Faz a requisição AJAX para gerar o QR Code
        $.ajax({
            url: routaGerarQrCode, // Substitua pela URL da rota que gera o QR Code
            method: 'GET',
            success: function(response) {
                // Supondo que 'response.qrCodeUrl' seja a URL retornada pelo backend
                $('#qrCodeImage').attr('src', response.qrCodeUrl); // Define a URL da imagem
                $('#qrLoadingText').hide(); // Esconde o texto de carregamento
                $('#qrCodeImage').show(); // Mostra a imagem do QR Code
            },
            error: function() {
                $('#qrLoadingText').text('Erro ao gerar o QR Code. Tente novamente.');
            }
        });
    }
</script>
