@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table mt-4">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Thumbnail</th>
                            <th scope="col">Title</th>
                            <th scope="col">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $element)
                            <tr @if ($key == 3) class="bg-secondary text-white" @endif>
                                <th scope="row">
                                    <img src="{{ $element['thumb_url'] }}" width="200" alt="thumb_url">
                                </th>
                                <td>{{ $element['title'] }}</td>
                                <td> @money($element['price']) </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
