@extends('master')

@section('content_header')
    <h1>Cadastrar Equipamento</h1>
@stop

@section('content')
    @include('messages.flash')
    @include('messages.errors')
<p>
    <a href="{{ route('equipamentos.create') }}" class="btn btn-success">
        Adicionar Equipamento
    </a>
</p>

<div class="panel panel-default">
  <div class="panel-heading">Filtros</div>
  <div class="panel-body">

    <form method="get" action="/equipamentos">
        <div>
            <label class="checkbox-inline"><input type="checkbox" name="vencidos" value="true">Vencidos</label>
            <label class="checkbox-inline"><input type="checkbox" name="naoalocados" value="true">Não Alocados</label>
        </div>
        <br>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Patrimônio, Mac Address, IP, Local ou descrição" name="search">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success"> Buscar </button>
            </span>
        </div><!-- /input-group -->
    </form>

  </div>
</div>

<br>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>MAC Address</th>
                <th>Identificação/patrimônio</th>
                <th>IP</th>
                <th>Rede</th>
                <th>Vencimento</th>
                <th>Responsável</th>
                <th colspan="2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipamentos as $equipamento)
            <tr>
                <td><a href="/equipamentos/{{ $equipamento->id }}"> {{ $equipamento->macaddress }}</a></td>
                <td>{{ $equipamento->descricaosempatrimonio ?? $equipamento->patrimonio }}</td>
                <td>{{ $equipamento->ip ?? '' }}</td>
                <td><i>{{ $equipamento->rede->nome ?? '' }}</i>
                    @isset ($equipamento->rede->iprede)
                        @can('admin')
                            <a href="/redes/{{$equipamento->rede->id}}">{{ $equipamento->rede->iprede ?? '' }}/{{ $equipamento->rede->cidr ?? '' }}</a>
                        @elsecannot('admin')
                            {{ $equipamento->rede->iprede ?? '' }}/{{ $equipamento->rede->cidr ?? '' }}
                        @endcan
                    @endisset
                </td>

                <td>{{ \Carbon\Carbon::CreateFromFormat('Y-m-d', $equipamento->vencimento)->format('d/m/Y') }}</td>

                <td>
                    @can('admin')
                        <a href="/users/{{$equipamento->user->username}}">{{ $equipamento->user->name ?? '' }}</a>
                    @elsecannot('admin')
                        {{ $equipamento->user->name ?? '' }}
                    @endcan
                </td>

                <td>
                    <a href="{{action('EquipamentoController@edit', $equipamento->id)}}" class="btn btn-warning">Editar</a>
                </td>
                <td>
                    <form action="{{action('EquipamentoController@destroy', $equipamento->id)}}" method="post">
                      {{csrf_field()}} {{ method_field('delete') }}
                      <button class="delete-item btn btn-danger" type="submit">Deletar</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $equipamentos->appends(request()->query())->links() }}
</div>

@stop
