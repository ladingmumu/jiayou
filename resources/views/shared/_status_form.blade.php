<form method="POST" action="{{ route('statuses.store') }}">
{{ csrf_field() }}
@include('shared._errors')
    <textarea class="form-control" rows="3" placeholder="聊聊.." name="content">
        {{ old('content') }}
    </textarea>
    <button type="submit" class="btn btn-primary pull-right">
        发布
    </button>
</form>