@extends('posts.layoutposts')
@section('title', 'Kullanıcılar')
@section('main-title', 'Kullanıcı listesi')
@section('subtitle', 'Detay')
@section('content')
    <h1>Kullanıcı Listesi</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
<div class="card-body">
    <table class="table ">

        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Ad</th>
            <th>Email</th>
            <th>Mevcut Rol</th>
            <th>İşlem</th>
        </tr>
        </thead>

        <tbody class="table-border-bottom-0">
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                <td>
                    <a class="btn btn-primary" href="{{ route('admin.edit-role', $user->id) }}">Rolü Düzenle</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="container-fluid d-flex">


        {{-- ms-auto sağa yaslamak için çalışır ama sadece d-flex içinde --}}
        <div class="ms-auto">
            <form action="{{ route('logout') }}" method="POST"  style="display: inline;">
                @csrf
                <button type="submit"  class="btn rounded-pill btn-danger ">Çıkış yap</button>

                </button>
            </form>
        </div>
    </div>

</div>
@endsection
