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
                                <td class="text-center">
                                    <span id="price-of-{{ $element['id'] }}">@money($element['price'])</span>
                                    <div class="dropdown">
                                        <button
                                            class="btn @if ($key != 3) btn-outline-primary @else btn-primary @endif dropdown-toggle"
                                            type="button" id="{{ 'dropdownMenuButton-' . $key }}" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            Pricing options
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="{{ 'dropdownMenuButton-' . $key }}">
                                            @foreach ($element['pricing_options'] as $option)
                                                <a class="dropdown-item" href="#"
                                                    onclick="getPrice({{ $element['id'] }}, '{{ $option }}')">
                                                    {{ $option }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function getPrice(id, price_name) {
            const params = {
                id: id,
                price_name: price_name
            }

            $.ajax({
                type: 'GET',
                url: '/get-price/',
                data: params,
                success: function(data) {
                    const response = JSON.parse(data);

                    $('#price-of-' + id).html(response.new_price);
                }
            });
        }
    </script>
@endsection
