@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Consulta de Auditorias</div>

                    <div class="card-body">
                        <form method="GET" action="audits">
                            <div class="form-group mb-2 col-3">
                                <label for="model">Tabela:</label>
                                <select name="model" class="form-select">
                                    @foreach($models as $model)
                                        <option value="{{ $model }}" {{ $selectedModel === $model ? 'selected' : '' }}>{{ class_basename($model) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-2 col-3">
                                <label for="event">Evento:</label>
                                <select name="event" class="form-select">
                                    <option value="" {{ empty($selectedEvent) ? 'selected' : '' }}>Todos</option>
                                    <option value="created" {{ $selectedEvent === 'created' ? 'selected' : '' }}>Criação</option>
                                    <option value="updated" {{ $selectedEvent === 'updated' ? 'selected' : '' }}>Atualização</option>
                                    <option value="deleted" {{ $selectedEvent === 'deleted' ? 'selected' : '' }}>Exclusão</option>
                                </select>
                            </div>

                            <div class="form-group mb-2 col-3">
                                <label for="data_inicio">Data de Início:</label>
                                <input type="text" class="form-control" id="data_inicio" value="{{$selectedStartDate ?? ''}}" name="data_inicio">
                            </div>
                            <div class="form-group mb-2 col-3">
                                <label for="data_fim">Data Final:</label>
                                <input type="text" class="form-control" id="data_fim" value="{{$selectedEndDate ?? ''}}" name="data_fim">
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>

                        @if($audits)
                            <div class="table-responsive mt-4">
                                <table class="table align-middle table-striped table-bordered">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Modelo</th>
                                        <th>Ação</th>
                                        <th>Usuário</th>
                                        <th>Data</th>
                                        <th>Dados Modificados</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($audits->items()) === 0)
                                        <tr>
                                            <td colspan="5">
                                                Nenhum registro encontrado.
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach($audits as $audit)
                                        <tr>
                                            <td>{{ class_basename($audit->auditable_type) }}</td>
                                            <td>{{ $audit->event }}</td>
                                            <td>{{ $audit->user->nome ?? 'N/A' }}</td>
                                            <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <table class="table table-bordered mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Campo</th>
                                                            <th>Antes</th>
                                                            <th>Depois</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($audit->getModified() as $field => $value)
                                                        <tr>
                                                            <td>{{ $field }}</td>
                                                            <td>{{ $value['old'] ?? '' }}</td>
                                                            <td>{{ $value['new'] ?? '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{ $audits->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        flatpickr("#data_inicio", {
            dateFormat: "d/m/Y"
        });
        flatpickr("#data_fim", {
            dateFormat: "d/m/Y"
        });
    </script>
@endsection
