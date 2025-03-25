@extends('layouts.app')
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-bg-success">Inclusão de Alvará via REDESIM</div>
                <div class="card-body">
                    <form method="GET" action="alvara">
                        <div class="form-group mb-2 col-4">
                            <label for="cpf_cnpj">CNPJ:</label>
                            <input
                                type="text"
                                class="form-control"
                                maxlength="14"
                                min="14"
                                id="cpf_cnpj"
                                value="{{ $cpfCnpj }}"
                                name="cpfCnpj"
                            >
                        </div>
                        <button type="submit" class="btn btn-success">Buscar</button>
                    </form>
                    @if(!empty($companies))
                        <div class="table-responsive mt-4">
                            <table class="table align-middle table-striped table-bordered table-hover">
                                <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Endereço</th>
                                    <th>Simples</th>
                                    <th>MEI</th>
                                    <th>Situação</th>
                                    <th>Data Abertura</th>
                                    <th>Data Encerramento</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($companies) === 0)
                                    <tr>
                                        <td colspan="5">
                                            Nenhum registro encontrado.
                                        </td>
                                    </tr>
                                @endif
                                @foreach($companies as $companie)
                                    <tr>
                                        <td>{{ $companie->id }}</td>
                                        <td>{{ $companie->razaoSocial }}</td>
                                        <td>{{ $companie->cpfCnpj }}</td>
                                        <td>{{ "{$companie->endereco->getLogradouroEcidadeFormat()} {$companie->endereco->numero} {$companie->endereco->complemento} {$companie->endereco->bairro} {$companie->endereco->cep}" }}</td>
                                        <td>{{ $companie->simples ? 'Sim' : 'Não' }}</td>
                                        <td>{{ $companie->mei ? 'Sim' : 'Não' }}</td>
                                        <td>{{ $companie->situacao}}</td>
                                        <td>{{ $companie->abertura instanceof DateTime ? $companie->abertura->format('d/m/Y H:i') : '' }}</td>
                                        <td>{{ $companie->encerramento instanceof DateTime ? $companie->encerramento->format('d/m/Y H:i') : '' }}</td>
                                        <td><button type="button" onclick="handleSelectRow({{ $companie->id }})" class="btn btn-success btn-sm">Importar</button></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function handleSelectRow(companyId) {
            Swal.fire({
                title: "Tem certeza?",
                text: "Deseja importar o cadastro da empresa " + companyId + "?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "var(--ecidade-contass-default-btn-bg)",
                cancelButtonColor: "var(--ecidade-contass-danger-btn-bg)",
                confirmButtonText: "Sim, importar!",
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const formData = new FormData();
                        formData.append('id', companyId);

                        const response = await fetch(`alvara/create`, {
                            method: 'POST',
                            body: formData
                        });
                        if (!response.ok) {
                            return Swal.showValidationMessage(`${JSON.stringify(await response.json())}`);
                        }
                        return response.json();
                    } catch (error) {
                        Swal.showValidationMessage(`Ocorreu um erro: ${error}`);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(async (result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Sucesso!",
                        text: "Alvará criado com sucesso!",
                        icon: "success"
                    });
                }
            });
        }
    </script>
@endsection
