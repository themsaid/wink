@extends('wink::default.layout')

@section('content')
    <h1 class="mb-20 px-5 text-center font-sans">Posts in: {{ $tag->name }}</h1>
    <div class="flex justify-center flex-wrap">
        <div class="lg:w-2/3">
            @foreach($posts as $post)
                @include('wink::default.partials.post-list')
            @endforeach
        </div>
    </div>
    
    {{-- TODO: Custom pagination view (simple) --}}
    <div class="uppercase flex items-center justify-between flex-1 px-5 mt-10">
        <a href="http://themsaid.com?page=2" rel="next" class="block no-underline text-light hover:text-black">Read More
            Posts</a>
    </div>
@endsection