@extends('posts.layoutposts')

@section('title', 'Kullanıcı İzinlerini Yönet')

@section('content')
    <div class="container mt-5">
        <h3>Kullanıcı İzin Yönetimi</h3>
        <p>Her kullanıcı için hangi işlemleri yapabileceğini buradan belirleyebilirsin.</p>

        <table class="table table-bordered mt-4">
            <thead>
            <tr>
                <th>Kullanıcı</th>
                <th>Rol</th>
                <th>İzinler</th>
                <th>Kaydet</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @if(!$user->hasRole('super-admin'))
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->getRoleNames()->first() }}</td>
                        <td>
                            <form action="{{ route('admin.permissions.update', $user->id) }}" method="POST">
                                @csrf
                                @foreach($permissions as $permission)
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            class="form-check-input"
                                            id="perm_{{ $user->id }}_{{ $permission->id }}"
                                            {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $user->id }}_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                            @endforeach
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Kaydet</button>
                        </td>
                        </form>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
