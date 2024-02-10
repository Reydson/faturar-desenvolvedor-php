//Seleciona os elementos do DOM
const addButton = document.querySelector('#addButton');
const clientsTable = document.querySelector('#clientsTable');
const templateClient = document.querySelector('#templateClient');
const pagination = document.querySelector('.pagination');
const modal = new bootstrap.Modal('#modalForm');
const modalForm = document.querySelector('#modalForm form');

let currentPage = 1;
let pageCount;

//Aplica a máscara no campo de telefone
const behaviorPhone = function (val) {
    return val.replace(/\D/g, "").length === 11 ? "(00) 00000-0000" : "(00) 0000-00009";
},
optionsPhone = {
    onKeyPress: function (val, e, field, optionsPhone) {
        field.mask(behaviorPhone.apply({}, arguments), optionsPhone);
    },
};
$("input[type=phone]").mask(behaviorPhone, optionsPhone);

//Exibe uma mensagem de erro
function showErrorMessage(message) {
    iziToast.error({
        message: message,
        position: 'topRight'
    });
}

//Exibe uma mensagem de sucesso
function showSuccessMessage(message) {
    iziToast.success({
        message: message,
        position: 'topRight'
    });
}

//Limpa a tabela de clientes
function clearClients() {
    const clients = document.querySelectorAll('#clientsTable tbody tr');
    clients.forEach((client) => client.remove());
}

//Atualiza a paginação da lista de clientes
function updatePagination() {
    //Checa se tem mais de uma página
    if(pageCount  == 1) {
        //Oculta caso só tenha uma
        pagination.classList.add('d-none');
    } else {
        pagination.classList.remove('d-none');
        const previousPageLink =  pagination.querySelector('.previous .page-link');
        const currentPageLink =  pagination.querySelector('.current .page-link');
        const nextPageLink =  pagination.querySelector('.next .page-link');

        //Atualiza o número na paginação
        currentPageLink.innerText = currentPage;
        //Desabilita/habilita o previous caso esteja ou não na primeira página
        if(currentPage == 1) {
            previousPageLink.classList.add('disabled');
        } else {
            previousPageLink.classList.remove('disabled');
        }
        //Desabilita/habilita o next caso esteja ou não na última página
        if(currentPage == pageCount) {
            nextPageLink.classList.add('disabled');
        } else {
            nextPageLink.classList.remove('disabled');
        }
        
    }
}

//Captura e trata o click na paginação
pagination.addEventListener("click", ( {target} ) => {
    if(target.classList.contains("previous") || target.parentElement.classList.contains("previous")) {
        loadClients(currentPage -1);
    } else if(target.classList.contains("next") || target.parentElement.classList.contains("next")) {
        loadClients(currentPage +1);
    }
});

//Busca pelos clientes no back-end e os adiciona na tabela
function loadClients(page = currentPage) {
    fetch(`./clients/${page}`)
    .then((response) => response.json())
    .then((json) => {
        if(json.result == 'success') {
            if(json.data.clients.length != 0) {
                clearClients();
                currentPage = page;
                pageCount = +json.data.pageCount;
                updatePagination();
                json.data.clients.forEach((client) => {
                    //Clona o modelo para criar um novo elemento
                    const clientNode = templateClient.content.cloneNode(true);
                    //Preenche os campos do clone
                    clientNode.querySelector('.id').innerText = client.id;
                    clientNode.querySelector('.name').innerText = client.name;
                    clientNode.querySelector('.email').innerText = client.email;
                    clientNode.querySelector('.phone').innerText = client.phone;
                    //Adiciona o id aos botões
                    clientNode.querySelector('.edit').dataset.id = client.id;
                    clientNode.querySelector('.delete').dataset.id = client.id;
                    //Adiciona o clone  na lista de clientes
                    templateClient.parentElement.appendChild(clientNode);
                })
            } else if(page > 1) {
                //Caso a página não possua clientes na página e não esteja na perimeira, busca pela anterior
                loadClients(--page);
            }
        } else {
            throw(new Error(json.message));
        }
    })
    .catch(error => showErrorMessage(error.message)); 
}

//carrega a primeira página de clientes ao entrar
loadClients();

//Abre a modal para a adição de um cliente
function launchCreationModal() {
    //Preenche a modal da forma adequada
    document.querySelector('#modalTitle').innerHTML = "Adicionar cliente";
    document.querySelector('#inputId').value = '';
    document.querySelector('#inputName').value = '';
    document.querySelector('#inputEmail').value = '';
    document.querySelector('#inputPhone').value = '';
    //Abre a modal de fato
    modal.show();
}

//Adiciona a abertura da modal para adicionar um cliente ao botão
addButton.addEventListener('click', launchCreationModal);

//Abre a modal para a edição de um cliente
function launchEditModal( {id, name, email, phone} ) {
    //Preenche a modal da forma adequada
    document.querySelector('#modalTitle').innerHTML = "Editar cliente";
    document.querySelector('#inputId').value = id;
    document.querySelector('#inputName').value = name;
    document.querySelector('#inputEmail').value = email;
    document.querySelector('#inputPhone').value = phone;
    //Abre a modal de fato
    modal.show();
}

clientsTable.addEventListener('click', ( {target} ) =>{
    const id = target.dataset.id || target.parentElement.dataset.id;
    //Busca pelos dados do cliente no back-end, os coloca na modal e a abre
    if(target.classList.contains("edit") || target.parentElement.classList.contains("edit")) {
        fetch(`./client/${id}`)
        .then(response => response.json())
        .then((json) => {
            if(json.result == 'success') {
                //lança a modal com os dados do cliente
                launchEditModal(json.data);
            } else {
                throw(new Error(json.message));
            }
        })
        .catch(error => showErrorMessage(error.message));
    }
    //Confirma se de fato deseja remover o cliente e envia a requisição para o back-end
    if(target.classList.contains("delete") || target.parentElement.classList.contains("delete")) {
        if(confirm('Tem certeza de que deseja remover o cliente?')) {
            fetch(`./client/${id}`, { method: 'DELETE' })
            .then(response => response.json())
            .then((json) => {
                if(json.result == 'success') {
                    //Exibe uma mensagem de sucesso e regarrega os clientes
                    showSuccessMessage('Cliente removido com sucesso');
                    loadClients();
                } else {
                    throw(new Error(json.message));
                }
            })
            .catch(error => showErrorMessage(error.message));
        }
    }
})

//Serializa o formulario da modal
function getFormData() {
    var formData = new FormData(modalForm);
    data = {};
    formData.forEach((value, name) => data[name] = value);
    return data;
}

//Função responsável por adicionar ou atualizar um cliente
modalForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = getFormData();

    fetch('./client/', {
        method: data.id ? 'PUT' : 'POST',
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then((json) => {
        if(json.result == 'success') {
            //Exibe uma mensagem de sucesso e regarrega os clientes
            showSuccessMessage('Cliente salvo com sucesso');
            loadClients();
            modal.hide();
        } else {
            throw(new Error(json.message));
        }
    })
    .catch(error => showErrorMessage(error.message));

});