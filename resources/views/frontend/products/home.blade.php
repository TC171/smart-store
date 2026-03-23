@foreach($featuredCategories as $category)

<a href="{{ route('category.show', $category->slug) }}">

    {{ $category->name }}

</a>

@endforeach