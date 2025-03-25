@foreach (['success', 'error', 'warning', 'info'] as $msg)
    @if (session($msg))
        <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
            {{ session($msg) }}
        </div>
    @endif
@endforeach
