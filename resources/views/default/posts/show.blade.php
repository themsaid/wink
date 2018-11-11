@extends('wink::default.layout')

@section('content')
    <div class="flex justify-center flex-wrap px-5">
        <div class="w-full lg:w-2/3">
            <h1 class="mb-10 text-5xl font-sans">{{ $post->title }}</h1>
            
            <p class="text-light uppercase font-sans mb-10 block">
                Posted on {{ $post->publish_date->format('F, d Y') }}
                in
                @foreach($post->tags as $tag)
                    <a class="text-light hover:text-black" href="{{ route('wink.default.tags.show', $tag->slug) }}">{{ $tag->name }}</a>
                @endforeach
            </p>
            
            @if ($post->featured_image)
            <div class="mb-10">
                <img src="{{ url($post->featured_image) }}" alt="{{ $post->title }}" class="mb-2">
                <div class="text-center text-light">
                    {{ $post->featured_image_caption }}
                </div>
            </div>
            @endif
            
            <div class="leading-loose text-base flex flex-col justify-center items-center post-body">
                {!! $post->body !!}
            </div>
            
            <div class="mt-10 pt-10 flex items-center border-dashed border-t border-border">
                @if ($post->author->avatar)
                <div class="w-32">
                    <img src="{{ url($post->author->avatar) }}" class="rounded-full">
                </div>
                @endif
                <div class="pl-5 leading-loose">
                    By <span class="font-bold">{{ $post->author->name }}</span>
                    <div class="text-sm">{!! $post->author->bio !!}</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('metas')
    <meta name="twitter:title" content="{{ $post->title }} - {{ $post->author->name }}">
    <meta name="og:title" content="{{ $post->title }} - {{ $post->author->name }}">
    
    <meta name="twitter:card" content="summary">
    <meta name="twitter:description" content="{{ $post->excerpt }}">
    
    @if(config('wink.default.twitter'))
    <meta name="twitter:site" content="{{ config('wink.default.twitter') }}">
    @endif
    
    @if($post->featured_image)
    <meta name="twitter:image" content="{{ url($post->featured_image) }}">
    <meta name="og:image" content="{{ url($post->featured_image) }}">
    @endif
    
    <meta name="og:site_name" content="{{ config('app.name') ?? 'My blog' }}">
    <meta name="og:type" content="website">
    <meta name="og:locale" content="{{ app()->getLocale() }}">
@endsection