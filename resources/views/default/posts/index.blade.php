@extends('wink::default.layout')
@section('content')
    <div class="flex justify-center flex-wrap">
        <div class="lg:w-2/3">
            
            @foreach($posts as $post)
                <div class="w-full mb-32">
                    <div>
                        @if($post->featured_image)
                        <a href="{{ route('wink.default.posts.show', $post->slug) }}"
                           class="block h-md bg-cover bg-center bg-no-repeat"
                           style="background-image: url('{{ url($post->featured_image) }}')">
                        </a>
                        @endif
                        <div class="py-5 px-5 sm:px-0">
                            <div>
                                <span class="text-light uppercase font-sans text-xs">Posted on {{ $post->publish_date->format('F, d Y') }}</span>
                                
                                <h3 class="font-sans leading-normal block my-4">
                                    <a class="no-underline text-black hover:underline"
                                       href="{{ route('wink.default.posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                
                                <p class="leading-loose mb-5">
                                    {{ $post->excerpt }}
                                </p>
                            </div>
                            
                            <div class="flex justify-between items-center mt-5">
                                {{-- TODO: Figure out how @themsaid does this or simply remove the tag --}}
                                <span class="font-sans uppercase text-light text-sm">1 min Read</span>
                                <a href="{{ route('wink.default.posts.show', $post->slug) }}"
                                   class="text-light no-underline hover:text-black font-sans uppercase text-sm">Read
                                    Full Post</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    {{-- TODO: Custom pagination view (simple) --}}
    <div class="uppercase flex items-center justify-between flex-1 px-5 mt-10">
        <a href="http://themsaid.com?page=2" rel="next" class="block no-underline text-light hover:text-black">Read More
            Posts</a>
    </div>
@endsection