<?php
require_once(__DIR__ . '/../header.php');
?>
<h1 class="my-3">Gerenciar clientes</h1>
<button type="button" class="btn btn-success" id="addButton"><i class="far fa-plus"></i> Adicionar cliente</button>
<div class="table-responsive">
    <table class="table table-striped" id="clientsTable">
        <thead>
            <tr>
                <td>ID</td>
                <td>Nome</td>
                <td>Email</td>
                <td>Telefone</td>
                <td>Ações</td>
            </tr>
        </thead>
        <tbody>
            <template id="templateClient">
                <tr>
                    <td class="id"></td>
                    <td class="name"></td>
                    <td class="email"></td>
                    <td class="phone"></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-info edit my-1 my-md-1"><i class="far fa-edit"></i> <span class="d-none d-md-inline">Editar</span></button>
                        <button type="button" class="btn btn-danger my-1 my-md-1 ms-md-2 delete"><i class="fas fa-trash"></i> <span class="d-none d-md-inline">Remover</span></button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
<ul class="pagination">
    <li class="page-item previous" aria-label="« Anterior">
        <span class="page-link">‹</span>
    </li>
    <li class="page-item current" aria-current="page">
        <span class="page-link disabled">1</span>
    </li>
    <li class="page-item next" aria-label="Próximo »">
        <span class="page-link">›</span>
    </li>
</ul>
<div class="modal" tabindex="-1" id="modalForm">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Adicionar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="inputId" name="id">
                <div class="mb-3">
                    <label for="inputName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="inputName" name="name" minlength="5" maxlength="30" required>
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail" name="email" minlength="5" maxlength="30" required>
                </div>
                <div class="mb-3">
                    <label for="inputPhone" class="form-label">Telefone</label>
                    <input type="phone" class="form-control" id="inputPhone" name="phone" minlength="14" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>
</div>

<?php
require_once(__DIR__ . '/../footer.php');
?>