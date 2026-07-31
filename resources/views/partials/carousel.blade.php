<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    @if (count(config('global.slider')) > 0)
        <div class="carousel-indicators">
            @foreach(config('global.slider') as $key => $row)
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="{{ $key }}" @if($key == 0) class="active" aria-current="true" @endif aria-label="Slide {{ $key }}"></button>
            @endforeach
        </div>
    @endif
    <div class="carousel-inner">
        @foreach(config('global.slider') as $key => $row)
            <div class="carousel-item @if($key == 0) active @endif" style="height: 32rem">
                <img class="object-fit-cover" src="{{ $row['image'] }}" alt="" width="100%" height="100%">
                <div class="container">
                    <div class="carousel-caption">
                        <h1 class="text-white">{{ $row['title'] }}</h1>
                        <p class="text-white">{{ $row['desc'] }}</p>
                        <p><a class="btn btn-lg btn-primary" href="{{ $row['btn_url'] }}">{{ $row['btn_label'] }}</a></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if (count(config('global.slider')) > 0)
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    @endif
</div>
