    @extends('layouts.front')
    @section('content')
       

        <section class="s-content s-content--no-top-padding">
            
            <div class="s-bricks">
                <div style="display: flex;justify-content: center;"> 
                    <div class="search-container">
                        <!-- Add a search icon using Font Awesome -->
                        <input type="text" id="searchInput" placeholder="Search for posts" style="padding-bottom: 0px;">
                        <i class="fas fa-search"></i>

                        <div id="resultsContainer"></div>
                    </div>
                </div>
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
                                    <h5 style="margin-top: 0px;font-size: 18px;">
                                        {{ $category->title }}
                                    </h5>
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
                                        <h5 style="font-size: 15px;" class="entry__title hover-underline"><a
                                                href="{{ route('posts.view', $post->slug) }}">{{ $post->title }}</a></h5>
                                    </div>
                                   
                                   
                                    @endforeach
                                   
                                </div> <!-- end entry__text -->

                            </article> <!-- end article -->
                        @endforeach

                    </div> <!-- end brick-wrapper -->

                </div> <!-- end masonry -->

            </div> <!-- end s-bricks -->

        </section> <!-- end s-content -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchInput");
        const resultsContainer = document.getElementById("resultsContainer");

        searchInput.addEventListener("input", debounce(searchPosts, 300));

        async function searchPosts() {
            const query = searchInput.value;
            resultsContainer.innerHTML = ''; // Clear previous results

            if (query.trim() === "") {
            return;
            }

            try {
            const response = await fetch("{{ route('search') }}?q=" + encodeURIComponent(query));

            const data = await response.json();
                console.log(data)
            data.forEach(post => {
                const postResult = document.createElement("div");
                postResult.classList.add("post-result");

                const postImage = document.createElement("img");
                postImage.src = "{{ asset('images/')}}" +'/'+post.image;
                postImage.alt = "Post Image";

                const postTitle = document.createElement("p");
                postTitle.textContent = post.title;

                postResult.appendChild(postImage);
                postResult.appendChild(postTitle);

                resultsContainer.appendChild(postResult);
            });
            } catch (error) {
            console.error("Error fetching data:", error);
            }
        }

        // Debounce function to delay API calls
        function debounce(func, delay) {
            let timeoutId;
            return function () {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(func, delay);
            };
        }
    });
</script>
    @endsection
