@extends('posts.layoutposts')

@section('title', 'Kullanıcı')
@section('main-title', 'Kullanıcı düzenle')
@section('subtitle', 'Detay')

@section('content')

    <div class="card-body">
    <h1>{{ $user->name }} için Rol Düzenle</h1>

    <form method="POST" action="{{ route('admin.update-role', $user->id) }}">
        @csrf
        <label >Yeni Rol:   </label>
        <select name="role" class="btn btn-primary dropdown-toggle show" >
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Güncelle</button>
    </form>
    </div>
@endsection
