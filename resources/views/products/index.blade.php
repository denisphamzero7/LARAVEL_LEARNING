<h1> Danh sách sản phẩm</h1>

@foreach ($products as $item)
    <p>{{ $item->name }}</p>
@endforeach
