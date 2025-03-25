<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

abstract class DataGridController extends Controller
{
    // Retorna uma nova coleção de recursos
    protected function newCollection($resources, $columns): JsonResource
    {
        return JsonResource::collection($resources)->additional([
            'columns' => $columns,
        ]);
    }

    // Retorna um novo recurso individual
    protected function newResource($resource): JsonResource
    {
        return JsonResource::make($resource);
    }

    // Define as colunas que devem ser incluídas na consulta
    protected function columns(Request $request, Builder $query, array $baseColumns): array
    {
        $columns = $request->query('only');

        if (empty($columns)) {
            $columns = array_column($baseColumns, 'name');
        } else {
            // Extrai as colunas da query string e adiciona a chave primária
            $columns = explode(',', $request->query('only'));
            $columns[] = $query->getModel()->getKeyName();
        }

        return $columns;
    }

    // Define as relações que devem ser incluídas na consulta
    protected function include(Request $request, Builder $query): void
    {
        $include = $request->query('include');
        $include = explode('|', $include);
        $include = array_filter($include);
        $include = array_unique($include);

        // Itera sobre as relações a serem incluídas e define as colunas delas
        foreach ($include as $string) {
            [$relation, $columns] = explode(':', $string . ':');

            $query->with([
                $relation => function ($query) use ($columns) {
                    if ($columns) {
                        $columns = explode(',', $columns);
                        $columns[] = $query instanceof BelongsTo ? $query->getOwnerKeyName() : $query->getForeignKeyName();

                        $this->includeColumns($columns, $query);
                    }
                },
            ]);
        }
    }

    // Define a ordem de ordenação dos resultados na consulta
    protected function order(Request $request, Builder $query): void
    {
        $order = $request->query('order');
        if (empty($order)) {
            return;
        }

        $dir = ($request->query('dir'))?$request->query('dir'):'asc';

        // Separa as colunas e define a ordenação
        $columns = array_filter(explode('|', $order));

        $columns = array_map(static function ($columns) {
            return array_filter(explode(',', $columns));
        }, $columns);

        foreach ($columns as $column) {
            $query->orderBy($column[0], $column[1] ?? $dir);
        }
    }

    // Inclui as colunas na consulta
    protected function includeColumns(array $columns, $query): void
    {
        $columns = array_unique($columns);
        $columns = array_map('trim', $columns);

        $query->select($columns);
    }

    // Aplica filtros na consulta, se o modelo possuir o método "filter"
    protected function filter(Builder $builder, Request $request, array $baseColumns): void
    {
        if ($request->has('filter') && !empty($request->query('filter'))) {
            $filters = $request->query('filter');  // Obtemos o valor de 'filter'
            $filters = explode(',', $filters);  // Se forem múltiplos filtros, divide-os por vírgula
    
            foreach ($filters as $filter) {
                // Divide cada filtro no formato 'coluna:valor'
                list($column, $value) = explode(':', $filter);
    
                if (empty($column) || empty($value)) {
                    continue;  // Ignora filtros malformados
                }
    
                // Verifica se a coluna é de um relacionamento
                if (strpos($column, '.') !== false) {
                    list($relation, $relationColumn) = explode('.', $column);
    
                    // Adiciona o relacionamento na query
                    $builder->whereHas($relation, function ($query) use ($relationColumn, $value) {
                        $query->where($relationColumn, '=', $value);
                    });
                } else {
                    // Caso a coluna seja da tabela principal, realiza o filtro direto nela
                    $builder->where($column, '=', $value);
                }
            }
        }

        if ($request->has('search') && !empty($request->query('search'))) {
            $search = $request->query('search');
    
            // Verifica se a pesquisa é no formato 'valor:coluna'
            if (strpos($search, ':') !== false) {
                list($column, $searchValue) = explode(':', $search);
    
                // Verifica se a coluna é de um relacionamento
                if (strpos($column, '.') !== false) {
                    list($relation, $relationColumn) = explode('.', $column);
    
                    // Adiciona o relacionamento na query
                    $builder->whereHas($relation, function ($query) use ($relationColumn, $searchValue) {
                        $query->where($relationColumn, 'ilike', '%' . $searchValue . '%');
                    });
                } else {
                    // Caso a coluna seja da tabela principal, realiza o filtro direto nela
                    $builder->where($column, 'ilike', '%' . $searchValue . '%');
                }
            } else {
                $columns = array_column($baseColumns, 'name');

                $builder->where(function ($query) use ($columns, $search) {
                    foreach ($columns as $column) {
                        if(!empty($column)) {
                            $query->orWhere($column, 'ilike', '%' . $search . '%');
                        }
                    }
                });
            }
        }
        
        if (method_exists($builder, 'filter')) {
            $builder->filter($request->except('only', 'include', 'order', 'page', 'search', 'column'));
        }
    }
    

    // Retorna todos os recursos de um modelo, com suporte para paginação e filtros
    public function gridData(Model $model, Request $request, array $columns): JsonResource
    {
        // Cria uma nova query para o modelo
        $query = $model->newQuery();

        // Aplica as colunas, relações, ordenação e filtros
        $columns = $this->columns($request, $query, $columns);
        $this->includeColumns($columns, $query);
        $this->include($request, $query);
        $this->order($request, $query);

        $page = $request->query('page', 1);
        $show = $request->query('show', $query->getModel()->getPerPage());

        $this->filter($query, $request, $columns);

        // Retorna a coleção paginada
        return $this->newCollection(
            $query->paginate($show, $page),
            $columns
        );
    }

    // Realiza a validação dos dados de um modelo com base nas regras fornecidas
    protected function validation(Model $model, array $rules)
    {
        $validator = Validator::make(
            Arr::wrap($model),
            [$rules]
        );

        $validator->validate();
    }

    // Retorna as regras de validação (pode ser sobrescrito)
    protected function rules(Model $model, Request $request): array
    {
        return [];
    }
}
