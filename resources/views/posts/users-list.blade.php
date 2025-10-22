@extends('posts.layoutposts')

@section('title', 'Kullanıcılar')
@section('main-title', 'Kullanıcı listesi')
@section('subtitle', 'Detay')



@section('content')
    <div class="card-body">


            <table class="table table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>İsim</th>
                    <th>Mail</th>
                    <th>Rütbe</th>
                    <th>Kayıt Tarihi</th>

                </tr>
                </thead>
                <tbody>

            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin }}</td>
                    <td>{{ $user->created_at }}</td>

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
