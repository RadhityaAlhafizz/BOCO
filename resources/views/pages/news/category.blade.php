@extends('layouts.app')

@section('title', $category->slug)

@section('content')

    <!-- Header -->
    <div class="w-full mb-16 bg-[#F6F6F6]">
      <h1 class="text-center font-bold text-2xl p-24">{{ $category->title }}</h1>
    </div>

    <div class=" flex flex-col gap-5 px-4 lg:px-14">
      <div class="grid sm:grid-cols-1 gap-5 lg:grid-cols-4">
        @foreach ( $category->news as $news)  
        <a href="{{ route('news.show', $news->slug)}}">
          <div
            class="border border-slate-200 p-3 rounded-xl hover:border-primary hover:cursor-pointer transition duration-300 ease-in-out" style="height:100%;">
            <div class="bg-primary text-white rounded-full w-fit px-5 py-1 font-normal ml-2 mt-2 text-sm absolute">
              {{ $news->newsCategory->title}}</div>
            <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="" class="w-full object-cover object-top rounded-xl mb-3" style="height: 150px;">
            <p class="font-bold text-base mb-1">{{ $news->title}}</p>
            <p class="text-slate-400"> {{ ($news->created_at)->format('d F Y') }}</p>
          </div>
        </a>
        @endforeach
      </div>
    </div>  

@endsection