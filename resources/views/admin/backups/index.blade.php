@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Lista de Backups</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data da Geração</th>
                    <th>Tamanho</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($backups as $backup)
                <tr>
                    <td>{{ $backup['file'] }}</td>
                    <td>{{ $backup['date'] }}</td>
                    <td>{{ $backup['size'] }}</td>
                    <td>
                        <a href="{{ $backup['downloadUrl'] }}" target="_blank" class="btn btn-xs btn-success">Download</a>
                        <a href="{{ $backup['deleteUrl'] }}" class="btn btn-xs btn-danger" onclick="return confirm('Tem certeza que deseja excluir este backup?')">Excluir</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Nenhum backup encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection