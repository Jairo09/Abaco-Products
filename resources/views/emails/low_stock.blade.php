<x-mail::message>
# Low Stock Alert

The stock for the following products is below the threshold:

<ul>
@foreach ($products as $product)
    <li>
        <strong>Product:</strong> {{ $product->name }} <br>
        <strong>Stock:</strong> {{ $product->stock_quantity }} <br>
        <x-mail::button :url="url('/api/products/' . $product->id)">
            View Product
        </x-mail::button>
    </li>
@endforeach
</ul>

Please restock these products as soon as possible.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>




