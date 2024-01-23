    @extends('layouts.front')
    @section('content')
       

        <section class="s-content s-content--no-top-padding">

            <div class="s-bricks">

                <div class="masonry">
                    <div class="bricks-wrapper h-group">

                        <div class="grid-sizer"></div>

                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        @foreach ($categories_present as $category)
                            <article class="brick entry" data-aos="fade-up">
                                <div style="display: flex;justify-content: space-between;">
                                    <h3 style="margin-top: 0px">
                                        {{ $category->title }}
                                    </h3>
                                    <a href="{{ route('categories.view', $category->category_id) }}" class="entry__more-link" >View
                                        More
                                    </a>
                                </div>
                                <div class="entry__thumb">
                                    <a  class="thumb-link">
                                        <img src="{{ asset('images/' . $category->image) }}" alt="">
                                    </a>
                                </div> <!-- end entry__thumb -->

                                <div class="entry__text">
                                    @foreach($category->posts as $post )
                                    <div class="entry__header">
                                        <h4 class="entry__title"><a
                                                href="{{ route('posts.view', $post->slug) }}">{{ $post->title }}</a></h4>

                                        <div class="entry__meta">
                                            <p>
                                                {{ $post->excerpt }}
                                            </p>
                                            
                                        </div>
                                    </div>
                                   
                                   
                                    @endforeach
                                   
                                </div> <!-- end entry__text -->

                            </article> <!-- end article -->
                        @endforeach

                    </div> <!-- end brick-wrapper -->

                </div> <!-- end masonry -->

            </div> <!-- end s-bricks -->

        </section> <!-- end s-content -->
    @endsection
